<?php
// EXEMPLO: Escape every dynamic value before inserting it into HTML.
function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// EXEMPLO: A single base path supports both a virtual host and an Apache subfolder.
function url(string $path = ''): string
{
    global $config;
    return rtrim($config['base_url'], '/') . '/' . ltrim($path, '/');
}

// EXEMPLO: Store feedback until the next request, then consume it in the header.
function flash(string $type, string $message): void
{
    $_SESSION['messages'][] = ['type' => $type === 'success' ? 'success' : 'danger', 'text' => $message];
}

// EXEMPLO: Reuse one PDO connection during each request.
function database(): PDO
{
    global $config;
    static $connection = null;
    if ($connection === null) {
        $connection = new PDO(
            "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset=utf8mb4",
            $config['username'],
            $config['password'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
    }
    return $connection;
}

// EXEMPLO: Bind values independently of SQL syntax.
// ATENCAO: SQL comes from application files, never from request parameters.
function fetchAll(string $sql, array $parameters = []): array
{
    $statement = database()->prepare($sql);
    $statement->execute($parameters);
    return $statement->fetchAll();
}

// EXEMPLO: Include this token in future POST forms that change data.
function csrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// ATENCAO: Validate both the HTTP method and token before a state-changing operation.
function requireValidPost(): void
{
    $token = $_POST['csrf_token'] ?? '';
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !is_string($token)
        || !hash_equals(csrfToken(), $token)) {
        http_response_code(403);
        exit('Pedido inválido. Volte à página anterior e tente novamente.');
    }
}

// EXEMPLO: Accept scalar text only, then remove surrounding whitespace.
function inputText(array $input, string $key): string
{
    return isset($input[$key]) && is_string($input[$key]) ? trim($input[$key]) : '';
}

// ATENCAO: Validate identifiers before using them in a prepared statement.
function requestId(): int
{
    $id = filter_var(inputText($_GET, 'id'), FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if ($id === false) {
        pageError(400, 'Identificador inválido.');
    }
    return $id;
}

// EXEMPLO: Keep error pages inside the same navigation and layout.
function pageError(int $status, string $message): void
{
    global $navigation, $section;
    http_response_code($status);
    $title = 'Não foi possível concluir o pedido';
    flash('error', $message);
    require __DIR__ . '/../views/header.php';
    require __DIR__ . '/../views/footer.php';
    exit;
}

// EXEMPLO: Fetch a single record and handle a missing row explicitly.
function fetchRecord(string $sql, int $id): array
{
    try {
        $statement = database()->prepare($sql);
        $statement->execute(['id' => $id]);
        $record = $statement->fetch();
    } catch (PDOException $exception) {
        error_log($exception->getMessage());
        pageError(503, 'Não foi possível consultar os dados. Verifique a ligação à base de dados.');
    }
    if ($record === false) {
        pageError(404, 'O registo já não existe.');
    }
    return $record;
}

// EXEMPLO: Redirect after POST so refreshing the page does not repeat the write.
function redirectTo(string $path): void
{
    header('Location: ' . url($path), true, 303);
    exit;
}

// EXEMPLO: Reuse the required-field and length checks across forms.
// ADAPTAR: Set field limits according to the destination SQL columns.
function validateText(string $value, string $label, int $maximum, array &$errors, string $field): void
{
    if ($value === '' || mb_strlen($value) > $maximum) {
        $errors[$field] = $label . ' é obrigatório e deve ter até ' . $maximum . ' caracteres.';
    }
}

// EXEMPLO: Associate Bootstrap validation feedback with the control for assistive technology.
function fieldAttributes(array $errors, string $field): string
{
    return isset($errors[$field]) ? ' aria-invalid="true" aria-describedby="' . e($field) . '-error"' : '';
}

// ATENCAO: Some HTML controls cannot display an invalid submitted value.
function fieldFeedback(array $errors, string $field, string $submitted = ''): void
{
    if (isset($errors[$field])) {
        echo '<div class="invalid-feedback d-block" id="' . e($field) . '-error">' . e($errors[$field]);
        if ($submitted !== '') {
            echo ' Valor enviado: ' . e($submitted) . '.';
        }
        echo '</div>';
    }
}

// EXEMPLO: Keep the text visible so the colour is supplementary.
function statusBadge(string $status): string
{
    $colour = match ($status) {
        'ativo', 'disponível', 'confirmada' => 'success',
        'pendente', 'esgotado' => 'warning',
        'cancelada', 'cancelado' => 'danger',
        default => 'secondary',
    };
    return '<span class="badge text-bg-' . $colour . '">' . e(mb_convert_case($status, MB_CASE_TITLE, 'UTF-8')) . '</span>';
}
