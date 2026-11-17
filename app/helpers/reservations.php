<?php
// EXEMPLO: Parse a calendar date strictly, including leap years.
function validDate(string $value): bool
{
    $date = DateTimeImmutable::createFromFormat('!Y-m-d', $value);
    return $date !== false && $date->format('Y-m-d') === $value;
}

// EXEMPLO: Use a fresh committed snapshot for aggregate queries after acquiring locks.
function beginReservationTransaction(): PDO
{
    $connection = database();
    $connection->exec('SET TRANSACTION ISOLATION LEVEL READ COMMITTED');
    $connection->beginTransaction();
    return $connection;
}

// ATENCAO: Lock the trip before reading occupancy. All reservation writers use this lock.
function lockTrip(PDO $connection, int $id): array
{
    $statement = $connection->prepare('SELECT * FROM trips WHERE id = :id FOR UPDATE');
    $statement->execute(['id' => $id]);
    $trip = $statement->fetch();
    if (!$trip) {
        throw new DomainException('O passeio selecionado já não existe.');
    }
    return $trip;
}

// EXEMPLO: SUM excludes cancelled bookings and the booking currently being edited.
// ADAPTAR: Change the occupying states for room, ticket or equipment reservations.
function occupiedSeats(PDO $connection, int $tripId, int $excludedId = 0): int
{
    $statement = $connection->prepare("SELECT COALESCE(SUM(passengers), 0) FROM bookings WHERE trip_id = :trip AND id <> :excluded AND status IN ('pendente', 'confirmada')");
    $statement->execute(['trip' => $tripId, 'excluded' => $excludedId]);
    return (int) $statement->fetchColumn();
}

// EXEMPLO: Lock capacity so a concurrent boat edit cannot invalidate this calculation.
function boatCapacity(PDO $connection, int $id): int
{
    $statement = $connection->prepare('SELECT capacity FROM boats WHERE id = :id FOR UPDATE');
    $statement->execute(['id' => $id]);
    $capacity = $statement->fetchColumn();
    if ($capacity === false) {
        throw new DomainException('O barco selecionado já não existe.');
    }
    return (int) $capacity;
}
