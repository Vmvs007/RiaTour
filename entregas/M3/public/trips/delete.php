<?php
require __DIR__ . '/../../app/bootstrap.php';
require __DIR__ . '/../../app/helpers/reservations.php';
$section = 'trips';
$id = requestId();
$title = 'Eliminar passeio';
$record = fetchRecord('SELECT t.id, r.name, t.trip_date, t.trip_time FROM trips t JOIN routes r ON r.id = t.route_id WHERE t.id = :id', $id);
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireValidPost();
    $connection = null;
    try {
        $connection = beginReservationTransaction();
        lockTrip($connection, $id);
        // EXEMPLO: The foreign key prevents deleting trips with dependent bookings.
        $statement = $connection->prepare('DELETE FROM trips WHERE id = :id');
        $statement->execute(['id'=>$id]);
        $connection->commit();
        flash('success', 'Registo eliminado com sucesso.');
        redirectTo('trips/');
    } catch (DomainException | PDOException $exception) {
        if ($connection !== null && $connection->inTransaction()) { $connection->rollBack(); }
        http_response_code(409);
        $error = 'Não foi possível eliminar. Verifique se existem reservas associadas ou alterações simultâneas e tente novamente.';
    }
}
require __DIR__ . '/../../app/views/header.php';
?>
<?php if ($error): ?><p class="alert alert-danger" role="alert"><?= e($error) ?></p><?php endif; ?>
<div class="card card-body riatour-edit">
<p>Confirma a eliminação do passeio de <strong><?= e($record['name']) ?></strong> (ID <?= e($id) ?>), com partida em <?= e($record['trip_date']) ?> às <?= e($record['trip_time']) ?>?</p>
<form method="post">
<input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
<button type="submit" class="btn btn-danger">Confirmar eliminação</button>
<a class="btn btn-outline-secondary" href="<?= e(url('trips/view.php?id=' . $id)) ?>">Cancelar</a>
</form>
</div>
<?php require __DIR__ . '/../../app/views/footer.php'; ?>
