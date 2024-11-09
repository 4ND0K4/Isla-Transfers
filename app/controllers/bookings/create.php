<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$tipo_creador_reserva = isset($_SESSION['admin']) ? 1 : 2; // 1 para admin, 2 para traveler

// Declaraciones de inclusión
require_once __DIR__ . '/../../models/db.php';
require_once __DIR__ . '/../../models/booking.php';
require_once __DIR__ . '/../../models/traveler.php';

// Conectar con la base de datos
$db = db_connect();
if (!$db) {
    $_SESSION['create_error'] = "Error al conectar con la base de datos.";
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

// Crear instancia de Traveler (para buscar datos del usuario)
$travelerModel = new Traveler($db);

// Función que crea un código alfanumérico aleatorio de 10 dígitos (empleada en el localizador)
function generateLocator($length = 10) {
    return substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
}

// Variable que regula que el usuario traveler no pueda modificar fechas con menos de 2 días
$fechaMinima = (new DateTime())->modify('+2 days')->format('Y-m-d H:i:s');

// Verificar si la solicitud es de tipo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $locator = generateLocator();
    $id_destino = $_POST['id_destino'] ?? null;
    $email_cliente = $_POST['email_cliente'];
    $horaVueloSalida = $_POST['hora_vuelo_salida'] ?? null;
    $fechaEntrada = $_POST['fecha_entrada'] ?? null;
    $fechaVueloSalida = $_POST['fecha_vuelo_salida'] ?? null;

    // Validar si el email del cliente de la reserva existe en la base de datos
    $traveler = $travelerModel->findByEmail($email_cliente);
    if (!$traveler) {
        $_SESSION['create_error'] = "Error: El email ingresado no corresponde a ningún viajero en la base de datos.";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    // Validación para asegurar que id_destino sea un id_hotel válido
    $validHotelStmt = $db->prepare("SELECT COUNT(*) FROM tranfer_hotel WHERE id_hotel = :id_destino");
    $validHotelStmt->execute([':id_destino' => $id_destino]);
    $isValidHotel = $validHotelStmt->fetchColumn() > 0;

    if (!$isValidHotel) {
        $_SESSION['create_error'] = "Error: El id_destino no es válido. Debe ser un id_hotel existente.";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    // Si id_hotel no está definido, usa el valor de id_destino
    $id_hotel = !empty($_POST['id_hotel']) ? $_POST['id_hotel'] : $id_destino;

    // Resta tres horas a la hora de vuelo de salida, si está definida
    if ($horaVueloSalida) {
        $horaVueloSalidaObj = new DateTime($horaVueloSalida);
        $horaVueloSalidaObj->modify('-3 hours');
        $horaVueloSalida = $horaVueloSalidaObj->format('H:i:s');
    }

    // Validación para los usuarios tipo traveler no puedan crear reservas con menos de 48 horas
    if (isset($_SESSION['travelerUser'])) {
        $fechaMinima = (new DateTime())->modify('+2 days')->format('Y-m-d');
        if ($fechaEntrada && $fechaEntrada < $fechaMinima) {
            $_SESSION['create_48_error'] = "No puede realizar reservas con menos de 48 horas de antelación.";
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit;
        }
        if ($fechaVueloSalida && $fechaVueloSalida < $fechaMinima) {
            $_SESSION['create_48_error'] = "No puede realizar reservas con menos de 48 horas de antelación.";
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit;
        }
    }

    // Preparar los datos para la creación
    $data = [
        'localizador' => $locator,
        'id_hotel' => $id_hotel,
        'id_tipo_reserva' => $_POST['id_tipo_reserva'],
        'email_cliente' => $email_cliente,
        'fecha_reserva' => date('Y-m-d H:i:s'),
        'fecha_modificacion' => date('Y-m-d H:i:s'),
        'id_destino' => $id_destino,
        'fecha_entrada' => $fechaEntrada,
        'hora_entrada' => $_POST['hora_entrada'] ?? null,
        'numero_vuelo_entrada' => $_POST['numero_vuelo_entrada'] ?? null,
        'origen_vuelo_entrada' => $_POST['origen_vuelo_entrada'] ?? null,
        'hora_vuelo_salida' => $horaVueloSalida ?? null,
        'fecha_vuelo_salida' => $fechaVueloSalida,
        'num_viajeros' => $_POST['num_viajeros'],
        'id_vehiculo' => $_POST['id_vehiculo'] ?? 1,
        'tipo_creador_reserva' => $tipo_creador_reserva ?? null
    ];

    // Crear la reserva
    $booking = new Booking($db);
    $createResult = false;

    if ($data['id_tipo_reserva'] === 'idayvuelta') {
        $data['id_tipo_reserva'] = 1;
        $data['localizador'] = generateLocator();
        $createResult = $booking->addBooking($data);

        $data['id_tipo_reserva'] = 2;
        $data['localizador'] = generateLocator();
        $createResult = $createResult && $booking->addBooking($data);
    } else {
        $data['localizador'] = generateLocator();
        $createResult = $booking->addBooking($data);
    }

    if ($createResult) {
        $_SESSION['create_booking_success'] = "Reserva creada correctamente.";
    } else {
        $_SESSION['create_booking_error'] = "Error al crear la reserva.";
    }

    // Redirigir a la página de origen para mostrar el mensaje
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}
?>
