<?php
require __DIR__ . '/../../app/bootstrap.php';
$section = 'boats';
$title = 'Consultar barco';
$id = requestId();
// EXEMPLO: Read one record using its primary key.
$record = fetchRecord('SELECT * FROM boats WHERE id = :id', $id);
$columns = ['id' => 'ID', 'name' => 'Nome', 'capacity' => 'Capacidade máxima', 'status' => 'Estado'];
require __DIR__ . '/../../app/views/header.php';
?>
<dl class="row bg-white border rounded p-3">
<?php foreach ($columns as $field => $label): ?>
    <dt class="col-sm-3"><?= e($label) ?></dt><dd class="col-sm-9 text-break" style="white-space: pre-wrap"><?= $field === 'status' ? statusBadge($record[$field]) : e($record[$field]) ?></dd>
<?php endforeach; ?>
</dl>
<a class="btn btn-primary" href="<?= e(url('boats/form.php?id=' . $id)) ?>">Editar</a>
<a class="btn btn-outline-danger" href="<?= e(url('boats/delete.php?id=' . $id)) ?>">Eliminar</a>
<a class="btn btn-outline-secondary" href="<?= e(url('boats/')) ?>">Voltar à lista</a>
<?php require __DIR__ . '/../../app/views/footer.php'; ?>
