<?php
require __DIR__ . '/../../app/bootstrap.php';
$section = 'routes';
$title = 'Percursos';
// EXEMPLO: LIKE searches partial text; PDO binds the supplied values.
// ADAPTAR: Choose the searchable columns for the new business.
$name = inputText($_GET, 'name');
$sql = 'SELECT id, name, description, duration_minutes, price_per_passenger FROM routes WHERE name LIKE :name ORDER BY name, id';
$parameters = ['name' => '%' . $name . '%'];
try {
    $rows = fetchAll($sql, $parameters);
} catch (PDOException $exception) {
    error_log($exception->getMessage());
    pageError(503, 'Não foi possível consultar os dados. Verifique a ligação à base de dados.');
}
$columns = ['id' => 'ID', 'name' => 'Nome', 'description' => 'Descrição', 'duration_minutes' => 'Duração (min)', 'price_per_passenger' => 'Preço por passageiro (€)'];
require __DIR__ . '/../../app/views/header.php';
?>
<a class="btn btn-primary mb-3" href="<?= e(url('routes/form.php')) ?>">Criar percurso</a>
<form method="get" class="row g-3 align-items-end card-body bg-white border rounded p-3 mb-4" role="search" aria-label="Filtrar registos">
<div class="col-md-8"><label for="name" class="form-label">Nome</label><input id="name" name="name" class="form-control" value="<?= e($name) ?>"></div>
<div class="col-md-4"><button class="btn btn-primary" type="submit">Pesquisar</button> <a class="btn btn-outline-secondary" href="<?= e(url('routes/')) ?>">Limpar</a></div>
</form>
<?php if ($rows === []): ?>
<p class="alert alert-info">Não foram encontrados registos.</p>
<?php else: require __DIR__ . '/../../app/views/crud-table.php'; endif; ?>
<?php require __DIR__ . '/../../app/views/footer.php'; ?>
