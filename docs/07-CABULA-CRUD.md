# Cábula CRUD — barcos, percursos e clientes

As três áreas usam páginas PHP convencionais e a base do Módulo 1. Cada pasta contém quatro páginas. O formulário serve para criar e editar.

| Página | Pedido | Responsabilidade |
| --- | --- | --- |
| index.php | GET | Pesquisar e listar com SELECT e fetchAll(). |
| view.php?id=1 | GET | Consultar um registo pelo ID. |
| form.php | GET / POST | Mostrar o formulário e criar um registo. |
| form.php?id=1 | GET / POST | Mostrar os dados existentes e atualizar o ID indicado. |
| delete.php?id=1 | GET / POST | Mostrar a confirmação e processar a eliminação confirmada. |

Os formulários de pesquisa usam GET para permitir copiar o endereço dos resultados. As operações de gravação usam POST com um token CSRF. Após uma gravação bem-sucedida, a resposta redireciona com HTTP 303 para evitar repetir a operação ao atualizar a página.

## SELECT, pesquisa e fetchAll()

Em [boats/index.php](../public/boats/index.php), a pesquisa por nome usa LIKE e a pesquisa por estado usa igualdade. Ao preencher os dois campos, ambos os filtros são aplicados. Nos clientes, o mesmo texto procura no nome ou no email. Os percursos permitem pesquisar pelo nome.

```php
// EXEMPLO: Bind the search value independently of SQL syntax.
// ADAPTAR: Replace the searchable column to match the new entity.
$rows = fetchAll(
    'SELECT id, name, capacity, status FROM boats WHERE name LIKE :name',
    ['name' => '%' . $name . '%']
);
```

O auxiliar fetchAll() prepara a consulta, executa-a com os parâmetros e devolve um conjunto de linhas associativas. O símbolo % permite encontrar o texto em qualquer posição. Os caracteres % e _ introduzidos no campo mantêm o significado de padrões de LIKE.

A consulta individual usa fetchRecord(), que devolve uma linha ou apresenta uma página 404. Um ID inválido produz uma página 400. Os nomes das tabelas e colunas são definidos pelo programador nos ficheiros.

## INSERT com POST e formulário HTML

Em [boats/form.php](../public/boats/form.php), o ramo sem ID cria o barco depois de validar os dados.

```php
// EXEMPLO: Insert one boat using parameters from validated form values.
// ADAPTAR: Match the columns and parameters to the target table.
$statement = database()->prepare(
    'INSERT INTO boats (name, capacity, status) VALUES (:name, :capacity, :status)'
);
$statement->execute([
    'name' => $values['name'],
    'capacity' => $values['capacity'],
    'status' => $values['status'],
]);
```

O formulário preserva os valores enviados quando há um erro. O ID é atribuído pela base de dados.

```php
<form method="post" action="<?= e(url('boats/form.php')) ?>">
    <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
    <label for="name">Nome</label>
    <input id="name" name="name" value="<?= e($values['name']) ?>" required>
    <!-- EXEMPLO: Include the remaining required boat fields before submitting. -->
    <button type="submit">Guardar</button>
</form>
```

O excerto mostra a estrutura do formulário. A página completa inclui capacidade e estado. O controlador chama requireValidPost() antes de processar os campos. inputText() aceita texto escalar e retira espaços nas extremidades. e() escapa os valores apresentados em HTML.

## UPDATE por ID

A mesma página recebe um ID validado no endereço e carrega o registo para edição. Antes de atualizar, inicia uma transação e bloqueia a linha com SELECT ... FOR UPDATE, confirmando que o registo continua a existir.

```php
// EXEMPLO: Update the record selected by its validated primary key.
// ATENCAO: Keep the WHERE clause to avoid updating every row.
$statement = database()->prepare(
    'UPDATE boats SET name = :name, capacity = :capacity, status = :status WHERE id = :id'
);
$statement->execute([
    'name' => $values['name'],
    'capacity' => $values['capacity'],
    'status' => $values['status'],
    'id' => $id,
]);
```

Nos barcos, a nova capacidade tem de acomodar as reservas pendentes e confirmadas de cada passeio associado. A edição bloqueia os passeios e as reservas relevantes durante a verificação. A vista de disponibilidade do Módulo 1 acompanha a nova capacidade. Os futuros fluxos de gravação de reservas também terão de verificar a lotação dentro de uma transação.

## DELETE e confirmação

O botão Eliminar abre [boats/delete.php](../public/boats/delete.php), onde se apresenta o nome e o ID do registo. Cancelar regressa ao detalhe. Confirmar eliminação envia um POST com CSRF.

```php
// EXEMPLO: Delete the selected row after confirmation and CSRF validation.
// ADAPTAR: Change the table while preserving the bound primary key.
$statement = database()->prepare('DELETE FROM boats WHERE id = :id');
$statement->execute(['id' => $id]);
```

As chaves estrangeiras bloqueiam a eliminação de barcos ou percursos com passeios associados e de clientes com reservas. A página trata o erro 1451 devolvido pelo servidor e explica a dependência ao utilizador. O pedido responde com HTTP 409 e os dados permanecem na base.

## Validação e mensagens

| Área | Validações no servidor |
| --- | --- |
| Barcos | Nome obrigatório até 100 caracteres. Capacidade inteira entre 1 e 65535. Estado ativo ou inativo. Capacidade suficiente para as reservas existentes. |
| Percursos | Nome obrigatório até 100 caracteres. Descrição obrigatória até ao limite de bytes de TEXT. Duração inteira entre 1 e 65535 minutos. Preço positivo até 999999,99 €, com no máximo duas casas decimais. |
| Clientes | Nome, email e telefone obrigatórios, dentro dos limites das colunas. Formato do email validado com FILTER_VALIDATE_EMAIL. |

O preço aceita vírgula ou ponto decimal. O telefone permanece texto para conservar prefixos e zeros. As regras do HTML ajudam a preencher o formulário. A validação PHP volta a aplicar as regras quando o pedido chega ao servidor.

Os erros de validação apresentam uma lista de mensagens com HTTP 422. As mensagens de sucesso são guardadas na sessão e apresentadas na página de destino. Os detalhes técnicos das exceções PDO seguem para o registo de erros do PHP.

## Adaptar o exemplo a produtos, equipamentos ou salas

Copiar a organização das quatro páginas de barcos e editar explicitamente cada consulta, formulário e validação. Manter PDO, parâmetros, escape HTML, POST, CSRF, confirmação e redirecionamento após gravar.

| Exemplo | Campos possíveis | Regras a adaptar |
| --- | --- | --- |
| Produtos | ID, nome, preço e quantidade | Preço em DECIMAL e validação da quantidade conforme o negócio. |
| Equipamentos | ID, nome, número de série e estado | Comprimento dos campos, domínio de estados e eventual unicidade do número de série. |
| Salas | ID, nome, capacidade e estado | Capacidade positiva e verificação de reservas associadas. |

1. Criar a tabela e definir as relações antes de alterar o PHP.
2. Atualizar o nome da pasta, o menu e os títulos.
3. Alterar o SELECT, os filtros de pesquisa e os rótulos das colunas.
4. Adaptar os campos do formulário, os limites e as validações.
5. Atualizar as colunas e os parâmetros do INSERT e do UPDATE.
6. Rever as dependências que podem bloquear o DELETE e escrever a mensagem adequada.
7. Testar a sequência criar, consultar, editar, pesquisar e eliminar, incluindo entradas inválidas.

Nos produtos, a verificação de lotação de passeios deve ser substituída pela regra de negócio aplicável. Nas salas, a mesma ideia pode verificar a ocupação das reservas. A reutilização do layout e dos auxiliares mantém as páginas legíveis, enquanto as consultas permanecem explícitas em cada área.

## Testes realizados

Testados por HTTP com PHP 8.2.12 e uma base temporária MariaDB 10.4.32, usando o esquema e os dados reais do Módulo 1. Foram confirmados o ciclo completo de cada CRUD, persistência na base, pesquisas, email inválido, valores zero, IDs inválidos e inexistentes, rejeição de tokens CSRF inválidos, escape de HTML e bloqueio de eliminações com dependências. Foi também testada a rejeição da redução da capacidade do barco abaixo dos lugares reservados.

A execução num servidor MySQL continua por verificar. Para reproduzir manualmente, seguir a configuração do [README](../README.md), abrir cada área, criar um registo de treino, pesquisar pelo nome, editar e eliminar. Nos dados de exemplo, tentar eliminar o barco, percurso ou cliente com ID 1 deve apresentar uma mensagem de dependência.
