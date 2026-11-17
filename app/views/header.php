<!doctype html>
<html lang="pt-PT">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title) ?> | RiaTour</title>
    <link rel="stylesheet" href="<?= e(url('assets/bootstrap/bootstrap.min.css')) ?>">
    <link rel="stylesheet" href="<?= e(url('assets/riatour.css')) ?>">
</head>
<body class="bg-light d-flex flex-column min-vh-100">
<a class="visually-hidden-focusable p-2" href="#conteudo">Saltar para o conteúdo</a>
<nav class="navbar navbar-expand-lg navbar-dark riatour-nav" aria-label="Navegação principal">
    <div class="container">
        <a class="navbar-brand" href="<?= e(url()) ?>">RiaTour</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navigation"
                aria-controls="navigation" aria-expanded="false" aria-label="Abrir menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navigation">
            <div class="navbar-nav gap-1">
                <a class="nav-link <?= ($section ?? '') === 'home' ? 'active' : '' ?>"
                   <?= ($section ?? '') === 'home' ? 'aria-current="page"' : '' ?>
                   href="<?= e(url()) ?>">Início</a>
                <?php foreach ($navigation as $key => $label): ?>
                    <a class="nav-link <?= ($section ?? '') === $key ? 'active' : '' ?>"
                       <?= ($section ?? '') === $key ? 'aria-current="page"' : '' ?>
                       href="<?= e(url($key . '/')) ?>"><?= e($label) ?></a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</nav>
<main id="conteudo" class="container py-4 flex-grow-1" tabindex="-1">
    <h1 class="mb-4"><?= e($title) ?></h1>
    <?php foreach ($_SESSION['messages'] ?? [] as $message): ?>
        <div class="alert alert-<?= e($message['type']) ?>" role="<?= $message['type'] === 'danger' ? 'alert' : 'status' ?>"><?= e($message['text']) ?></div>
    <?php endforeach; unset($_SESSION['messages']); ?>
