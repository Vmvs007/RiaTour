<?php
require __DIR__ . '/../../app/bootstrap.php';
$section = 'customers';
$id = array_key_exists('id', $_GET) ? requestId() : null;
$title = $id === null ? 'Criar cliente' : 'Editar cliente';
$values = ['name' => '', 'email' => '', 'phone' => ''];
if ($id !== null) {
    $values = fetchRecord('SELECT * FROM customers WHERE id = :id', $id);
}
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireValidPost();
    // EXEMPLO: Read submitted values and validate them on the server.
    // ADAPTAR: Match these fields and rules to the new entity.
    $values['name'] = inputText($_POST, 'name');
    $values['email'] = inputText($_POST, 'email');
    $values['phone'] = inputText($_POST, 'phone');
    validateText($values['name'], 'O nome', 120, $errors, 'name');
    validateText($values['email'], 'O email', 254, $errors, 'email');
    validateText($values['phone'], 'O telefone', 32, $errors, 'phone');
    if (!isset($errors['email']) && filter_var($values['email'], FILTER_VALIDATE_EMAIL) === false) {
        $errors['email'] = 'Introduza um endereço de email válido.';
    }
    if ($errors === []) {
        $connection = null;
        try {
            $connection = database();
            $connection->beginTransaction();
            if ($id !== null) {
                // ATENCAO: Lock the row and check it still exists before updating.
                $statement = $connection->prepare('SELECT id FROM customers WHERE id = :id FOR UPDATE');
                $statement->execute(['id' => $id]);
                if ($statement->fetch() === false) {
                    $connection->rollBack();
                    pageError(404, 'O registo já não existe.');
                }
            }

            if ($errors === []) {
                $parameters = ['name' => $values['name'], 'email' => $values['email'], 'phone' => $values['phone']];
                if ($id === null) {
                    // EXEMPLO: INSERT creates the record using named parameters.
                    $statement = $connection->prepare('INSERT INTO customers (name, email, phone) VALUES (:name, :email, :phone)');
                } else {
                    // EXEMPLO: UPDATE targets exactly one validated primary key.
                    $statement = $connection->prepare('UPDATE customers SET name = :name, email = :email, phone = :phone WHERE id = :id');
                    $parameters['id'] = $id;
                }
                $statement->execute($parameters);
                $savedId = $id ?? (int) $connection->lastInsertId();
                $connection->commit();
                flash('success', 'Registo guardado com sucesso.');
                redirectTo('customers/view.php?id=' . $savedId);
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
<form data-validate method="post" action="<?= e(url('customers/form.php' . ($id === null ? '' : '?id=' . $id))) ?>" class="card card-body riatour-edit shadow-sm">
<p class="small text-secondary">* Campo obrigatório</p>
    <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
    <?php if ($id !== null): ?><p>ID <?= e($id) ?></p><?php endif; ?>
    <div class="mb-3">
        <label for="name" class="form-label">Nome <span aria-hidden="true">*</span></label>
        <input id="name" name="name" type="text" class="form-control<?= isset($errors['name']) ? ' is-invalid' : '' ?>"<?= fieldAttributes($errors, 'name') ?> value="<?= e($values['name']) ?>" maxlength="120" required>
        <?php fieldFeedback($errors, 'name'); ?>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email <span aria-hidden="true">*</span></label>
        <input id="email" name="email" type="email" class="form-control<?= isset($errors['email']) ? ' is-invalid' : '' ?>"<?= fieldAttributes($errors, 'email') ?> value="<?= e($values['email']) ?>" maxlength="254" required>
        <?php fieldFeedback($errors, 'email'); ?>
    </div>
    <div class="mb-3">
        <label for="phone" class="form-label">Telefone <span aria-hidden="true">*</span></label>
        <input id="phone" name="phone" type="tel" class="form-control<?= isset($errors['phone']) ? ' is-invalid' : '' ?>"<?= fieldAttributes($errors, 'phone') ?> value="<?= e($values['phone']) ?>" maxlength="32" required>
        <?php fieldFeedback($errors, 'phone'); ?>
    </div>
    <div><button class="btn btn-primary" type="submit">Guardar</button>
    <a class="btn btn-outline-secondary" href="<?= e(url('customers/')) ?>">Cancelar</a></div>
</form>
<script src="<?= e(url('assets/validation.js')) ?>" defer></script>
<?php require __DIR__ . '/../../app/views/footer.php'; ?>
