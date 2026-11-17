<?php
require __DIR__ . '/../../app/bootstrap.php';
require __DIR__ . '/../../app/helpers/reservations.php';
$section = 'trips';
$title = 'Passeios';
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

$sql = "SELECT t.id, b.name AS boat, r.name AS route, t.trip_date, t.trip_time, a.status FROM trips t JOIN boats b ON b.id=t.boat_id JOIN routes r ON r.id=t.route_id JOIN trip_availability a ON a.trip_id=t.id WHERE 1=1";
$parameters = [];
// EXEMPLO: Build optional filters using bound values.
$trip_date = inputText($_GET, 'trip_date');
if ($trip_date !== '') {
    $sql .= ' AND t.trip_date = :trip_date';
    $parameters['trip_date'] = $trip_date;
}
$boat_id = inputText($_GET, 'boat_id');
if ($boat_id !== '') {
    $sql .= ' AND t.boat_id = :boat_id';
    $parameters['boat_id'] = $boat_id;
}
$route_id = inputText($_GET, 'route_id');
if ($route_id !== '') {
    $sql .= ' AND t.route_id = :route_id';
    $parameters['route_id'] = $route_id;
}
try {
    $rows = fetchAll($sql . ' ORDER BY t.id', $parameters);
} catch (PDOException $exception) {
    error_log($exception->getMessage());
    pageError(503, 'Não foi possível consultar os registos.');
}
$columns = ['id' => 'ID', 'boat' => 'Barco', 'route' => 'Percurso', 'trip_date' => 'Data', 'trip_time' => 'Hora', 'status' => 'Estado'];
require __DIR__ . '/../../app/views/header.php';
?>
<a class="btn btn-primary mb-3" href="<?= e(url('trips/form.php')) ?>">Criar passeio</a>
<form method="get" class="riatour-filters bg-white border rounded p-3 mb-4" role="search" aria-label="Filtrar registos">
<div><label class="form-label" for="trip_date">Data</label>
<input class="form-control" id="trip_date" name="trip_date" type="date"   value="<?= e($trip_date) ?>" ></div>
<div><label class="form-label" for="boat_id">Barco</label>
<select class="form-select" id="boat_id" name="boat_id" ><option value="">Todos</option><?php foreach ($options['boat_id'] as $option): ?><option value="<?= e($option['id']) ?>" <?= (string) $boat_id === (string) $option['id'] ? 'selected' : '' ?>><?= e($option['label']) ?></option><?php endforeach; ?></select></div>
<div><label class="form-label" for="route_id">Percurso</label>
<select class="form-select" id="route_id" name="route_id" ><option value="">Todos</option><?php foreach ($options['route_id'] as $option): ?><option value="<?= e($option['id']) ?>" <?= (string) $route_id === (string) $option['id'] ? 'selected' : '' ?>><?= e($option['label']) ?></option><?php endforeach; ?></select></div>
<div><button type="submit" class="btn btn-primary">Pesquisar</button> <a class="btn btn-outline-secondary" href="<?= e(url('trips/')) ?>">Limpar</a></div>
</form>
<?php if (!$rows): ?><p class="alert alert-info">Não foram encontrados registos.</p>
<?php else: require __DIR__ . '/../../app/views/crud-table.php'; endif; ?>
<?php require __DIR__ . '/../../app/views/footer.php'; ?>
