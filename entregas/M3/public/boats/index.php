<?php
require __DIR__ . '/../../app/bootstrap.php';
$section = 'boats';
$title = 'Barcos';
// EXEMPLO: LIKE searches partial text; PDO binds the supplied values.
// ADAPTAR: Choose the searchable columns for the new business.
$name = inputText($_GET, 'name');
$status = inputText($_GET, 'status');
$sql = 'SELECT id, name, capacity, status FROM boats WHERE name LIKE :name';
$parameters = ['name' => '%' . $name . '%'];
if ($status !== '') {
    if (!in_array($status, ['ativo', 'inativo'], true)) {
        pageError(400, 'Estado de pesquisa inválido.');
    }
    $sql .= ' AND status = :status';
    $parameters['status'] = $status;
}
$sql .= ' ORDER BY name, id';
try {
    $rows = fetchAll($sql, $parameters);
} catch (PDOException $exception) {
    error_log($exception->getMessage());
    pageError(503, 'Não foi possível consultar os dados. Verifique a ligação à base de dados.');
}
$columns = ['id' => 'ID', 'name' => 'Nome', 'capacity' => 'Capacidade máxima', 'status' => 'Estado'];
require __DIR__ . '/../../app/views/header.php';
?>
<a class="btn btn-primary mb-3" href="<?= e(url('boats/form.php')) ?>">Criar barco</a>
<form method="get" class="row g-3 align-items-end card-body bg-white border rounded p-3 mb-4" role="search" aria-label="Filtrar registos">
<div class="col-md-5"><label for="name" class="form-label">Nome</label><input id="name" name="name" class="form-control" value="<?= e($name) ?>"></div>
<div class="col-md-3"><label for="status" class="form-label">Estado</label><select id="status" name="status" class="form-select"><option value="">Todos</option><?php foreach (['ativo' => 'Ativo', 'inativo' => 'Inativo'] as $value => $label): ?><option value="<?= e($value) ?>" <?= $status === $value ? 'selected' : '' ?>><?= e($label) ?></option><?php endforeach; ?></select></div>
<div class="col-md-4"><button class="btn btn-primary" type="submit">Pesquisar</button> <a class="btn btn-outline-secondary" href="<?= e(url('boats/')) ?>">Limpar</a></div>
</form>
<?php if ($rows === []): ?>
<p class="alert alert-info">Não foram encontrados registos.</p>
<?php else: require __DIR__ . '/../../app/views/crud-table.php'; endif; ?>
<?php require __DIR__ . '/../../app/views/footer.php'; ?>
