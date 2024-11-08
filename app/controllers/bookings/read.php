<?php
require_once(__DIR__ . '/../../models/db.php');
require_once(__DIR__ . '/../../models/booking.php');

// Conectar con la base de datos
$db = db_connect();
if (!$db) {
    die("Error al conectar con la base de datos");
}

// Crear una instancia del modelo Booking
$booking = new Booking($db);

// Capturar y validar el parámetro id_tipo_reserva
$Id_tipo_reserva = isset($_GET['id_tipo_reserva']) && $_GET['id_tipo_reserva'] !== '' ? (int)$_GET['id_tipo_reserva'] : null;

// Llamar al metodo para obtener las reservas filtradas por tipo
$bookings = $booking->getBookingsByType($Id_tipo_reserva);
?>
