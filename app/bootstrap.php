<?php
session_start();
date_default_timezone_set('Europe/Lisbon');
$config = require __DIR__ . '/config/local.php';
require_once __DIR__ . '/helpers/functions.php';
$navigation = [
    'boats' => 'Barcos', 'routes' => 'Percursos', 'trips' => 'Passeios',
    'customers' => 'Clientes', 'bookings' => 'Reservas',
];
