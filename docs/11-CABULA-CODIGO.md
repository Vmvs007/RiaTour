# Cábula de código — WorldSkills

Usa uma cópia de treino da RiaTour já importada. Em SQL, seleciona a base RiaTour. Os exemplos 01 a 05 usam tabelas `practice_*` e a mesma sessão SQL, e os seguintes usam o esquema do projeto. Para os exemplos PHP autónomos, copia um de cada vez para `public/exemplo.php` e abre-o pelo servidor local. Os exemplos 17, 18 e 20 indicam o ponto em que encaixam num formulário ou controlador existente. Para instalar a aplicação, segue o [README](../README.md).

## SQL

### 01. CREATE TABLE

Cria uma tabela de treino com restrições.

```sql
CREATE TABLE practice_boats (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    capacity SMALLINT UNSIGNED NOT NULL,
    CHECK (CHAR_LENGTH(TRIM(name)) > 0),
    CHECK (capacity > 0)
) ENGINE=InnoDB;
```

- **Utilizar** — Executa uma vez numa base de treino.
- **Adaptar** — Troca os campos e os limites pelos da nova entidade.
- **Erro comum** — Usar apenas NOT NULL e admitir texto vazio.

### 02. PRIMARY KEY e FOREIGN KEY

Liga cada horário de treino a um barco existente.

```sql
CREATE TABLE practice_trips (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    boat_id INT UNSIGNED NOT NULL,
    trip_date DATE NOT NULL,
    FOREIGN KEY (boat_id) REFERENCES practice_boats (id)
        ON DELETE RESTRICT
) ENGINE=InnoDB;
```

- **Utilizar** — Executa depois do exemplo 01.
- **Adaptar** — Substitui o barco pelo recurso associado.
- **Erro comum** — Usar um tipo diferente na chave estrangeira, incluindo o sinal.

### 03. INSERT

Cria um barco e um horário associado.

```sql
START TRANSACTION;
INSERT INTO practice_boats (name, capacity)
VALUES ('Ria Serena', 10);
SET @boat_id = LAST_INSERT_ID();
INSERT INTO practice_trips (boat_id, trip_date)
VALUES (@boat_id, CURRENT_DATE + INTERVAL 1 DAY);
COMMIT;
```

- **Utilizar** — Executa após 01 e 02 e mantém a sessão SQL aberta.
- **Adaptar** — Insere primeiro o registo que será referenciado.
- **Erro comum** — Presumir que o novo ID é sempre 1.

### 04. UPDATE

Altera a capacidade de um barco de treino.

```sql
UPDATE practice_boats
SET capacity = 12
WHERE id = @boat_id;

SELECT id, capacity FROM practice_boats WHERE id = @boat_id;
```

- **Utilizar** — Executa na sessão do exemplo 03.
- **Adaptar** — Troca a coluna e aplica a regra de negócio antes de gravar.
- **Erro comum** — Esquecer o WHERE e alterar todas as linhas.

### 05. DELETE

Elimina o horário de treino e depois o barco.

```sql
START TRANSACTION;
DELETE FROM practice_trips
WHERE boat_id = @boat_id;
DELETE FROM practice_boats
WHERE id = @boat_id;
ROLLBACK;
```

- **Utilizar** — Executa depois de 03. O rollback restitui os dados do exercício.
- **Adaptar** — Usa o ID do registo confirmado e decide como tratar dependências.
- **Erro comum** — Eliminar primeiro o registo que ainda tem referências.

### 06. SELECT com WHERE

Lista os barcos ativos com capacidade mínima.

```sql
SELECT id, name, capacity
FROM boats
WHERE status = 'ativo'
  AND capacity >= 10
ORDER BY name, id;
```

- **Utilizar** — Executa na base RiaTour com os dados de exemplo.
- **Adaptar** — Troca o estado e o limiar pedidos pelo utilizador.
- **Erro comum** — Confundir AND com OR.

### 07. INNER JOIN

Mostra o nome do barco e do percurso em cada passeio.

```sql
SELECT t.id, t.trip_date, b.name AS boat, r.name AS route
FROM trips AS t
INNER JOIN boats AS b ON b.id = t.boat_id
INNER JOIN routes AS r ON r.id = t.route_id
ORDER BY t.trip_date, t.id;
```

- **Utilizar** — Executa na base RiaTour.
- **Adaptar** — Liga a chave estrangeira à chave primária da entidade associada.
- **Erro comum** — Cruzar tabelas sem uma condição ON correta.

### 08. GROUP BY e SUM

Soma passageiros pendentes e confirmados por passeio, incluindo totais a zero.

```sql
SELECT t.id, COALESCE(SUM(b.passengers), 0) AS occupied
FROM trips AS t
LEFT JOIN bookings AS b
    ON b.trip_id = t.id
   AND b.status IN ('pendente', 'confirmada')
GROUP BY t.id
ORDER BY t.id;
```

- **Utilizar** — Confirma que um passeio sem reservas ativas devolve zero.
- **Adaptar** — Define quais os estados que consomem capacidade.
- **Erro comum** — Pôr o filtro de estado no WHERE e perder linhas sem reservas.

## PHP

### 09. Ligação PDO

Abre uma ligação MySQL em UTF-8 e confirma que responde.

```php
<?php
$config = require __DIR__ . '/../app/config/local.php';
$dsn = "mysql:host={$config['host']};port={$config['port']};"
     . "dbname={$config['database']};charset=utf8mb4";
$pdo = new PDO($dsn, $config['username'], $config['password'], [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_EMULATE_PREPARES => false,
]);
echo $pdo->query('SELECT 1')->fetchColumn();
```

- **Utilizar** — Abre o ficheiro e espera 1. Na aplicação usa database().
- **Adaptar** — Ajusta a configuração local da ligação.
- **Erro comum** — Mostrar a palavra-passe ou detalhes técnicos ao utilizador.

### 10. SELECT com fetchAll()

Lê e apresenta todos os percursos.

```php
<?php
require __DIR__ . '/../app/bootstrap.php';
$rows = fetchAll('SELECT id, name FROM routes ORDER BY name');
echo '<ul>';
foreach ($rows as $row) {
    echo '<li>' . e($row['name']) . '</li>';
}
echo '</ul>';
```

- **Utilizar** — Abre o ficheiro após importar os exemplos RiaTour.
- **Adaptar** — Troca as colunas e o campo de ordenação.
- **Erro comum** — Apresentar dados sem escape HTML.

### 11. INSERT com POST

Cria um barco de treino com nome validado e capacidade fixa.

```php
<?php
require __DIR__ . '/../app/bootstrap.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireValidPost();
    $name = inputText($_POST, 'name');
    if ($name === '' || mb_strlen($name) > 100) {
        pageError(422, 'Introduz um nome até 100 caracteres.');
    }
    $statement = database()->prepare(
        'INSERT INTO boats (name, capacity, status) VALUES (:name, 10, :status)'
    );
    $statement->execute(['name' => $name, 'status' => 'ativo']);
    flash('success', 'Barco criado.');
    redirectTo('boats/');
}
?>
<form method="post">
    <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
    <label for="name">Nome</label>
    <input id="name" name="name" required maxlength="100">
    <button type="submit">Criar</button>
</form>
```

- **Utilizar** — Abre a página, envia o formulário e aponta o ID para 12 e 13.
- **Adaptar** — Acrescenta os campos do negócio e valida-os no servidor.
- **Erro comum** — Confiar apenas no required do navegador.

### 12. UPDATE por ID

Edita apenas o nome do barco indicado.

```php
<?php
require __DIR__ . '/../app/bootstrap.php';
$id = requestId();
$boat = fetchRecord('SELECT id, name FROM boats WHERE id = :id', $id);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireValidPost();
    $name = inputText($_POST, 'name');
    if ($name === '' || mb_strlen($name) > 100) {
        pageError(422, 'Introduz um nome até 100 caracteres.');
    }
    $sql = 'UPDATE boats SET name = :name WHERE id = :id';
    database()->prepare($sql)->execute(['name' => $name, 'id' => $id]);
    flash('success', 'Nome guardado.');
    redirectTo('boats/view.php?id=' . $id);
}
?>
<form method="post">
    <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
    <label for="name">Nome</label>
    <input id="name" name="name" required maxlength="100" value="<?= e($boat['name']) ?>">
    <button type="submit">Guardar</button>
</form>
```

- **Utilizar** — Abre com ?id= seguido do ID criado em 11.
- **Adaptar** — Atualiza as colunas permitidas e revê as regras que dependem delas.
- **Erro comum** — Inserir o ID recebido diretamente na consulta.

### 13. DELETE por ID

Pede confirmação e elimina o barco indicado por POST.

```php
<?php
require __DIR__ . '/../app/bootstrap.php';
$id = requestId();
$boat = fetchRecord('SELECT name FROM boats WHERE id = :id', $id);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireValidPost();
    try {
        database()->prepare('DELETE FROM boats WHERE id = :id')->execute(['id' => $id]);
    } catch (PDOException $error) {
        error_log($error->getMessage());
        if ((int) ($error->errorInfo[1] ?? 0) === 1451) {
            pageError(409, 'O barco tem passeios associados.');
        }
        pageError(503, 'Não foi possível eliminar o barco.');
    }
    flash('success', 'Barco eliminado.');
    redirectTo('boats/');
}
?>
<form method="post">
    <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
    <p>Eliminar <?= e($boat['name']) ?>?</p>
    <button type="submit">Confirmar eliminação</button>
    <a href="<?= e(url('boats/')) ?>">Cancelar</a>
</form>
```

- **Utilizar** — Abre com o ID de treino. Experimenta também um barco com passeios associados.
- **Adaptar** — Ajusta a tabela e a mensagem de dependência.
- **Erro comum** — Executar a eliminação ao abrir o endereço por GET.

### 14. Pesquisa com LIKE

Pesquisa percursos por parte do nome e mantém o filtro.

```php
<?php
require __DIR__ . '/../app/bootstrap.php';
$name = inputText($_GET, 'name');
$rows = fetchAll('SELECT name FROM routes WHERE name LIKE :name ORDER BY name',
    ['name' => '%' . $name . '%']);
?>
<form method="get">
    <label for="name">Nome do percurso</label>
    <input id="name" name="name" value="<?= e($name) ?>">
    <button type="submit">Pesquisar</button>
</form>
<ul>
<?php foreach ($rows as $row): ?><li><?= e($row['name']) ?></li><?php endforeach; ?>
</ul>
```

- **Utilizar** — Pesquisa «Salinas» ou limpa o campo para ver todos.
- **Adaptar** — Altera as colunas pesquisáveis.
- **Erro comum** — Escrever LIKE '%:name%' em vez de passar os % no parâmetro.

### 15. Dropdown preenchido por SQL

Mostra nomes de barcos e envia o ID escolhido.

```php
<?php
require __DIR__ . '/../app/bootstrap.php';
$boats = fetchAll('SELECT id, name FROM boats ORDER BY name, id');
$boatId = inputText($_GET, 'boat_id');
?>
<form method="get">
    <label for="boat_id">Barco</label>
    <select id="boat_id" name="boat_id" required>
        <option value="">Selecionar</option>
        <?php foreach ($boats as $boat): ?>
            <option value="<?= e($boat['id']) ?>" <?= $boatId === (string) $boat['id'] ? 'selected' : '' ?>>
                <?= e($boat['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Selecionar</button>
</form>
```

- **Utilizar** — Muda a opção e confirma que permanece selecionada. Num POST de gravação confirma que o ID existe.
- **Adaptar** — Troca os nomes e a tabela pela entidade associada.
- **Erro comum** — Guardar o nome como referência e perder a ligação quando o nome mudar.

### 16. Validação de formulário

Valida uma capacidade inteira e mostra o erro junto ao campo.

```php
<?php
require __DIR__ . '/../app/bootstrap.php';
$value = '';
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireValidPost();
    $value = inputText($_POST, 'capacity');
    if (filter_var($value, FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 1, 'max_range' => 65535]]) === false) {
        $errors['capacity'] = 'Indica uma capacidade entre 1 e 65535.';
        http_response_code(422);
    } else {
        echo '<p>Capacidade válida.</p>';
    }
}
?>
<form method="post">
    <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
    <label for="capacity">Capacidade</label>
    <input id="capacity" name="capacity" type="number" required min="1" max="65535"
           value="<?= e($value) ?>" <?= fieldAttributes($errors, 'capacity') ?>>
    <?php fieldFeedback($errors, 'capacity', $value); ?>
    <button type="submit">Validar</button>
</form>
```

- **Utilizar** — Testa 10 e confirma que um POST direto com 0 recebe erro.
- **Adaptar** — Relaciona os limites com o tipo SQL e a regra local.
- **Erro comum** — Converter para inteiro antes de validar e aceitar uma fração truncada.

### 17. Transação SQL

Guarda duas inserções em conjunto ou reverte ambas.

```php
<?php
require __DIR__ . '/../app/bootstrap.php';
requireValidPost();
$pdo = database();
$pdo->beginTransaction();
try {
    $sql = 'INSERT INTO boats (name, capacity) VALUES (:name, :capacity)';
    $statement = $pdo->prepare($sql);
    $statement->execute(['name' => 'Treino A', 'capacity' => 10]);
    $statement->execute(['name' => 'Treino B', 'capacity' => 12]);
    $pdo->commit();
} catch (Throwable $error) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log($error->getMessage());
    pageError(503, 'Não foi possível guardar os dois barcos.');
}
flash('success', 'Dois barcos criados.');
redirectTo('boats/');
```

- **Utilizar** — Usa como destino de um formulário POST com o token do exemplo 11. Troca 12 por 0 para observar o rollback.
- **Adaptar** — Agrupa as gravações que têm de acontecer juntas.
- **Erro comum** — Confirmar a primeira operação antes de a segunda terminar.

### 18. Validação de capacidade

Calcula os lugares livres mantendo o bloqueio até a reserva ser gravada.

```php
<?php
// Use inside the booking POST handler after validating all submitted fields.
$connection = beginReservationTransaction();
try {
    $trip = lockTrip($connection, $tripId);
    $capacity = boatCapacity($connection, (int) $trip['boat_id']);
    $reserved = occupiedSeats($connection, $tripId);
    $available = $capacity - $reserved;
    if ($trip['is_cancelled'] || $passengers > $available) {
        throw new DomainException('Passeio cancelado ou sem lugares suficientes.');
    }
    $statement = $connection->prepare(
        "INSERT INTO bookings (customer_id, trip_id, passengers, booking_date, status)
         VALUES (:customer, :trip, :passengers, :date, 'pendente')"
    );
    $statement->execute([
        'customer' => $customerId, 'trip' => $tripId,
        'passengers' => $passengers, 'date' => date('Y-m-d'),
    ]);
    $connection->commit();
} catch (Throwable $error) {
    if ($connection->inTransaction()) {
        $connection->rollBack();
    }
    throw $error;
}
```

- **Utilizar** — Integra no ramo de criação do controlador, depois de validar POST, CSRF, $tripId, $customerId e $passengers. Usa os auxiliares de reservations.php e o tratamento de erros do controlador.
- **Adaptar** — Na edição passa o ID da própria reserva a occupiedSeats() e usa UPDATE. Ao mudar de passeio segue o bloqueio de ambos os passeios da aplicação.
- **Erro comum** — Calcular antes de bloquear ou libertar o bloqueio antes do INSERT.

## HTML e JavaScript

### 19. Formulário e tabela Bootstrap

Cria um filtro GET e apresenta resultados numa tabela responsiva.

```php
<?php
require __DIR__ . '/../app/bootstrap.php';
$title = 'Pesquisar barcos';
$name = inputText($_GET, 'name');
$rows = fetchAll('SELECT id, name FROM boats WHERE name LIKE :name ORDER BY name',
    ['name' => '%' . $name . '%']);
require __DIR__ . '/../app/views/header.php';
?>
<form method="get" class="mb-3">
    <label for="name" class="form-label">Nome</label>
    <input id="name" name="name" class="form-control" value="<?= e($name) ?>">
    <button class="btn btn-primary mt-2" type="submit">Pesquisar</button>
</form>
<div class="table-responsive" tabindex="0" role="region" aria-label="Tabela de barcos">
    <table class="table table-striped">
        <thead><tr><th scope="col">ID</th><th scope="col">Nome</th></tr></thead>
        <tbody>
        <?php foreach ($rows as $row): ?>
            <tr><td><?= e($row['id']) ?></td><td><?= e($row['name']) ?></td></tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require __DIR__ . '/../app/views/footer.php'; ?>
```

- **Utilizar** — Abre o ficheiro. O cabeçalho carrega o Bootstrap local.
- **Adaptar** — Troca rótulos, campos e colunas apresentados.
- **Erro comum** — Aplicar table-responsive diretamente à tabela.

### 20. Validação JavaScript e confirmação de eliminação

Dá feedback ao escrever e pede confirmação adicional antes do POST.

```php
<?php
// Use in the confirmation view of the DELETE example with a validated $id.
?>
<form id="delete-form" method="post" action="<?= e(url('boats/delete.php?id=' . $id)) ?>">
    <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
    <label for="confirmation">Escreve ELIMINAR para confirmar</label>
    <input id="confirmation" required pattern="ELIMINAR">
    <button type="submit">Confirmar eliminação</button>
</form>
<script>
const form = document.querySelector('#delete-form');
const field = document.querySelector('#confirmation');
field.addEventListener('input', () => {
    const valid = field.value === '' || field.value === 'ELIMINAR';
    field.setCustomValidity(valid ? '' : 'Escreve ELIMINAR.');
});
form.addEventListener('submit', (event) => {
    if (!window.confirm('Confirmas a eliminação?')) {
        event.preventDefault();
    }
});
</script>
```

- **Utilizar** — Experimenta o excerto na vista de confirmação de boats/delete.php, sobre um barco de treino. O destino POST volta a verificar CSRF e as dependências.
- **Adaptar** — Usa uma regra curta para o campo e ajusta a pergunta à ação.
- **Erro comum** — Confiar no JavaScript para proteger a gravação.
