<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Declaraciones de inclusión
require_once(__DIR__ . '/../../models/db.php');
require_once(__DIR__ . '/../../models/booking.php');

// Conectar a la base de datos
$db = db_connect();
if (!$db) {
    $_SESSION['update_error'] = "Error al conectar con la base de datos.";
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

$booking = new Booking($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_destino = $_POST['id_destino'] ?? null;
    $id_reserva = $_POST['id_reserva'];

    // Validación para asegurar que id_destino sea un id_hotel válido
    $validHotelStmt = $db->prepare("SELECT COUNT(*) FROM tranfer_hotel WHERE id_hotel = :id_destino");
    $validHotelStmt->execute([':id_destino' => $id_destino]);
    $isValidHotel = $validHotelStmt->fetchColumn() > 0;

    if (!$isValidHotel) {
        $_SESSION['update_error'] = "Error: El id_destino no es válido.";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    // Obtener detalles de la reserva original
    $stmt = $db->prepare("SELECT id_tipo_reserva, fecha_entrada, fecha_vuelo_salida, tipo_creador_reserva FROM transfer_reservas WHERE id_reserva = :id_reserva");
    $stmt->execute([':id_reserva' => $id_reserva]);
    $reservaData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$reservaData) {
        $_SESSION['update_error'] = "Error: La reserva no existe.";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    // Si el usuario es traveler, aplicar la restricción de 48 horas
    if (isset($_SESSION['travelerUser'])) {
        $fechaMinima = (new DateTime())->modify('+2 days')->format('Y-m-d H:i:s');

        // Verificar si la reserva está dentro del período restringido de 48 horas
        if (($reservaData['id_tipo_reserva'] == 1 && $reservaData['fecha_entrada'] < $fechaMinima) ||
            ($reservaData['id_tipo_reserva'] == 2 && $reservaData['fecha_vuelo_salida'] < $fechaMinima)) {
            $_SESSION['update_48_error'] = "No puede modificar reservas con menos de 48 horas de antelación.";
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit;
        }
    }

    // Preparar los datos para la actualización
    $id_hotel = !empty($_POST['id_hotel']) ? $_POST['id_hotel'] : $id_destino;
    $data = [
        'id_reserva' => $id_reserva,
        'localizador' => $_POST['localizador'],
        'id_hotel' => $id_hotel,
        'id_tipo_reserva' => $_POST['id_tipo_reserva'],
        'email_cliente' => $_POST['email_cliente'],
        'fecha_modificacion' => date('Y-m-d H:i:s'),
        'id_destino' => $id_destino,
        'num_viajeros' => $_POST['num_viajeros'],
        'id_vehiculo' => $_POST['id_vehiculo'] ?? 1,
        'tipo_creador_reserva' => $reservaData['tipo_creador_reserva']
    ];

    // Agregar campos adicionales según el tipo de reserva
    if ($data['id_tipo_reserva'] == 1) { // Aeropuerto - Hotel
        $data['fecha_entrada'] = $_POST['fecha_entrada'] ?? null;
        $data['hora_entrada'] = $_POST['hora_entrada'] ?? null;
        $data['numero_vuelo_entrada'] = $_POST['numero_vuelo_entrada'] ?? null;
        $data['origen_vuelo_entrada'] = $_POST['origen_vuelo_entrada'] ?? null;
    } elseif ($data['id_tipo_reserva'] == 2) { // Hotel - Aeropuerto
        $data['fecha_vuelo_salida'] = $_POST['fecha_vuelo_salida'] ?? null;
        $data['hora_vuelo_salida'] = $_POST['hora_vuelo_salida'] ?? null;
    }

    // Llamar al método de actualización en el modelo
    $result = $booking->updateBooking($data);

    // Establecer el mensaje según el resultado de la actualización
    if ($result) {
        $_SESSION['update_booking_success'] = "Reserva actualizada correctamente.";
    } else {
        $_SESSION['update_booking_error'] = "Error al actualizar la reserva.";
    }

    // Redirigir a la página de origen para mostrar el mensaje
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}
?>
