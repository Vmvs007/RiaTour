<div class="table-responsive bg-white border rounded" tabindex="0" role="region" aria-label="Tabela de <?= e(mb_strtolower($title)) ?>">
<table class="table table-striped table-hover align-middle mb-0 riatour-table">
    <caption class="px-3">Registos de <?= e(mb_strtolower($title)) ?></caption>
    <thead class="table-light"><tr><?php foreach ($columns as $label): ?><th scope="col"><?= e($label) ?></th><?php endforeach; ?><th scope="col">Ações</th></tr></thead>
    <tbody>
    <?php foreach ($rows as $row): ?>
        <tr><?php foreach ($columns as $field => $label): ?>
            <td><?= $field === 'status' ? statusBadge($row[$field]) : e($row[$field]) ?></td>
        <?php endforeach; ?>
            <td><div class="d-flex flex-wrap gap-2">
                <a class="btn btn-sm btn-outline-primary" href="<?= e(url($section . '/view.php?id=' . $row['id'])) ?>">Ver</a>
                <a class="btn btn-sm btn-outline-secondary" href="<?= e(url($section . '/form.php?id=' . $row['id'])) ?>">Editar</a>
                <a class="btn btn-sm btn-outline-danger" href="<?= e(url($section . '/delete.php?id=' . $row['id'])) ?>">Eliminar</a>
            </div></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
