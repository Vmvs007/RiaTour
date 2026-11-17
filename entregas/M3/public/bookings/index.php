<?php
require __DIR__ . '/../../app/bootstrap.php';
require __DIR__ . '/../../app/helpers/reservations.php';
$section = 'bookings';
$title = 'Reservas';
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

$sql = "SELECT bk.id, c.name AS customer, r.name AS route, b.name AS boat, t.trip_date, t.trip_time, bk.passengers, bk.booking_date, bk.status FROM bookings bk JOIN customers c ON c.id=bk.customer_id JOIN trips t ON t.id=bk.trip_id JOIN routes r ON r.id=t.route_id JOIN boats b ON b.id=t.boat_id WHERE 1=1";
$parameters = [];
// EXEMPLO: Build optional filters using bound values.
$customer = inputText($_GET, 'customer');
if ($customer !== '') {
    $sql .= ' AND c.name LIKE :customer';
    $parameters['customer'] = '%' . $customer . '%';
}
$trip_id = inputText($_GET, 'trip_id');
if ($trip_id !== '') {
    $sql .= ' AND bk.trip_id = :trip_id';
    $parameters['trip_id'] = $trip_id;
}
$status = inputText($_GET, 'status');
if ($status !== '') {
    $sql .= ' AND bk.status = :status';
    $parameters['status'] = $status;
}
try {
    $rows = fetchAll($sql . ' ORDER BY bk.id', $parameters);
} catch (PDOException $exception) {
    error_log($exception->getMessage());
    pageError(503, 'Não foi possível consultar os registos.');
}
$columns = ['id' => 'ID', 'customer' => 'Cliente', 'route' => 'Percurso', 'boat' => 'Barco', 'trip_date' => 'Data do passeio', 'trip_time' => 'Hora', 'passengers' => 'Passageiros', 'booking_date' => 'Data da reserva', 'status' => 'Estado'];
require __DIR__ . '/../../app/views/header.php';
?>
<a class="btn btn-primary mb-3" href="<?= e(url('bookings/form.php')) ?>">Criar reserva</a>
<form method="get" class="riatour-filters bg-white border rounded p-3 mb-4" role="search" aria-label="Filtrar registos">
<div><label class="form-label" for="customer">Nome do cliente</label>
<input class="form-control" id="customer" name="customer" type="text"   value="<?= e($customer) ?>" ></div>
<div><label class="form-label" for="trip_id">Passeio</label>
<select class="form-select" id="trip_id" name="trip_id" ><option value="">Todos</option><?php foreach ($options['trip_id'] as $option): ?><option value="<?= e($option['id']) ?>" <?= (string) $trip_id === (string) $option['id'] ? 'selected' : '' ?>><?= e($option['label']) ?></option><?php endforeach; ?></select></div>
<div><label class="form-label" for="status">Estado</label>
<select class="form-select" id="status" name="status" ><option value="">Todos</option><?php foreach ($options['status'] as $option): ?><option value="<?= e($option['id']) ?>" <?= (string) $status === (string) $option['id'] ? 'selected' : '' ?>><?= e($option['label']) ?></option><?php endforeach; ?></select></div>
<div><button type="submit" class="btn btn-primary">Pesquisar</button> <a class="btn btn-outline-secondary" href="<?= e(url('bookings/')) ?>">Limpar</a></div>
</form>
<?php if (!$rows): ?><p class="alert alert-info">Não foram encontrados registos.</p>
<?php else: require __DIR__ . '/../../app/views/crud-table.php'; endif; ?>
<?php require __DIR__ . '/../../app/views/footer.php'; ?>
