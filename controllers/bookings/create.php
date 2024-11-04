<?php
session_start();
// Determina el creador de la reserva mediante la sesión del tipo de usuario
$creador = '';
if (isset($_SESSION['admin'])) {
    $creador = 'admin';
} elseif (isset($_SESSION['travelerUser'])) {
    $creador = 'traveler';
}
// Verifica que se haya identificado al creador
if (empty($creador)) {
    die("Error: No se pudo identificar al creador de la reserva.");
}
//
require_once __DIR__ . '/../../models/db.php';
require_once __DIR__ . '/../../models/booking.php';
require_once __DIR__ . '/../../models/traveler.php';

// Conexión BBDD
$db = db_connect();
if (!$db) {
    die("Error al conectar con la base de datos");
}

// Crear instancia de Traveler (para buscar datos del usuario)
$travelerModel = new Traveler($db);

// Función que crea un código alfanumérico aleatorio de 10 dígitos (empleada en el localizador)
function generateLocator($length = 10) {
    return substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
}

// CREACIÓN DE LA RESERVA
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //Variables
    $locator = generateLocator();
    $id_destino = $_POST['id_destino'] ?? null;
    $email_cliente = $_POST['email_cliente'];

    // Validar si el email del cliente de la reserva existe en la base de datos (tabla transfer_viajeros)
    $traveler = $travelerModel->findByEmail($email_cliente);
    if (!$traveler) {
        die("Error: El email ingresado no corresponde a ningún viajero en la base de datos.");
    }

    // Validación para asegurar que id_destino sea un id_hotel válido
    $validHotelStmt = $db->prepare("SELECT COUNT(*) FROM tranfer_hotel WHERE id_hotel = :id_destino");
    $validHotelStmt->execute([':id_destino' => $id_destino]);
    $isValidHotel = $validHotelStmt->fetchColumn() > 0;

    if (!$isValidHotel) {
        echo "Error: El id_destino no es válido. Debe ser un id_hotel existente.";
        exit;
    }

    // Si id_hotel no está definido, usa el valor de id_destino
    $id_hotel = !empty($_POST['id_hotel']) ? $_POST['id_hotel'] : $id_destino;

    // Preparar los datos para la creación
    $data = [
        'localizador' => $locator,
        'id_hotel' => $id_hotel,
        'id_tipo_reserva' => $_POST['id_tipo_reserva'],
        'email_cliente' => $email_cliente,
        'fecha_reserva' => date('Y-m-d H:i:s'),
        'fecha_modificacion' => date('Y-m-d H:i:s'),
        'id_destino' => $id_destino,
        'fecha_entrada' => $_POST['fecha_entrada'] ?? null,
        'hora_entrada' => $_POST['hora_entrada'] ?? null,
        'numero_vuelo_entrada' => $_POST['numero_vuelo_entrada'] ?? null,
        'origen_vuelo_entrada' => $_POST['origen_vuelo_entrada'] ?? null,
        'hora_vuelo_salida' => $_POST['hora_vuelo_salida'] ?? null,
        'fecha_vuelo_salida' => $_POST['fecha_vuelo_salida'] ?? null,
        'num_viajeros' => $_POST['num_viajeros'],
        'id_vehiculo' => $_POST['id_vehiculo'] ?? null,
    ];

    // Validación de id_hotel en la base de datos
    if ($isValidHotel) {
        $booking = new Booking($db);
        //
        if ($data['id_tipo_reserva'] === 'idayvuelta') {
            // Crear ambas reservas para "ida y vuelta"
            $data['id_tipo_reserva'] = 1; // Aeropuerto-Hotel
            $data['localizador'] = generateLocator(); // Genera un localizador para la primera reserva
            $booking->addBooking($data);

            $data['id_tipo_reserva'] = 2; // Hotel-Aeropuerto
            $data['localizador'] = generateLocator(); // Genera un nuevo localizador para la segunda reserva
            $booking->addBooking($data);
        } else {
            // Crea la reserva individual (aeropuerto-hotel o hotel-aeropuerto)
            $data['localizador'] = generateLocator(); // Genera un localizador único
            $booking->addBooking($data);
        }
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    } else {
        echo "Error: El id_hotel no es válido.";
    }
}
?>