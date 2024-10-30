<?php
require_once(__DIR__ . '/../../models/db.php');
require_once(__DIR__ . '/../../models/booking.php');

$db = db_connect();
$booking = new Booking($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'id_reserva' => $_POST['id_reserva'],
        'localizador' => $_POST['localizador'],
        'id_hotel' => $_POST['id_hotel'],
        'id_tipo_reserva' => $_POST['id_tipo_reserva'],
        'email_cliente' => $_POST['email_cliente'],
        'fecha_modificacion' => date('Y-m-d H:i:s'),
        'id_destino' => $_POST['id_destino'] ?? null,
        'num_viajeros' => $_POST['num_viajeros'],
        'id_vehiculo' => $_POST['id_vehiculo'] ?? null
    ];

    // Agrega campos adicionales según el tipo de reserva
    if ($data['id_tipo_reserva'] == 1) { // Aeropuerto - Hotel
        $data['fecha_entrada'] = $_POST['fecha_entrada'] ?? null;
        $data['hora_entrada'] = $_POST['hora_entrada'] ?? null;
        $data['numero_vuelo_entrada'] = $_POST['numero_vuelo_entrada'] ?? null;
        $data['origen_vuelo_entrada'] = $_POST['origen_vuelo_entrada'] ?? null;
    } elseif ($data['id_tipo_reserva'] == 2) { // Hotel - Aeropuerto
        $data['fecha_vuelo_salida'] = $_POST['fecha_vuelo_salida'] ?? null;
        $data['hora_vuelo_salida'] = $_POST['hora_vuelo_salida'] ?? null;
    }

    // Llama al método de actualización en el modelo
    $result = $booking->updateBooking($data);

    // Redirecciona o muestra un mensaje de éxito
    if ($result) {
        header("Location: ../views/dashboard-admin.php?status=updated");
        exit;
    } else {
        echo "Error al actualizar la reserva";
    }
}
