<?php
require __DIR__ . '/../../app/bootstrap.php';
require __DIR__ . '/../../app/helpers/reservations.php';
$section = 'bookings';
$id = array_key_exists('id', $_GET) ? requestId() : null;
$title = $id === null ? 'Criar reserva' : 'Editar reserva';
$values = ['customer_id' => '', 'trip_id' => '', 'passengers' => '', 'booking_date' => '', 'status' => 'pendente'];
$values['booking_date'] = date('Y-m-d');
if ($id !== null) {
    $values = fetchRecord('SELECT * FROM bookings WHERE id = :id', $id);
}
$options = [];
try {
    $options['customer_id'] = fetchAll("SELECT id, name AS label FROM customers ORDER BY name");
    $options['trip_id'] = fetchAll("SELECT t.id, CONCAT(r.name, ' | ', b.name, ' | ', t.trip_date, ' ', t.trip_time) AS label FROM trips t JOIN routes r ON r.id=t.route_id JOIN boats b ON b.id=t.boat_id ORDER BY t.trip_date, t.trip_time");
} catch (PDOException $exception) {
    error_log($exception->getMessage());
    pageError(503, 'Não foi possível carregar as opções.');
}
$options['status'] = [['id'=>'pendente','label'=>'Pendente'],['id'=>'confirmada','label'=>'Confirmada'],['id'=>'cancelada','label'=>'Cancelada']];
$options['is_cancelled'] = [['id'=>'0','label'=>'Disponível / esgotado (calculado)'],['id'=>'1','label'=>'Cancelado']];

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireValidPost();
    $values['customer_id'] = inputText($_POST, 'customer_id');
    $values['trip_id'] = inputText($_POST, 'trip_id');
    $values['passengers'] = inputText($_POST, 'passengers');
    $values['booking_date'] = inputText($_POST, 'booking_date');
    $values['status'] = inputText($_POST, 'status');
    // EXEMPLO: Validate references and scalar fields before starting the transaction.
    if (!in_array($values['customer_id'], array_map('strval', array_column($options['customer_id'], 'id')), true)) {
        $errors['customer_id'] = 'Selecione cliente válido.';
    }
    if (!in_array($values['trip_id'], array_map('strval', array_column($options['trip_id'], 'id')), true)) {
        $errors['trip_id'] = 'Selecione passeio válido.';
    }
    if (!validDate($values['booking_date'])) { $errors['booking_date'] = 'Data da reserva inválida.'; }
    if (filter_var($values['passengers'], FILTER_VALIDATE_INT, ['options'=>['min_range'=>1,'max_range'=>65535]]) === false) {
        $errors['passengers'] = 'O número de passageiros deve ser um inteiro entre 1 e 65535.';
    }
    if (!in_array($values['status'], ['pendente','confirmada','cancelada'], true)) {
        $errors['status'] = 'Estado inválido.';
    }
    if (!$errors) {
        $connection = null;
        try {
            $connection = beginReservationTransaction();
            $oldTripId = 0;
            if ($id !== null) {
                $statement = $connection->prepare('SELECT trip_id FROM bookings WHERE id = :id FOR UPDATE');
                $statement->execute(['id'=>$id]);
                $oldTripId = $statement->fetchColumn();
                if ($oldTripId === false) { throw new DomainException('A reserva já não existe.'); }
            }
            // ATENCAO: Lock both trips when moving a booking. Failed transactions roll back.
            $tripIds = array_unique([(int) $values['trip_id'], (int) $oldTripId]);
            sort($tripIds);
            foreach ($tripIds as $tripId) {
                if ($tripId > 0) { lockTrip($connection, $tripId); }
            }
            $trip = lockTrip($connection, (int) $values['trip_id']);
            $capacity = boatCapacity($connection, (int) $trip['boat_id']);
            $reserved = occupiedSeats($connection, (int) $trip['id'], $id ?? 0);
            $available = $capacity - $reserved;
            // ADAPTAR: Pending and confirmed bookings occupy seats; cancelled ones do not.
            if ($values['status'] !== 'cancelada') {
                if ($trip['is_cancelled']) { throw new DomainException('O passeio está cancelado.', 2); }
                if ((int) $values['passengers'] > $available) {
                    throw new DomainException('Lugares insuficientes. Disponíveis para esta reserva: ' . $available . '.', 3);
                }
            }
            $parameters = ['customer_id'=>$values['customer_id'],'trip_id'=>$values['trip_id'],'passengers'=>$values['passengers'],'booking_date'=>$values['booking_date'],'status'=>$values['status']];
            if ($id === null) {
                $statement = $connection->prepare('INSERT INTO bookings (customer_id,trip_id,passengers,booking_date,status) VALUES (:customer_id,:trip_id,:passengers,:booking_date,:status)');
            } else {
                $statement = $connection->prepare('UPDATE bookings SET customer_id = :customer_id,trip_id = :trip_id,passengers = :passengers,booking_date = :booking_date,status = :status WHERE id = :id');
                $parameters['id'] = $id;
            }
            $statement->execute($parameters);
            $savedId = $id ?? (int) $connection->lastInsertId();
            $connection->commit();
            flash('success', 'Registo guardado com sucesso.');
            redirectTo('bookings/view.php?id=' . $savedId);
        } catch (DomainException | PDOException $exception) {
            if ($connection !== null && $connection->inTransaction()) { $connection->rollBack(); }
            if ($exception instanceof DomainException) {
                $field = match ($exception->getCode()) { 1 => 'boat_id', 2 => 'trip_id', 3 => 'passengers', default => '_form' };
                $errors[$field] = $exception->getMessage();
            } else {
                error_log($exception->getMessage());
                $conflict = in_array((int) ($exception->errorInfo[1] ?? 0), [1205, 1213, 1452], true);
                http_response_code($conflict ? 409 : 503);
                $errors['_form'] = $conflict
                    ? 'Os dados foram alterados entretanto. Atualize a página e tente novamente.'
                    : 'Não foi possível guardar o registo. Tente novamente.';
            }
        }
    }
    if ($errors && http_response_code() === 200) { http_response_code(422); }
}
require __DIR__ . '/../../app/views/header.php';
?>
<?php if (isset($errors['_form'])): ?><p class="alert alert-danger" role="alert"><?= e($errors['_form']) ?></p><?php endif; ?>
<form data-validate method="post" class="card card-body riatour-edit shadow-sm">
<p class="small text-secondary">* Campo obrigatório</p>
<input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
<div class="mb-3"><label class="form-label" for="customer_id">Cliente <span aria-hidden="true">*</span></label>
<select class="form-select<?= isset($errors['customer_id']) ? ' is-invalid' : '' ?>"<?= fieldAttributes($errors, 'customer_id') ?> id="customer_id" name="customer_id" required><option value="">Selecionar</option><?php foreach ($options['customer_id'] as $option): ?><option value="<?= e($option['id']) ?>" <?= (string) $values['customer_id'] === (string) $option['id'] ? 'selected' : '' ?>><?= e($option['label']) ?></option><?php endforeach; ?></select><?php fieldFeedback($errors, 'customer_id', (string) $values['customer_id']); ?></div>
<div class="mb-3"><label class="form-label" for="trip_id">Passeio <span aria-hidden="true">*</span></label>
<select class="form-select<?= isset($errors['trip_id']) ? ' is-invalid' : '' ?>"<?= fieldAttributes($errors, 'trip_id') ?> id="trip_id" name="trip_id" required><option value="">Selecionar</option><?php foreach ($options['trip_id'] as $option): ?><option value="<?= e($option['id']) ?>" <?= (string) $values['trip_id'] === (string) $option['id'] ? 'selected' : '' ?>><?= e($option['label']) ?></option><?php endforeach; ?></select><?php fieldFeedback($errors, 'trip_id', (string) $values['trip_id']); ?></div>
<div class="mb-3"><label class="form-label" for="passengers">Número de passageiros <span aria-hidden="true">*</span></label>
<input class="form-control<?= isset($errors['passengers']) ? ' is-invalid' : '' ?>"<?= fieldAttributes($errors, 'passengers') ?> id="passengers" name="passengers" type="number" min="1" max="65535"  value="<?= e($values['passengers']) ?>" required><?php fieldFeedback($errors, 'passengers', (string) $values['passengers']); ?></div>
<div class="mb-3"><label class="form-label" for="booking_date">Data da reserva <span aria-hidden="true">*</span></label>
<input class="form-control<?= isset($errors['booking_date']) ? ' is-invalid' : '' ?>"<?= fieldAttributes($errors, 'booking_date') ?> id="booking_date" name="booking_date" type="date"   value="<?= e($values['booking_date']) ?>" required><?php fieldFeedback($errors, 'booking_date', (string) $values['booking_date']); ?></div>
<div class="mb-3"><label class="form-label" for="status">Estado <span aria-hidden="true">*</span></label>
<select class="form-select<?= isset($errors['status']) ? ' is-invalid' : '' ?>"<?= fieldAttributes($errors, 'status') ?> id="status" name="status" required><option value="">Selecionar</option><?php foreach ($options['status'] as $option): ?><option value="<?= e($option['id']) ?>" <?= (string) $values['status'] === (string) $option['id'] ? 'selected' : '' ?>><?= e($option['label']) ?></option><?php endforeach; ?></select><?php fieldFeedback($errors, 'status', (string) $values['status']); ?></div>
<div class="d-flex flex-wrap gap-2"><button type="submit" class="btn btn-primary">Guardar</button> <a class="btn btn-outline-secondary" href="<?= e(url('bookings/')) ?>">Cancelar</a></div>
</form>
<script src="<?= e(url('assets/validation.js')) ?>" defer></script>
<?php require __DIR__ . '/../../app/views/footer.php'; ?>
