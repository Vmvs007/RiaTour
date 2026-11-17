<?php
require __DIR__ . '/../../app/bootstrap.php';
$section = 'boats';
$id = array_key_exists('id', $_GET) ? requestId() : null;
$title = $id === null ? 'Criar barco' : 'Editar barco';
$values = ['name' => '', 'capacity' => '', 'status' => 'ativo'];
if ($id !== null) {
    $values = fetchRecord('SELECT * FROM boats WHERE id = :id', $id);
}
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireValidPost();
    // EXEMPLO: Read submitted values and validate them on the server.
    // ADAPTAR: Match these fields and rules to the new entity.
    $values['name'] = inputText($_POST, 'name');
    $values['capacity'] = inputText($_POST, 'capacity');
    $values['status'] = inputText($_POST, 'status');
    validateText($values['name'], 'O nome', 100, $errors, 'name');
    if (filter_var($values['capacity'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 65535]]) === false) {
        $errors['capacity'] = 'A capacidade deve ser um número inteiro entre 1 e 65535.';
    }
    if (!in_array($values['status'], ['ativo', 'inativo'], true)) {
        $errors['status'] = 'Selecione um estado válido.';
    }
    if ($errors === []) {
        $connection = null;
        try {
            $connection = database();
            $connection->beginTransaction();
            if ($id !== null) {
                // ATENCAO: Lock the row and check it still exists before updating.
                $statement = $connection->prepare('SELECT id FROM boats WHERE id = :id FOR UPDATE');
                $statement->execute(['id' => $id]);
                if ($statement->fetch() === false) {
                    $connection->rollBack();
                    pageError(404, 'O registo já não existe.');
                }
            }

            if ($id !== null) {
                // ATENCAO: A lower capacity must still fit every existing trip.
                // Lock related trips and bookings before counting occupied seats.
                $statement = $connection->prepare('SELECT id FROM trips WHERE boat_id = :id ORDER BY id FOR UPDATE');
                $statement->execute(['id' => $id]);
                $trips = $statement->fetchAll();
                foreach ($trips as $trip) {
                    $statement = $connection->prepare("SELECT passengers FROM bookings WHERE trip_id = :trip_id AND status IN ('pendente', 'confirmada') FOR UPDATE");
                    $statement->execute(['trip_id' => $trip['id']]);
                    $occupied = array_sum(array_column($statement->fetchAll(), 'passengers'));
                    if ($occupied > (int) $values['capacity']) {
                        $errors['capacity'] = 'A capacidade é inferior aos lugares reservados num passeio deste barco.';
                        break;
                    }
                }
            }

            if ($errors === []) {
                $parameters = ['name' => $values['name'], 'capacity' => $values['capacity'], 'status' => $values['status']];
                if ($id === null) {
                    // EXEMPLO: INSERT creates the record using named parameters.
                    $statement = $connection->prepare('INSERT INTO boats (name, capacity, status) VALUES (:name, :capacity, :status)');
                } else {
                    // EXEMPLO: UPDATE targets exactly one validated primary key.
                    $statement = $connection->prepare('UPDATE boats SET name = :name, capacity = :capacity, status = :status WHERE id = :id');
                    $parameters['id'] = $id;
                }
                $statement->execute($parameters);
                $savedId = $id ?? (int) $connection->lastInsertId();
                $connection->commit();
                flash('success', 'Registo guardado com sucesso.');
                redirectTo('boats/view.php?id=' . $savedId);
            }
            $connection->rollBack();
        } catch (PDOException $exception) {
            if ($connection !== null && $connection->inTransaction()) {
                $connection->rollBack();
            }
            error_log($exception->getMessage());
            http_response_code(503);
            $errors['_form'] = 'Não foi possível guardar o registo. Tente novamente.';
        }
    }
    if ($errors !== [] && http_response_code() !== 503) {
        http_response_code(422);
    }
}
require __DIR__ . '/../../app/views/header.php';
?>
<?php if (isset($errors['_form'])): ?><p class="alert alert-danger" role="alert"><?= e($errors['_form']) ?></p><?php endif; ?>
<form data-validate method="post" action="<?= e(url('boats/form.php' . ($id === null ? '' : '?id=' . $id))) ?>" class="card card-body riatour-edit shadow-sm">
<p class="small text-secondary">* Campo obrigatório</p>
    <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
    <?php if ($id !== null): ?><p>ID <?= e($id) ?></p><?php endif; ?>
    <div class="mb-3">
        <label for="name" class="form-label">Nome <span aria-hidden="true">*</span></label>
        <input id="name" name="name" type="text" class="form-control<?= isset($errors['name']) ? ' is-invalid' : '' ?>"<?= fieldAttributes($errors, 'name') ?> value="<?= e($values['name']) ?>" maxlength="100" required>
        <?php fieldFeedback($errors, 'name'); ?>
    </div>
    <div class="mb-3">
        <label for="capacity" class="form-label">Capacidade máxima <span aria-hidden="true">*</span></label>
        <input id="capacity" name="capacity" type="number" class="form-control<?= isset($errors['capacity']) ? ' is-invalid' : '' ?>"<?= fieldAttributes($errors, 'capacity') ?> value="<?= e($values['capacity']) ?>" min="1" max="65535" step="1" required>
        <?php fieldFeedback($errors, 'capacity', (string) $values['capacity']); ?>
    </div>
    <div class="mb-3">
        <label for="status" class="form-label">Estado <span aria-hidden="true">*</span></label>
        <select id="status" name="status" class="form-select<?= isset($errors['status']) ? ' is-invalid' : '' ?>"<?= fieldAttributes($errors, 'status') ?> required><?php foreach (['ativo' => 'Ativo', 'inativo' => 'Inativo'] as $value => $label): ?><option value="<?= e($value) ?>" <?= $values['status'] === $value ? 'selected' : '' ?>><?= e($label) ?></option><?php endforeach; ?></select>
        <?php fieldFeedback($errors, 'status', (string) $values['status']); ?>
    </div>
    <div><button class="btn btn-primary" type="submit">Guardar</button>
    <a class="btn btn-outline-secondary" href="<?= e(url('boats/')) ?>">Cancelar</a></div>
</form>
<script src="<?= e(url('assets/validation.js')) ?>" defer></script>
<?php require __DIR__ . '/../../app/views/footer.php'; ?>
