USE bdRiaTour_NomeConcorrente_C27;
SET NAMES utf8mb4;

-- Q1
-- EXEMPLO: WHERE restricts the result to active boats.
-- ADAPTAR: Change the state filter to match the business terminology.
SELECT id, name, capacity, status
FROM boats
WHERE status = 'ativo'
ORDER BY name, id;

-- Q2
-- EXEMPLO: ORDER BY sorts routes by name and uses the ID to break ties.
-- ADAPTAR: Replace name with the field users expect to sort by.
SELECT id, name, description, duration_minutes, price_per_passenger
FROM routes
ORDER BY name, id;

-- Q3
-- EXEMPLO: JOIN combines trip details with the names and derived status.
-- ADAPTAR: Add a date filter if the report covers a specific period.
SELECT t.id, t.trip_date, t.trip_time, b.name AS boat_name, r.name AS route_name
FROM trips AS t
JOIN boats AS b ON b.id = t.boat_id
JOIN routes AS r ON r.id = t.route_id
JOIN trip_availability AS a ON a.trip_id = t.id
WHERE a.status = 'disponível'
ORDER BY t.trip_date, t.trip_time, t.id;

-- Q4
-- EXEMPLO: Follow the foreign keys from a booking to its customer and route.
-- ADAPTAR: Add a status filter only for a report restricted to specific bookings.
SELECT bk.id AS booking_id, c.name AS customer_name, r.name AS route_name,
       t.trip_date, t.trip_time
FROM bookings AS bk
JOIN customers AS c ON c.id = bk.customer_id
JOIN trips AS t ON t.id = bk.trip_id
JOIN routes AS r ON r.id = t.route_id
ORDER BY t.trip_date, t.trip_time, bk.id;

-- Q5
-- EXEMPLO: Read the SUM grouped by trip from the view, including zero totals.
-- ADAPTAR: Change the occupancy policy in the view to keep reports consistent.
SELECT t.id, t.trip_date, t.trip_time, a.reserved_passengers
FROM trips AS t
JOIN trip_availability AS a ON a.trip_id = t.id
ORDER BY t.trip_date, t.trip_time, t.id;

-- Q6
-- EXEMPLO: Use a session variable to choose a boat when running the SQL file.
-- ADAPTAR: In PHP/PDO, use a bound parameter for the boat ID.
SET @boat_id = 1;
SELECT t.id, b.name AS boat_name, r.name AS route_name,
       t.trip_date, t.trip_time, a.status
FROM trips AS t
JOIN boats AS b ON b.id = t.boat_id
JOIN routes AS r ON r.id = t.route_id
JOIN trip_availability AS a ON a.trip_id = t.id
WHERE t.boat_id = @boat_id
ORDER BY t.trip_date, t.trip_time, t.id;

-- EXEMPLO: Run practice changes together in a transaction.
-- ADAPTAR: Use an explicit commit only when the changes should be kept.
START TRANSACTION;

-- EXEMPLO: INSERT creates a disposable row. LAST_INSERT_ID captures its key.
-- ADAPTAR: Replace the values with the new customer's contact details.
INSERT INTO customers (name, email, phone)
VALUES ('Cliente de treino', 'treino@example.test', '+351 000 000 003');
SET @practice_customer_id = LAST_INSERT_ID();

-- EXEMPLO: UPDATE with the primary key changes one chosen customer.
-- ADAPTAR: Bind the new values and the ID when calling this from PHP/PDO.
UPDATE customers
SET phone = '+351 000 000 004'
WHERE id = @practice_customer_id;

-- EXEMPLO: SELECT with WHERE checks the updated row by its primary key.
-- ADAPTAR: Select the columns and filters needed on the current screen.
SELECT id, name, email, phone
FROM customers
WHERE id = @practice_customer_id;

-- EXEMPLO: DELETE targets only the disposable customer created above.
-- ADAPTAR: Check whether the real record is referenced before offering deletion.
DELETE FROM customers
WHERE id = @practice_customer_id;

-- EXEMPLO: ROLLBACK leaves the sample rows unchanged.
-- ADAPTAR: Use this pattern for experiments with transactional tables.
ROLLBACK;

-- EXEMPLO: JOIN resolves each trip's boat ID to a readable name.
-- ADAPTAR: Use the corresponding foreign key for a different relationship.
SELECT t.id AS trip_id, b.name AS boat_name
FROM trips AS t
JOIN boats AS b ON b.id = t.boat_id
ORDER BY t.id;

-- EXEMPLO: GROUP BY and SUM count seats within each stored booking state.
-- ADAPTAR: Add a trip filter to analyse the bookings for one departure.
SELECT status, SUM(passengers) AS passengers
FROM bookings
GROUP BY status
ORDER BY status;
