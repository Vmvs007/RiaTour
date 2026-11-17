USE bdRiaTour_NomeConcorrente_C27;
SET NAMES utf8mb4;

-- EXEMPLO: Insert parent rows before rows that reference them.
-- ADAPTAR: Replace these fictional records with examples from the new business.
-- ATENCAO: Run once on the empty schema. Fixed IDs keep the sample relationships readable.
START TRANSACTION;

INSERT INTO boats (id, name, capacity, status) VALUES
    (1, 'Ria Serena', 10, 'ativo'),
    (2, 'Canal do Sal', 12, 'inativo');

INSERT INTO routes (id, name, description, duration_minutes, price_per_passenger) VALUES
    (1, 'Circuito das Salinas', 'Passeio pelos canais junto às salinas.', 45, 14.50),
    (2, 'Canal Central', 'Percurso pelo centro de Aveiro.', 30, 10.00);

INSERT INTO customers (id, name, email, phone) VALUES
    (1, 'Ana Silva', 'ana@example.test', '+351 000 000 001'),
    (2, 'João Costa', 'joao@example.test', '+351 000 000 002');

-- EXEMPLO: Relative dates keep the example trips ahead of the import date.
-- ADAPTAR: Change the dates and departure times for the local schedule.
INSERT INTO trips (id, boat_id, route_id, trip_date, trip_time, is_cancelled) VALUES
    (1, 1, 1, CURRENT_DATE + INTERVAL 1 DAY, '10:00:00', 0),
    (2, 1, 2, CURRENT_DATE + INTERVAL 1 DAY, '11:30:00', 0),
    (3, 2, 2, CURRENT_DATE + INTERVAL 2 DAY, '14:00:00', 1);

-- EXEMPLO: Trip 2 is full with 7 confirmed and 3 pending passengers.
-- ADAPTAR: Vary quantities and states to cover available and full trips.
INSERT INTO bookings (id, customer_id, trip_id, passengers, booking_date, status) VALUES
    (1, 1, 2, 7, CURRENT_DATE, 'confirmada'),
    (2, 2, 2, 3, CURRENT_DATE, 'pendente'),
    (3, 1, 1, 4, CURRENT_DATE, 'cancelada');

COMMIT;
