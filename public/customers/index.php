<?php
require __DIR__ . '/../../app/bootstrap.php';
$section = 'customers';
$title = 'Clientes';
// EXEMPLO: LIKE searches partial text; PDO binds the supplied values.
// ADAPTAR: Choose the searchable columns for the new business.
$query = inputText($_GET, 'q');
// ATENCAO: Native PDO prepares require a distinct placeholder for each occurrence.
$sql = 'SELECT id, name, email, phone FROM customers WHERE name LIKE :name OR email LIKE :email ORDER BY name, id';
$parameters = ['name' => '%' . $query . '%', 'email' => '%' . $query . '%'];
try {
    $rows = fetchAll($sql, $parameters);
} catch (PDOException $exception) {
    error_log($exception->getMessage());
    pageError(503, 'Não foi possível consultar os dados. Verifique a ligação à base de dados.');
}
$columns = ['id' => 'ID', 'name' => 'Nome', 'email' => 'Email', 'phone' => 'Telefone'];
require __DIR__ . '/../../app/views/header.php';
?>
<a class="btn btn-primary mb-3" href="<?= e(url('customers/form.php')) ?>">Criar cliente</a>
<form method="get" class="row g-3 align-items-end card-body bg-white border rounded p-3 mb-4" role="search" aria-label="Filtrar registos">
<div class="col-md-8"><label for="q" class="form-label">Nome ou email</label><input id="q" name="q" class="form-control" value="<?= e($query) ?>"></div>
<div class="col-md-4"><button class="btn btn-primary" type="submit">Pesquisar</button> <a class="btn btn-outline-secondary" href="<?= e(url('customers/')) ?>">Limpar</a></div>
</form>
<?php if ($rows === []): ?>
<p class="alert alert-info">Não foram encontrados registos.</p>
<?php else: require __DIR__ . '/../../app/views/crud-table.php'; endif; ?>
<?php require __DIR__ . '/../../app/views/footer.php'; ?>
