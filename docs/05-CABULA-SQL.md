# Cábula SQL — Módulo 1

## Importar

Usar **MySQL 8.0.16 ou posterior** para garantir a aplicação das restrições `CHECK`. Iniciar o servidor e abrir o cliente `mysql` a partir da pasta do projeto com um utilizador com permissões para criar a base, as tabelas e a vista.

```powershell
mysql --default-character-set=utf8mb4 -u root -p
```

No cliente MySQL, executar as três linhas por ordem.

```text
source database/01_schema.sql
source database/02_seed.sql
source database/03_queries.sql
```

No MySQL Workbench, abrir cada ficheiro SQL na mesma ligação e executar o ficheiro completo, pela mesma ordem. Usar uma base nova. Para trocar `NomeConcorrente` pelo identificador de prova, alterar o nome nos três ficheiros e usar o mesmo nome nos comandos de exportação.

## Testar

Os dados de exemplo criam 2 barcos, 2 percursos, 2 clientes, 3 passeios e 3 reservas. As datas dos horários são relativas ao dia em que se importa o ficheiro.

| Consulta | Resultado esperado com os exemplos |
| --- | --- |
| Q1 · Barcos ativos | Ria Serena. |
| Q2 · Percursos por nome | Canal Central, depois Circuito das Salinas. |
| Q3 · Horários disponíveis | Passeio 1, Ria Serena, Circuito das Salinas. |
| Q4 · Todas as reservas | Três linhas com cliente, percurso, data e hora da partida, incluindo a reserva cancelada. |
| Q5 · Passageiros por horário | Passeio 1: 0. Passeio 2: 10. Passeio 3: 0. Contam pendentes e confirmadas. |
| Q6 · Horários por barco | Com `@boat_id = 1`, passeios 1 e 2. Com `@boat_id = 2`, passeio 3. |

Para verificar a coerência do estado, executar este teste na base de exemplo.

```sql
-- EXEMPLO: Read the derived status before and after releasing seats.
-- ADAPTAR: Choose a full trip and a booking that currently holds seats.
START TRANSACTION;
SELECT trip_id, reserved_passengers, status FROM trip_availability WHERE trip_id = 2;
UPDATE bookings SET status = 'cancelada' WHERE id = 2;
SELECT trip_id, reserved_passengers, status FROM trip_availability WHERE trip_id = 2;
ROLLBACK;
```

Esperar a mudança de `10 / esgotado` para `7 / disponível`. Depois de `ROLLBACK`, a consulta volta a devolver `10 / esgotado`. Para verificar as restrições, tentar individualmente inserir um barco com capacidade zero ou eliminar o barco 1. O primeiro comando deve falhar por causa de `CHECK` e o segundo por causa da chave estrangeira.

Os exemplos finais de [03_queries.sql](../database/03_queries.sql) demonstram `INSERT`, `UPDATE`, `DELETE` e `SELECT ... WHERE` sobre um cliente descartável, dentro de uma transação. Também mostram um `JOIN` e a combinação de `GROUP BY` com `SUM`. A leitura do cliente de treino deve apresentar o telefone terminado em `004`. A execução deixa as linhas de exemplo intactas, embora o contador de identificadores de cliente possa avançar.

## Exportar

Sair do cliente MySQL com `exit`. No PowerShell, executar a exportação a partir da pasta onde se pretende guardar o ficheiro.

```powershell
mysqldump -u root -p --default-character-set=utf8mb4 --single-transaction --no-tablespaces --set-gtid-purged=OFF --databases bdRiaTour_NomeConcorrente_C27 --result-file=DB_RiaTour_NomeConcorrente_M1.sql
```

O ficheiro inclui a definição da base, tabelas, dados e vista. Para verificar o restauro, usar uma instância MySQL de teste com a base de destino livre. Abrir o cliente, executar `source DB_RiaTour_NomeConcorrente_M1.sql` e voltar a correr as consultas. Se a instância usar uma conta diferente, verificar que a conta que restaura pode criar a vista com o definidor incluído no dump ou ajustar esse definidor para a conta de destino.

Para preparar a entrega, exportar o [modelo ER](04-MODELO-ER.md) para `ER_RiaTour_NomeConcorrente_M1.pdf` e incluir no ZIP `WSC27_NomeConcorrente_M1.zip` juntamente com o dump e uma cópia de `03_queries.sql` chamada `SQL_RiaTour_NomeConcorrente_M1.sql`.

[Manual MySQL — restrições CHECK](https://dev.mysql.com/doc/refman/8.0/en/create-table-check-constraints.html) · [Manual MySQL — exportação com mysqldump](https://dev.mysql.com/doc/refman/8.4/en/mysqldump.html)
