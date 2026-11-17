# Relações e reservas

Passeios e reservas usam index.php, view.php, form.php e delete.php nas respetivas pastas de public/. A edição da reserva inclui a alteração de estado. As eliminações exigem confirmação, POST e CSRF. Um passeio com reservas é protegido pela chave estrangeira.

## JOIN e dropdowns

As listagens apresentam nomes de barcos, percursos e clientes. O dropdown de passeios identifica percurso, barco, data e hora.

```sql
-- EXEMPLO: Resolve foreign keys into readable names.
-- ADAPTAR: Join rooms or equipment through their corresponding keys.
SELECT t.id, b.name AS boat, r.name AS route
FROM trips t
JOIN boats b ON b.id = t.boat_id
JOIN routes r ON r.id = t.route_id;
```

```php
// EXEMPLO: Load dropdown options from the database.
// ADAPTAR: Use the identifier and label of the new entity.
$boats = fetchAll('SELECT id, name FROM boats ORDER BY name');
?>
<select name="boat_id" required>
    <?php foreach ($boats as $boat): ?>
        <option value="<?= e($boat['id']) ?>"><?= e($boat['name']) ?></option>
    <?php endforeach; ?>
</select>
```

O servidor verifica se a opção enviada existe. As chaves estrangeiras continuam a proteger a gravação se uma entidade for eliminada entretanto.

## Ocupação

Considerar reservas pendentes e confirmadas como ocupação é a interpretação adotada para o enunciado. As reservas canceladas libertam lugares.

```sql
-- EXEMPLO: Exclude the booking being edited from the occupancy total.
-- ADAPTAR: Change the occupying states for the destination business.
SELECT COALESCE(SUM(passengers), 0)
FROM bookings
WHERE trip_id = :trip
  AND id <> :excluded
  AND status IN ('pendente', 'confirmada');
```

Na criação, excluded vale zero. Na edição, recebe o ID da própria reserva. A fórmula é capacidade_disponivel = capacidade_maxima - passageiros_reservados. Num barco com 10 lugares e 7 ocupados por outras reservas, a reserva em edição pode ter até 3 passageiros.

As reservas canceladas podem ser guardadas sem consumir lugares. A reativação para pendente ou confirmada volta a validar a capacidade. Um passeio cancelado rejeita reservas que ocupem lugares. A troca de barco de um passeio também verifica se o novo barco acomoda as reservas existentes.

## Transação e bloqueio

O código em app/helpers/reservations.php concentra as operações pequenas de bloqueio e soma. A gravação usa READ COMMITTED para obter uma soma atual depois de esperar pelo bloqueio.

```php
// EXEMPLO: Hold the trip lock until the reservation has been saved.
$connection = beginReservationTransaction();
$trip = lockTrip($connection, $tripId);
$capacity = boatCapacity($connection, (int) $trip['boat_id']);
$reserved = occupiedSeats($connection, $tripId, $bookingId);
$available = $capacity - $reserved;
// ATENCAO: Validate before INSERT or UPDATE, then commit.
if ($passengers > $available) {
    $connection->rollBack();
    throw new DomainException('Lugares insuficientes.');
}
// Execute the prepared INSERT or UPDATE here.
$connection->commit();
```

SELECT ... FOR UPDATE bloqueia o passeio até COMMIT ou ROLLBACK. Dois pedidos para o mesmo passeio esperam pelo mesmo bloqueio. O segundo calcula a ocupação já com a primeira reserva gravada. A capacidade do barco também é bloqueada para coordenar alterações concorrentes.

Na edição, a reserva é bloqueada e, numa mudança de passeio, os dois passeios são bloqueados por ordem de ID. As eliminações seguem a mesma proteção. Um conflito ou deadlock faz a transação recuar e apresenta uma mensagem para repetir o pedido. As alterações SQL manuais devem seguir o mesmo protocolo para preservar esta garantia.

## Estados coerentes

A tabela trips guarda is_cancelled. A vista trip_availability calcula o estado apresentado. Um passeio cancelado mantém esse estado. Nos restantes, a soma igual ou superior à capacidade resulta em esgotado e uma soma inferior resulta em disponível.

O formulário de passeios permite escolher cancelado ou o estado calculado disponível/esgotado. Ao criar, editar, cancelar ou eliminar reservas, a próxima consulta da vista já apresenta o estado atualizado. A soma usa a mesma interpretação de estados das validações.

## Adaptar a outro negócio

- Para salas, substituir passeio por sessão reservável e capacidade do barco por lotação da sala.
- Para bilhetes, usar evento ou sessão e contar os bilhetes das reservas que bloqueiam lugares.
- Para equipamentos, usar recurso e intervalo de utilização, definindo primeiro como detetar sobreposição temporal.

Conservar a transação, o bloqueio do recurso partilhado, a exclusão da própria reserva na edição e a validação antes da gravação. Adaptar os estados que consomem capacidade e os rótulos dos formulários.

## Verificação realizada

Os testes HTTP com PHP e MariaDB 10.4.32 confirmaram criação e eliminação de passeios, rejeição de datas passadas, criação e edição de reservas, lotação máxima, exclusão da própria reserva e libertação de lugares ao cancelar. A eliminação de um passeio com reservas foi bloqueada.

Dois processos PHP receberam pedidos simultâneos de sete lugares para um passeio com capacidade dez. Um pedido foi aceite e o outro rejeitado, ficando sete lugares ocupados. A sintaxe dos ficheiros PHP foi validada. A execução em MySQL permanece por verificar.
