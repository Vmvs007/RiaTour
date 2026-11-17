<?php
// EXEMPLO: Share table rendering while each page defines its own explicit query.
try {
    $rows = fetchAll($sql);
} catch (PDOException $exception) {
    error_log($exception->getMessage());
    http_response_code(503);
    $rows = null;
    flash('error', 'Não foi possível consultar os dados. Verifique o servidor e a configuração local.');
}
require __DIR__ . '/header.php';
?>
<?php if ($rows === []): ?>
    <p class="alert alert-info">Ainda não existem registos nesta área.</p>
<?php elseif ($rows !== null): ?>
    <div class="table-responsive bg-white border rounded">
        <table class="table table-striped mb-0">
            <caption class="px-3">Registos de <?= e(mb_strtolower($title)) ?></caption>
            <thead><tr>
                <?php foreach ($columns as $label): ?><th scope="col"><?= e($label) ?></th><?php endforeach; ?>
            </tr></thead>
            <tbody>
                <?php foreach ($rows as $row): ?><tr>
                    <?php foreach ($columns as $field => $label): ?><td><?= e($row[$field]) ?></td><?php endforeach; ?>
                </tr><?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
<?php require __DIR__ . '/footer.php'; ?>
