<?php
require __DIR__ . '/../../app/bootstrap.php';
require __DIR__ . '/../../app/helpers/reservations.php';
$section = 'trips';
$id = array_key_exists('id', $_GET) ? requestId() : null;
$title = $id === null ? 'Criar passeio' : 'Editar passeio';
$values = ['boat_id' => '', 'route_id' => '', 'trip_date' => '', 'trip_time' => '', 'is_cancelled' => '0'];
$values['trip_date'] = date('Y-m-d');
if ($id !== null) {
    $values = fetchRecord('SELECT * FROM trips WHERE id = :id', $id);
}
$options = [];
try {
    $options['boat_id'] = fetchAll("SELECT id, name AS label FROM boats ORDER BY name");
    $options['route_id'] = fetchAll("SELECT id, name AS label FROM routes ORDER BY name");
} catch (PDOException $exception) {
    error_log($exception->getMessage());
    pageError(503, 'Não foi possível carregar as opções.');
}
$options['status'] = [['id'=>'pendente','label'=>'Pendente'],['id'=>'confirmada','label'=>'Confirmada'],['id'=>'cancelada','label'=>'Cancelada']];
$options['is_cancelled'] = [['id'=>'0','label'=>'Disponível / esgotado (calculado)'],['id'=>'1','label'=>'Cancelado']];

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireValidPost();
    $values['boat_id'] = inputText($_POST, 'boat_id');
    $values['route_id'] = inputText($_POST, 'route_id');
    $values['trip_date'] = inputText($_POST, 'trip_date');
    $values['trip_time'] = inputText($_POST, 'trip_time');
    $values['is_cancelled'] = inputText($_POST, 'is_cancelled');
    // EXEMPLO: Validate references and scalar fields before starting the transaction.
    if (!in_array($values['boat_id'], array_map('strval', array_column($options['boat_id'], 'id')), true)) {
        $errors['boat_id'] = 'Selecione barco válido.';
    }
    if (!in_array($values['route_id'], array_map('strval', array_column($options['route_id'], 'id')), true)) {
        $errors['route_id'] = 'Selecione percurso válido.';
    }
    if (!validDate($values['trip_date']) || $values['trip_date'] < date('Y-m-d')) {
        $errors['trip_date'] = 'A data do passeio deve ser válida e igual ou posterior a hoje.';
    }
    if (!preg_match('/^(?:[01][0-9]|2[0-3]):[0-5][0-9](?::[0-5][0-9])?$/D', $values['trip_time'])) {
        $errors['trip_time'] = 'Introduza uma hora válida.';
    }
    if (!in_array($values['is_cancelled'], ['0', '1'], true)) {
        $errors['is_cancelled'] = 'Estado inválido.';
    }
    if (!$errors) {
        $connection = null;
        try {
            $connection = beginReservationTransaction();
            if ($id !== null) { lockTrip($connection, $id); }
            $capacity = boatCapacity($connection, (int) $values['boat_id']);
            if ($id !== null && occupiedSeats($connection, $id) > $capacity) {
                throw new DomainException('O barco não tem capacidade para as reservas existentes.', 1);
            }
            $parameters = ['boat_id'=>$values['boat_id'],'route_id'=>$values['route_id'],'trip_date'=>$values['trip_date'],'trip_time'=>$values['trip_time'],'is_cancelled'=>$values['is_cancelled']];
            if ($id === null) {
                $statement = $connection->prepare('INSERT INTO trips (boat_id,route_id,trip_date,trip_time,is_cancelled) VALUES (:boat_id,:route_id,:trip_date,:trip_time,:is_cancelled)');
            } else {
                $statement = $connection->prepare('UPDATE trips SET boat_id = :boat_id,route_id = :route_id,trip_date = :trip_date,trip_time = :trip_time,is_cancelled = :is_cancelled WHERE id = :id');
                $parameters['id'] = $id;
            }
            $statement->execute($parameters);
            $savedId = $id ?? (int) $connection->lastInsertId();
            $connection->commit();
            flash('success', 'Registo guardado com sucesso.');
            redirectTo('trips/view.php?id=' . $savedId);
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
<div class="mb-3"><label class="form-label" for="boat_id">Barco <span aria-hidden="true">*</span></label>
<select class="form-select<?= isset($errors['boat_id']) ? ' is-invalid' : '' ?>"<?= fieldAttributes($errors, 'boat_id') ?> id="boat_id" name="boat_id" required><option value="">Selecionar</option><?php foreach ($options['boat_id'] as $option): ?><option value="<?= e($option['id']) ?>" <?= (string) $values['boat_id'] === (string) $option['id'] ? 'selected' : '' ?>><?= e($option['label']) ?></option><?php endforeach; ?></select><?php fieldFeedback($errors, 'boat_id', (string) $values['boat_id']); ?></div>
<div class="mb-3"><label class="form-label" for="route_id">Percurso <span aria-hidden="true">*</span></label>
<select class="form-select<?= isset($errors['route_id']) ? ' is-invalid' : '' ?>"<?= fieldAttributes($errors, 'route_id') ?> id="route_id" name="route_id" required><option value="">Selecionar</option><?php foreach ($options['route_id'] as $option): ?><option value="<?= e($option['id']) ?>" <?= (string) $values['route_id'] === (string) $option['id'] ? 'selected' : '' ?>><?= e($option['label']) ?></option><?php endforeach; ?></select><?php fieldFeedback($errors, 'route_id', (string) $values['route_id']); ?></div>
<div class="mb-3"><label class="form-label" for="trip_date">Data <span aria-hidden="true">*</span></label>
<input class="form-control<?= isset($errors['trip_date']) ? ' is-invalid' : '' ?>"<?= fieldAttributes($errors, 'trip_date') ?> id="trip_date" name="trip_date" type="date" min="<?= e(date('Y-m-d')) ?>"   value="<?= e($values['trip_date']) ?>" required><?php fieldFeedback($errors, 'trip_date', (string) $values['trip_date']); ?></div>
<div class="mb-3"><label class="form-label" for="trip_time">Hora <span aria-hidden="true">*</span></label>
<input class="form-control<?= isset($errors['trip_time']) ? ' is-invalid' : '' ?>"<?= fieldAttributes($errors, 'trip_time') ?> id="trip_time" name="trip_time" type="time"  step="1" value="<?= e($values['trip_time']) ?>" required><?php fieldFeedback($errors, 'trip_time', (string) $values['trip_time']); ?></div>
<div class="mb-3"><label class="form-label" for="is_cancelled">Estado <span aria-hidden="true">*</span></label>
<select class="form-select<?= isset($errors['is_cancelled']) ? ' is-invalid' : '' ?>"<?= fieldAttributes($errors, 'is_cancelled') ?> id="is_cancelled" name="is_cancelled" required><option value="">Selecionar</option><?php foreach ($options['is_cancelled'] as $option): ?><option value="<?= e($option['id']) ?>" <?= (string) $values['is_cancelled'] === (string) $option['id'] ? 'selected' : '' ?>><?= e($option['label']) ?></option><?php endforeach; ?></select><?php fieldFeedback($errors, 'is_cancelled', (string) $values['is_cancelled']); ?></div>
<div class="d-flex flex-wrap gap-2"><button type="submit" class="btn btn-primary">Guardar</button> <a class="btn btn-outline-secondary" href="<?= e(url('trips/')) ?>">Cancelar</a></div>
</form>
<script src="<?= e(url('assets/validation.js')) ?>" defer></script>
<?php require __DIR__ . '/../../app/views/footer.php'; ?>
