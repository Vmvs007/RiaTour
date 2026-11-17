# Cábula de interfaces — RiaTour

A navegação, os títulos e as mensagens vêm de `app/views/header.php`. A área ativa tem destaque visual e `aria-current="page"`. As páginas usam Bootstrap 5 local e os pequenos ajustes de `public/assets/riatour.css`.

## Formulário Bootstrap

```php
<label for="capacity" class="form-label">Capacidade máxima <span aria-hidden="true">*</span></label>
<input id="capacity" name="capacity" type="number" class="form-control"
       min="1" required value="<?= e($values['capacity']) ?>"
       <?= fieldAttributes($errors, 'capacity') ?>>
<?php fieldFeedback($errors, 'capacity'); ?>
```

Indicar o significado do asterisco no formulário. O HTML ajuda durante o preenchimento e a validação PHP aplica as regras na gravação.

## Tabela responsiva

```html
<div class="table-responsive" tabindex="0" role="region" aria-label="Tabela de barcos">
    <table class="table table-striped table-hover align-middle">
        <thead class="table-light"><tr><th scope="col">Barco</th></tr></thead>
        <tbody><tr><td>Ria Serena</td></tr></tbody>
    </table>
</div>
```

A região pode receber foco para permitir deslocamento horizontal com o teclado. O `JOIN` obtém nomes legíveis para as relações, como o barco e o percurso numa reserva.

## Alert e badge

```html
<div class="alert alert-success" role="status">Registo guardado com sucesso.</div>
<div class="alert alert-danger" role="alert">Não foi possível guardar o registo.</div>
<span class="badge text-bg-warning">Pendente</span>
```

Usar `flash()` para mostrar mensagens após redirecionar. A função `statusBadge()` associa estilos aos estados e mantém o rótulo visível.

## Dropdown

```php
<label for="boat_id" class="form-label">Barco</label>
<select name="boat_id" id="boat_id" class="form-select">
    <option value="">Todos</option>
    <?php foreach ($boats as $boat): ?>
        <option value="<?= e($boat['id']) ?>" <?= (string) $boatId === (string) $boat['id'] ? 'selected' : '' ?>>
            <?= e($boat['name']) ?>
        </option>
    <?php endforeach; ?>
</select>
```

Nas pesquisas a opção vazia abrange todos os registos. Num formulário de edição usar `required` e validar o ID no servidor.

## Pesquisa GET

```php
$name = inputText($_GET, 'name');
$rows = fetchAll('SELECT id, name FROM routes WHERE name LIKE :name',
    ['name' => '%' . $name . '%']);
```

O formulário usa `method="get"`. A pesquisa mantém os valores selecionados no endereço e o botão Limpar regressa à lista completa. `fetchAll()` prepara a consulta e vincula os parâmetros.

| Área | Campos de pesquisa |
| --- | --- |
| Barcos | Nome ou estado |
| Percursos | Nome |
| Passeios | Data, barco ou percurso |
| Clientes | Nome ou email |
| Reservas | Cliente, passeio ou estado |

## Confirmar eliminação

```php
<p>Confirma a eliminação de <strong><?= e($record['name']) ?></strong>?</p>
<form method="post">
    <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
    <button type="submit" class="btn btn-danger">Confirmar eliminação</button>
    <a class="btn btn-outline-secondary" href="<?= e(url('boats/')) ?>">Cancelar</a>
</form>
```

O botão Eliminar da tabela abre a página de confirmação por GET. O POST é validado por `requireValidPost()` antes de executar o DELETE. As confirmações de passeios e reservas mostram o nome associado, a data e a hora da partida.
