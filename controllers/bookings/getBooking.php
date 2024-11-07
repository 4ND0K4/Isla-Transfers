<?php
session_start();
require_once(__DIR__ . '/../../models/db.php');
require_once(__DIR__ . '/../../models/booking.php');

if (isset($_GET['id_reserva'])) {
    $idReserva = $_GET['id_reserva'];

    $db = db_connect();
    $bookingModel = new Booking($db);
    $booking = $bookingModel->getBookingById($idReserva);

    if ($booking) {
        echo json_encode($booking);
    } else {
        echo json_encode(['error' => 'Reserva no encontrada']);
    }
} else {
    echo json_encode(['error' => 'ID de reserva no especificado']);
}
?>