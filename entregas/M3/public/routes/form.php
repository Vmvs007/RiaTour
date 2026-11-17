<?php
require __DIR__ . '/../../app/bootstrap.php';
$section = 'routes';
$id = array_key_exists('id', $_GET) ? requestId() : null;
$title = $id === null ? 'Criar percurso' : 'Editar percurso';
$values = ['name' => '', 'description' => '', 'duration_minutes' => '', 'price_per_passenger' => ''];
if ($id !== null) {
    $values = fetchRecord('SELECT * FROM routes WHERE id = :id', $id);
}
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireValidPost();
    // EXEMPLO: Read submitted values and validate them on the server.
    // ADAPTAR: Match these fields and rules to the new entity.
    $values['name'] = inputText($_POST, 'name');
    $values['description'] = inputText($_POST, 'description');
    $values['duration_minutes'] = inputText($_POST, 'duration_minutes');
    $values['price_per_passenger'] = inputText($_POST, 'price_per_passenger');
    validateText($values['name'], 'O nome', 100, $errors, 'name');
    if ($values['description'] === '' || strlen($values['description']) > 65535) {
        $errors['description'] = 'A descrição é obrigatória e deve ocupar até 65535 bytes em UTF-8.';
    }
    if (filter_var($values['duration_minutes'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 65535]]) === false) {
        $errors['duration_minutes'] = 'A duração deve ser um número inteiro entre 1 e 65535 minutos.';
    }
    // EXEMPLO: Accept a decimal comma and preserve money as a decimal string.
    $normalizedPrice = str_replace(',', '.', $values['price_per_passenger']);
    if (!preg_match('/^[0-9]{1,6}(\\.[0-9]{1,2})?$/D', $normalizedPrice)
        || (float) $normalizedPrice <= 0) {
        $errors['price_per_passenger'] = 'O preço deve ser positivo, até 999999,99 €, com no máximo duas casas decimais.';
    }
    if ($errors === []) {
        $connection = null;
        try {
            $connection = database();
            $connection->beginTransaction();
            if ($id !== null) {
                // ATENCAO: Lock the row and check it still exists before updating.
                $statement = $connection->prepare('SELECT id FROM routes WHERE id = :id FOR UPDATE');
                $statement->execute(['id' => $id]);
                if ($statement->fetch() === false) {
                    $connection->rollBack();
                    pageError(404, 'O registo já não existe.');
                }
            }

            if ($errors === []) {
                $parameters = ['name' => $values['name'], 'description' => $values['description'], 'duration_minutes' => $values['duration_minutes'], 'price_per_passenger' => $normalizedPrice];
                if ($id === null) {
                    // EXEMPLO: INSERT creates the record using named parameters.
                    $statement = $connection->prepare('INSERT INTO routes (name, description, duration_minutes, price_per_passenger) VALUES (:name, :description, :duration_minutes, :price_per_passenger)');
                } else {
                    // EXEMPLO: UPDATE targets exactly one validated primary key.
                    $statement = $connection->prepare('UPDATE routes SET name = :name, description = :description, duration_minutes = :duration_minutes, price_per_passenger = :price_per_passenger WHERE id = :id');
                    $parameters['id'] = $id;
                }
                $statement->execute($parameters);
                $savedId = $id ?? (int) $connection->lastInsertId();
                $connection->commit();
                flash('success', 'Registo guardado com sucesso.');
                redirectTo('routes/view.php?id=' . $savedId);
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
<form data-validate method="post" action="<?= e(url('routes/form.php' . ($id === null ? '' : '?id=' . $id))) ?>" class="card card-body riatour-edit shadow-sm">
<p class="small text-secondary">* Campo obrigatório</p>
    <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
    <?php if ($id !== null): ?><p>ID <?= e($id) ?></p><?php endif; ?>
    <div class="mb-3">
        <label for="name" class="form-label">Nome <span aria-hidden="true">*</span></label>
        <input id="name" name="name" type="text" class="form-control<?= isset($errors['name']) ? ' is-invalid' : '' ?>"<?= fieldAttributes($errors, 'name') ?> value="<?= e($values['name']) ?>" maxlength="100" required>
        <?php fieldFeedback($errors, 'name'); ?>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Descrição <span aria-hidden="true">*</span></label>
        <textarea id="description" name="description" class="form-control<?= isset($errors['description']) ? ' is-invalid' : '' ?>"<?= fieldAttributes($errors, 'description') ?> rows="4" required><?= e($values['description']) ?></textarea>
        <?php fieldFeedback($errors, 'description'); ?>
    </div>
    <div class="mb-3">
        <label for="duration_minutes" class="form-label">Duração (minutos) <span aria-hidden="true">*</span></label>
        <input id="duration_minutes" name="duration_minutes" type="number" class="form-control<?= isset($errors['duration_minutes']) ? ' is-invalid' : '' ?>"<?= fieldAttributes($errors, 'duration_minutes') ?> value="<?= e($values['duration_minutes']) ?>" min="1" max="65535" step="1" required>
        <?php fieldFeedback($errors, 'duration_minutes', (string) $values['duration_minutes']); ?>
    </div>
    <div class="mb-3">
        <label for="price_per_passenger" class="form-label">Preço por passageiro (€) <span aria-hidden="true">*</span></label>
        <input id="price_per_passenger" name="price_per_passenger" type="text" class="form-control<?= isset($errors['price_per_passenger']) ? ' is-invalid' : '' ?>"<?= fieldAttributes($errors, 'price_per_passenger') ?> value="<?= e($values['price_per_passenger']) ?>" inputmode="decimal" pattern="[0-9]{1,6}([.,][0-9]{1,2})?" data-positive-price required>
        <?php fieldFeedback($errors, 'price_per_passenger'); ?>
    </div>
    <div><button class="btn btn-primary" type="submit">Guardar</button>
    <a class="btn btn-outline-secondary" href="<?= e(url('routes/')) ?>">Cancelar</a></div>
</form>
<script src="<?= e(url('assets/validation.js')) ?>" defer></script>
<?php require __DIR__ . '/../../app/views/footer.php'; ?>
