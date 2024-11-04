<?php
require_once(__DIR__ . '/../../models/db.php');
require_once(__DIR__ . '/../../models/booking.php');

session_start();

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
    $email_cliente = $_SESSION['travelerUser']; // Se asume que el email del traveler está en la sesión
    $bookings = $booking->getBookingsByEmail($email_cliente);
} else {
    // Si no está identificado, no devolver nada
    echo json_encode([]);
    exit;
}

// Formatear los datos para FullCalendar
$events = [];
foreach ($bookings as $row) {

    $creado_por = (isset($_SESSION['admin']) && $_SESSION['admin'] === $row['id_reserva']) ? 'admin' : 'traveler';
    // Validar las fechas y usar la fecha alternativa si una es "0000-00-00 00:00:00"
    $id_tipo_reserva = $row['id_tipo_reserva'];
    $fechaEntrada = $row['fecha_entrada'];
    $fechaVueloSalida = $row['fecha_vuelo_salida'];

    // Verificar si la fecha es "0000-00-00 00:00:00" y usar la alternativa
    if ($id_tipo_reserva == 1) {
        $startDate = $fechaEntrada;
    } else {
        $startDate = $fechaVueloSalida;
    }

    $events[] = [
        'id' => $row['id_reserva'],
        'title' => 'Hotel ' . $row['id_hotel'],
        'start' => $startDate,
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
            'creado_por' => $creado_por //
        ]
    ];
}

// Devolver los datos en formato JSON
header('Content-Type: application/json');
echo json_encode($events);
?>