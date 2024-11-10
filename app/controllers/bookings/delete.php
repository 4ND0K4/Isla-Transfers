<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Declaraciones de inclusión
require_once __DIR__ . '/../../models/db.php';
require_once __DIR__ . '/../../models/booking.php';

// Verificar que la solicitud sea GET y que se haya pasado un ID de reserva
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id_booking'])) {
    $id_booking = $_GET['id_booking']; // Capturar el ID de la reserva desde los parámetros de la URL

    // Conectar a la base de datos
    $db = db_connect();
    if (!$db) {
        $_SESSION['delete_error'] = "Error al conectar con la base de datos.";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    // Crear instancia del modelo Booking
    $booking = new Booking($db);

    // Obtener detalles de la reserva
    $stmt = $db->prepare("SELECT id_tipo_reserva, fecha_entrada, fecha_vuelo_salida FROM transfer_reservas WHERE id_reserva = :id_reserva");
    $stmt->execute([':id_reserva' => $id_booking]);
    $reservaData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$reservaData) {
        $_SESSION['delete_error'] = "Error: La reserva no existe.";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    // Si el usuario es traveler, aplicar la restricción de 48 horas
    if (isset($_SESSION['travelerUser'])) {
        $fechaMinima = (new DateTime())->modify('+2 days')->format('Y-m-d H:i:s');

        // Verificar si la reserva está dentro del período restringido de 48 horas
        if (($reservaData['id_tipo_reserva'] == 1 && $reservaData['fecha_entrada'] < $fechaMinima) ||
            ($reservaData['id_tipo_reserva'] == 2 && $reservaData['fecha_vuelo_salida'] < $fechaMinima)) {
            $_SESSION['delete_48_error'] = "No puede eliminar reservas con menos de 48 horas de antelación.";
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit;
        }
    }

    // Intentar eliminar la reserva si no está en el período restringido o si el usuario es admin
    if ($booking->deleteBooking($id_booking)) {
        $_SESSION['delete_booking_success'] = "Reserva eliminada correctamente.";
    } else {
        $_SESSION['delete_booking_error'] = "Error al borrar la reserva.";
    }

    // Redirigir a la página de origen para mostrar el mensaje
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}
?>
