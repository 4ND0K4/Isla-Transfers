<?php
// Declaraciones de inclusión
require_once __DIR__ . '/../../models/db.php';
require_once __DIR__ . '/../../models/booking.php';

// Iniciar la sesión
session_start();

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

    // Obtener los detalles de la reserva
    $reservationDetails = $booking->getBookingById($id_booking);
    if (!$reservationDetails) {
        $_SESSION['delete_error'] = "Error: La reserva no existe.";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    // Verificación de 48 horas para usuarios tipo traveler
    if (isset($_SESSION['travelerUser'])) {
        $fechaMinima = (new DateTime())->modify('+2 days')->format('Y-m-d');

        // Verificar si la fecha de entrada o la fecha de vuelo de salida están dentro de las 48 horas
        $fechaEntrada = $reservationDetails['fecha_entrada'] ?? null;
        $fechaVueloSalida = $reservationDetails['fecha_vuelo_salida'] ?? null;

        if (($fechaEntrada && $fechaEntrada < $fechaMinima) || ($fechaVueloSalida && $fechaVueloSalida < $fechaMinima)) {
            $_SESSION['delete_48_error'] = "No puede eliminar reservas con menos de 48 horas de antelación.";
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit;
        }
    }

    // Intentar eliminar la reserva con el ID proporcionado
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

