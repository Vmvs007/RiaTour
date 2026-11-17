<?php
require __DIR__ . '/../app/bootstrap.php';
$section = 'home';
$title = 'Gestão de passeios';
try {
    $boats = fetchAll('SELECT name FROM boats WHERE status = :status ORDER BY name', ['status' => 'ativo']);
} catch (PDOException $exception) {
    error_log($exception->getMessage());
    http_response_code(503);
    $boats = null;
    flash('error', 'Não foi possível consultar os dados. Verifique o servidor e a configuração local.');
}
require __DIR__ . '/../app/views/header.php';
?>
<div class="row g-3 mb-4">
    <?php foreach ($navigation as $key => $label): ?>
        <div class="col-sm-6 col-lg-4">
            <a class="card riatour-card h-100 text-decoration-none" href="<?= e(url($key . '/')) ?>">
                <div class="card-body"><h2 class="h5 mb-0"><?= e($label) ?></h2></div>
            </a>
        </div>
    <?php endforeach; ?>
</div>
<?php if ($boats !== null): ?>
    <section class="card"><div class="card-body">
        <h2 class="h5">Barcos ativos</h2>
        <?php if (!$boats): ?><p class="mb-0">Ainda não existem barcos ativos.</p>
        <?php else: ?><ul class="mb-0">
            <?php foreach ($boats as $boat): ?><li><?= e($boat['name']) ?></li><?php endforeach; ?>
        </ul><?php endif; ?>
    </div></section>
<?php endif; ?>
<?php require __DIR__ . '/../app/views/footer.php'; ?>
