<?php
require __DIR__ . '/../../app/bootstrap.php';
$section = 'routes';
$title = 'Consultar percurso';
$id = requestId();
// EXEMPLO: Read one record using its primary key.
$record = fetchRecord('SELECT * FROM routes WHERE id = :id', $id);
$columns = ['id' => 'ID', 'name' => 'Nome', 'description' => 'Descrição', 'duration_minutes' => 'Duração (min)', 'price_per_passenger' => 'Preço por passageiro (€)'];
require __DIR__ . '/../../app/views/header.php';
?>
<dl class="row bg-white border rounded p-3">
<?php foreach ($columns as $field => $label): ?>
    <dt class="col-sm-3"><?= e($label) ?></dt><dd class="col-sm-9 text-break" style="white-space: pre-wrap"><?= $field === 'status' ? statusBadge($record[$field]) : e($record[$field]) ?></dd>
<?php endforeach; ?>
</dl>
<a class="btn btn-primary" href="<?= e(url('routes/form.php?id=' . $id)) ?>">Editar</a>
<a class="btn btn-outline-danger" href="<?= e(url('routes/delete.php?id=' . $id)) ?>">Eliminar</a>
<a class="btn btn-outline-secondary" href="<?= e(url('routes/')) ?>">Voltar à lista</a>
<?php require __DIR__ . '/../../app/views/footer.php'; ?>
