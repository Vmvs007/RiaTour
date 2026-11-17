<?php
require __DIR__ . '/../../app/bootstrap.php';
require __DIR__ . '/../../app/helpers/reservations.php';
$section = 'bookings';
$title = 'Consultar reserva';
$id = requestId();
$record = fetchRecord("SELECT bk.id, c.name AS customer, r.name AS route, b.name AS boat, t.trip_date, t.trip_time, bk.passengers, bk.booking_date, bk.status FROM bookings bk JOIN customers c ON c.id=bk.customer_id JOIN trips t ON t.id=bk.trip_id JOIN routes r ON r.id=t.route_id JOIN boats b ON b.id=t.boat_id WHERE bk.id = :id", $id);
$columns = ['id' => 'ID', 'customer' => 'Cliente', 'route' => 'Percurso', 'boat' => 'Barco', 'trip_date' => 'Data do passeio', 'trip_time' => 'Hora', 'passengers' => 'Passageiros', 'booking_date' => 'Data da reserva', 'status' => 'Estado'];
require __DIR__ . '/../../app/views/header.php';
?>
<dl class="row bg-white border rounded p-3"><?php foreach ($columns as $key => $label): ?><dt class="col-sm-3"><?= e($label) ?></dt><dd class="col-sm-9"><?= $key === 'status' ? statusBadge($record[$key]) : e($record[$key]) ?></dd><?php endforeach; ?></dl>
<a class="btn btn-primary" href="<?= e(url('bookings/form.php?id=' . $id)) ?>">Editar / Alterar estado</a>
<a class="btn btn-outline-danger" href="<?= e(url('bookings/delete.php?id=' . $id)) ?>">Eliminar</a>
<a class="btn btn-outline-secondary" href="<?= e(url('bookings/')) ?>">Voltar à lista</a>
<?php require __DIR__ . '/../../app/views/footer.php'; ?>
