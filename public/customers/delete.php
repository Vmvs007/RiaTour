<?php
require __DIR__ . '/../../app/bootstrap.php';
$section = 'customers';
$title = 'Eliminar cliente';
$id = requestId();
$record = fetchRecord('SELECT * FROM customers WHERE id = :id', $id);
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireValidPost();
    try {
        // EXEMPLO: A GET only displays confirmation. DELETE runs after a valid POST.
        // ADAPTAR: Keep a parameter for the primary key in the new table.
        $statement = database()->prepare('DELETE FROM customers WHERE id = :id');
        $statement->execute(['id' => $id]);
        if ($statement->rowCount() === 0) {
            pageError(404, 'O registo já não existe.');
        }
        flash('success', 'Registo eliminado com sucesso.');
        redirectTo('customers/');
    } catch (PDOException $exception) {
        // ATENCAO: Let the foreign key enforce integrity, including concurrent changes.
        if ((int) ($exception->errorInfo[1] ?? 0) === 1451) {
            http_response_code(409);
            $error = 'Este cliente tem reservas associadas. Elimine ou reatribua essas reservas antes de eliminar o cliente.';
        } else {
            error_log($exception->getMessage());
            http_response_code(503);
            $error = 'Não foi possível eliminar o registo. Tente novamente.';
        }
    }
}
require __DIR__ . '/../../app/views/header.php';
?>
<?php if ($error !== ''): ?><div class="alert alert-danger" role="alert"><?= e($error) ?></div><?php endif; ?>
<div class="card riatour-edit"><div class="card-body">
<p>Confirma a eliminação de <strong><?= e($record['name']) ?></strong> (ID <?= e($id) ?>)?</p>
<form method="post" action="<?= e(url('customers/delete.php?id=' . $id)) ?>">
    <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
    <button class="btn btn-danger" type="submit">Confirmar eliminação</button>
    <a class="btn btn-outline-secondary" href="<?= e(url('customers/view.php?id=' . $id)) ?>">Cancelar</a>
</form>
</div></div>
<?php require __DIR__ . '/../../app/views/footer.php'; ?>
