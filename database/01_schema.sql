-- EXEMPLO: Use InnoDB for foreign keys and transactions.
-- ADAPTAR: Replace NomeConcorrente consistently in the three scripts.
CREATE DATABASE IF NOT EXISTS bdRiaTour_NomeConcorrente_C27
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE bdRiaTour_NomeConcorrente_C27;
SET NAMES utf8mb4;

-- EXEMPLO: Keep each boat's capacity and activity status in one place.
-- ADAPTAR: Change the status domain if a business requires more states.
CREATE TABLE boats (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    capacity SMALLINT UNSIGNED NOT NULL,
    status VARCHAR(10) NOT NULL DEFAULT 'ativo',
    CONSTRAINT chk_boats_name CHECK (CHAR_LENGTH(TRIM(name)) > 0),
    CONSTRAINT chk_boats_capacity CHECK (capacity > 0),
    CONSTRAINT chk_boats_status CHECK (status IN ('ativo', 'inativo'))
) ENGINE=InnoDB;

-- EXEMPLO: DECIMAL stores exact monetary values. Duration is in minutes.
-- ADAPTAR: Choose the price precision and duration unit for the business.
CREATE TABLE routes (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    duration_minutes SMALLINT UNSIGNED NOT NULL,
    price_per_passenger DECIMAL(8,2) NOT NULL,
    CONSTRAINT chk_routes_name CHECK (CHAR_LENGTH(TRIM(name)) > 0),
    CONSTRAINT chk_routes_description CHECK (CHAR_LENGTH(TRIM(description)) > 0),
    CONSTRAINT chk_routes_duration CHECK (duration_minutes > 0),
    CONSTRAINT chk_routes_price CHECK (price_per_passenger > 0)
) ENGINE=InnoDB;

-- EXEMPLO: Store phone numbers as text to preserve prefixes and leading zeros.
-- ADAPTAR: Change field lengths to suit the formats accepted by the business.
CREATE TABLE customers (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(254) NOT NULL,
    phone VARCHAR(32) NOT NULL,
    CONSTRAINT chk_customers_name CHECK (CHAR_LENGTH(TRIM(name)) > 0),
    CONSTRAINT chk_customers_email CHECK (CHAR_LENGTH(TRIM(email)) > 0),
    CONSTRAINT chk_customers_phone CHECK (CHAR_LENGTH(TRIM(phone)) > 0)
) ENGINE=InnoDB;

-- EXEMPLO: Reference the boat and route instead of duplicating their attributes.
-- ADAPTAR: Adjust the scheduling fields when trips use a different time model.
-- ATENCAO: The view derives the trip status. Store only the cancellation decision.
CREATE TABLE trips (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    boat_id INT UNSIGNED NOT NULL,
    route_id INT UNSIGNED NOT NULL,
    trip_date DATE NOT NULL,
    trip_time TIME NOT NULL,
    is_cancelled TINYINT UNSIGNED NOT NULL DEFAULT 0,
    CONSTRAINT chk_trips_time CHECK (trip_time BETWEEN '00:00:00' AND '23:59:59'),
    CONSTRAINT chk_trips_cancelled CHECK (is_cancelled IN (0, 1)),
    CONSTRAINT fk_trips_boat FOREIGN KEY (boat_id) REFERENCES boats (id) ON DELETE RESTRICT,
    CONSTRAINT fk_trips_route FOREIGN KEY (route_id) REFERENCES routes (id) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- EXEMPLO: A booking belongs to one customer and one trip.
-- ADAPTAR: Update the booking status domain and the occupancy view together.
CREATE TABLE bookings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    customer_id INT UNSIGNED NOT NULL,
    trip_id INT UNSIGNED NOT NULL,
    passengers SMALLINT UNSIGNED NOT NULL,
    booking_date DATE NOT NULL,
    status VARCHAR(10) NOT NULL DEFAULT 'pendente',
    CONSTRAINT chk_bookings_passengers CHECK (passengers > 0),
    CONSTRAINT chk_bookings_status CHECK (status IN ('pendente', 'confirmada', 'cancelada')),
    CONSTRAINT fk_bookings_customer FOREIGN KEY (customer_id) REFERENCES customers (id) ON DELETE RESTRICT,
    CONSTRAINT fk_bookings_trip FOREIGN KEY (trip_id) REFERENCES trips (id) ON DELETE RESTRICT,
    INDEX idx_bookings_trip_status (trip_id, status)
) ENGINE=InnoDB;

-- EXEMPLO: LEFT JOIN keeps trips without bookings. COALESCE turns NULL into zero.
-- ADAPTAR: Change the ON filter to match the statuses that occupy seats.
-- ATENCAO: This view reports occupancy. Write operations need their own capacity check.
CREATE SQL SECURITY INVOKER VIEW trip_availability AS
SELECT
    t.id AS trip_id,
    b.capacity,
    COALESCE(SUM(bk.passengers), 0) AS reserved_passengers,
    CASE
        WHEN t.is_cancelled = 1 THEN 'cancelado'
        WHEN COALESCE(SUM(bk.passengers), 0) >= b.capacity THEN 'esgotado'
        ELSE 'disponível'
    END AS status
FROM trips AS t
JOIN boats AS b ON b.id = t.boat_id
LEFT JOIN bookings AS bk
    ON bk.trip_id = t.id AND bk.status IN ('pendente', 'confirmada')
GROUP BY t.id, b.capacity, t.is_cancelled;
