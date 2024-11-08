<?php
session_start();

// Declaraciones de inclusión
require_once(__DIR__ . '/../../models/db.php');
require_once(__DIR__ . '/../../models/booking.php');

// Conectar con la base de datos
$db = db_connect();
if (!$db) {
    die("Error al conectar con la base de datos");
}

// Crear una instancia del modelo Booking
$booking = new Booking($db);

// Verificar si es admin o traveler y filtrar las reservas
if (isset($_SESSION['admin'])) {
    // El admin ve todas las reservas
    $bookings = $booking->getAllBookings();
} elseif (isset($_SESSION['travelerUser'])) {
    // El traveler solo ve sus reservas
    $email_cliente = $_SESSION['travelerUser'];
    $bookings = $booking->getBookingsByEmail($email_cliente);
} else {
    // Si no está identificado, no devolver nada
    echo json_encode([]);
    exit;
}

// Formatear los datos del array events para FullCalendar
$events = [];
foreach ($bookings as $row) {
    // Validar las fechas y horas para usar una alternativa
    $id_tipo_reserva = $row['id_tipo_reserva'];
    $fechaEntrada = $row['fecha_entrada'];
    $fechaVueloSalida = $row['fecha_vuelo_salida'];
    $horaEntrada = $row['hora_entrada'];
    $horaSalida = $row['hora_vuelo_salida'];

    // Verificar si la fecha es de id_tipo_reserva 1. Si es 1 usa fecha_entrada. Si es 2 usa fecha_vuelo_salida
    $startDate = ($id_tipo_reserva == 1) ? $fechaEntrada : $fechaVueloSalida;
    // Verificar si la hora es de id_tipo_reserva 1. Si es 1 usa hora_entrada. Si es 2 usa hora_vuelo_salida
    $startTime = ($id_tipo_reserva == 1) ? $horaEntrada : $horaSalida;
    // Concatenar la fecha y la hora para el campo 'start'
    $start = ($startDate && $startTime) ? "$startDate $startTime" : $startDate;
    //Array de events
    $events[] = [
        'id' => $row['id_reserva'],
        'title' => 'Hotel ' . $row['id_hotel'],
        'start' => $start,
        'extendedProps' => [
            'id_hotel' => $row['id_hotel'],
            'localizador' => $row['localizador'],
            'id_tipo_reserva' => $row['id_tipo_reserva'],
            'email_cliente' => $row['email_cliente'],
            'fecha_reserva' => $row['fecha_reserva'],
            'fecha_modificacion' => $row['fecha_modificacion'],
            'hora_entrada' => $row['hora_entrada'],
            'numero_vuelo_entrada' => $row['numero_vuelo_entrada'],
            'origen_vuelo_entrada' => $row['origen_vuelo_entrada'],
            'hora_vuelo_salida' => $row['hora_vuelo_salida'],
            'num_viajeros' => $row['num_viajeros'],
            'id_vehiculo' => $row['id_vehiculo'],
            'tipo_creador_reserva' => $row['tipo_creador_reserva'] // Usar el campo directamente
        ]
    ];
}

// Devolver los datos en formato JSON
header('Content-Type: application/json');
echo json_encode($events);
?>
