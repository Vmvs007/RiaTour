# Guia de adaptação — WorldSkills

Escolhe o negócio, desenha as relações e implementa primeiro o percurso mínimo de ponta a ponta. Usa a [cábula de código](11-CABULA-CODIGO.md) para reproduzir as operações à medida que forem necessárias.

## Biblioteca

| Elemento RiaTour | Novo elemento | Ficheiros a alterar | Regra a rever |
| --- | --- | --- | --- |
| Percurso e barco | Título e exemplar | `database/01_schema.sql`, `public/routes/`, `public/boats/` | Um título pode ter vários exemplares. Identificar cada exemplar e registar se está apto a ser emprestado. |
| Cliente | Leitor | `public/customers/` e esquema SQL | Escolher campos de identificação e contacto. |
| Passeio e reserva | Empréstimo de exemplar ao leitor | `public/trips/`, `public/bookings/`, `app/helpers/reservations.php` e esquema SQL | Guardar datas de empréstimo, prazo e devolução. Bloquear o exemplar durante a gravação e impedir um segundo empréstimo ativo. Um empréstimo direto pode dispensar a entidade equivalente ao passeio. |

**Ensaio mínimo** — criar um título com dois exemplares, emprestar um, rejeitar novo empréstimo desse exemplar e voltar a disponibilizá-lo após devolução.

## Gestão de salas

| Elemento RiaTour | Novo elemento | Ficheiros a alterar | Regra a rever |
| --- | --- | --- | --- |
| Barco | Sala | `public/boats/` e esquema SQL | Capacidade máxima e disponibilidade da sala. |
| Percurso e passeio | Tipo de utilização e intervalo reservado | `public/routes/`, `public/trips/` e esquema SQL | Guardar início e fim. Para uso exclusivo, detetar sobreposição na mesma sala com `inicio_existente < fim_novo AND fim_existente > inicio_novo`. |
| Cliente e reserva | Responsável e agendamento | `public/customers/`, `public/bookings/`, `app/helpers/reservations.php` | Bloquear a linha da sala antes de verificar intervalos. Validar que o grupo cabe na sala. Para sessões partilhadas, somar participantes por sessão. |

**Ensaio mínimo** — reservar das 10h00 às 11h00, rejeitar das 10h30 às 11h30 e aceitar a partir das 11h00.

## Empréstimo de equipamentos

| Elemento RiaTour | Novo elemento | Ficheiros a alterar | Regra a rever |
| --- | --- | --- | --- |
| Percurso e barco | Categoria e equipamento | `public/routes/`, `public/boats/` e esquema SQL | Registar número de série para unidades individuais. Para unidades indistinguíveis, guardar o stock do tipo. |
| Cliente | Requisitante | `public/customers/` e esquema SQL | Identificar a pessoa que levanta e devolve o equipamento. |
| Passeio e reserva | Período e requisição | `public/trips/`, `public/bookings/`, `app/helpers/reservations.php` e esquema SQL | Para bens individuais, verificar sobreposição e bloquear a unidade. Para stock partilhado, validar a ocupação simultânea em cada parte do intervalo e bloquear a linha de stock durante a gravação. |

**Ensaio mínimo** — reservar uma unidade, rejeitar uma requisição sobreposta e confirmar que uma devolução liberta a unidade.

## Gestão de eventos

| Elemento RiaTour | Novo elemento | Ficheiros a alterar | Regra a rever |
| --- | --- | --- | --- |
| Barco e percurso | Recinto e evento | `public/boats/`, `public/routes/` e esquema SQL | Definir a lotação do recinto e a informação do evento. |
| Passeio | Sessão do evento | `public/trips/` e esquema SQL | Ligar evento a recinto e indicar data e hora. O sold out é calculado por sessão. |
| Cliente e reserva | Participante e reserva de bilhetes | `public/customers/`, `public/bookings/`, `app/helpers/reservations.php` | Somar bilhetes segundo os estados que ocupam lugares. Excluir a própria reserva na edição e libertar bilhetes ao cancelar. |

**Ensaio mínimo** — reservar os últimos lugares, rejeitar o pedido seguinte e confirmar a reabertura de disponibilidade após cancelamento.

## O que reaproveitar

| Reutilizar | Adaptar ao negócio |
| --- | --- |
| Ligação PDO e parâmetros de `fetchAll()` em `app/helpers/functions.php` | Credenciais e nome da base em `app/config/local.php`. Consultas, campos e filtros nos controladores. |
| Escape HTML, leitura de entradas, CSRF, mensagens e redirecionamentos | Listas de valores permitidos, obrigatoriedade, limites, dependências e mensagens de validação. |
| Cabeçalho, rodapé, tabela Bootstrap e confirmação por POST | Menu em `app/bootstrap.php`, rótulos, colunas e páginas em `public/`. |
| Padrão de transação e bloqueio de `app/helpers/reservations.php` | Recurso bloqueado, regra de ocupação ou sobreposição e estados usados pelo cálculo. |

## Sequência para a prova

1. Começa pelos comentários `ADAPTAR` em [01_schema.sql](../database/01_schema.sql). Revê tipos, estados, campos de agenda, chaves e a vista de disponibilidade.
2. Atualiza [02_seed.sql](../database/02_seed.sql) e [03_queries.sql](../database/03_queries.sql). Usa dados que mostrem um caso válido, um sem capacidade e um cancelado ou devolvido.
3. Parte de [boats/index.php](../public/boats/index.php), [boats/form.php](../public/boats/form.php) e [boats/delete.php](../public/boats/delete.php). Os comentários `ADAPTAR` apontam para colunas pesquisáveis, campos, regras e parâmetros de eliminação.
4. Revê em conjunto [bookings/form.php](../public/bookings/form.php), [reservations.php](../app/helpers/reservations.php) e a vista SQL. Usa a mesma definição de ocupação na gravação, no estado e nas consultas.
5. Testa criar, consultar, editar e eliminar. Depois confirma as fronteiras da regra do negócio e dois pedidos ao mesmo recurso quando houver concorrência.
