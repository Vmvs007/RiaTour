<?php
require __DIR__ . '/../../app/bootstrap.php';
require __DIR__ . '/../../app/helpers/reservations.php';
$section = 'trips';
$title = 'Consultar passeio';
$id = requestId();
$record = fetchRecord("SELECT t.id, b.name AS boat, r.name AS route, t.trip_date, t.trip_time, a.status FROM trips t JOIN boats b ON b.id=t.boat_id JOIN routes r ON r.id=t.route_id JOIN trip_availability a ON a.trip_id=t.id WHERE t.id = :id", $id);
$columns = ['id' => 'ID', 'boat' => 'Barco', 'route' => 'Percurso', 'trip_date' => 'Data', 'trip_time' => 'Hora', 'status' => 'Estado'];
require __DIR__ . '/../../app/views/header.php';
?>
<dl class="row bg-white border rounded p-3"><?php foreach ($columns as $key => $label): ?><dt class="col-sm-3"><?= e($label) ?></dt><dd class="col-sm-9"><?= $key === 'status' ? statusBadge($record[$key]) : e($record[$key]) ?></dd><?php endforeach; ?></dl>
<a class="btn btn-primary" href="<?= e(url('trips/form.php?id=' . $id)) ?>">Editar</a>
<a class="btn btn-outline-danger" href="<?= e(url('trips/delete.php?id=' . $id)) ?>">Eliminar</a>
<a class="btn btn-outline-secondary" href="<?= e(url('trips/')) ?>">Voltar à lista</a>
<?php require __DIR__ . '/../../app/views/footer.php'; ?>
