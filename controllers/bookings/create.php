<?php
session_start();
$tipo_creador_reserva = isset($_SESSION['admin']) ? 1 : 2; // 1 para admin, 2 para traveler

// Declaraciones de inclusión
require_once __DIR__ . '/../../models/db.php';
require_once __DIR__ . '/../../models/booking.php';
require_once __DIR__ . '/../../models/traveler.php';

// Conectar con la base de datos
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

// Variable que regula que el usuario traveler no pueda modificar fechas con menos de 2 días
$fechaMinima = (new DateTime())->modify('+2 days')->format('Y-m-d H:i:s');

// CREACIÓN DE LA RESERVA
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Variables
    $locator = generateLocator(); //El localizador será el código generado por la función
    $id_destino = $_POST['id_destino'] ?? null; //Se introducirá id_destino y esté será el mismo valor de id_hotel
    $email_cliente = $_POST['email_cliente']; //Se cogerá el email de transfer_viajeros -> email para identificar al usuario de la reserva. Solo permite realizar reservas con usuarios que existen en la BBDD
    $horaVueloSalida = $_POST['hora_vuelo_salida'] ?? null; //Reduce tres horas la fecha ingresada
    $fechaEntrada = $_POST['fecha_entrada'] ?? null;
    $fechaVueloSalida = $_POST['fecha_vuelo_salida'] ?? null;

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

    // Recoge la hora ingresada por el usuario desde el formulario para restarle 3 horas (hora recogida)
    if ($horaVueloSalida) {
        // Convierte la hora ingresada a un objeto DateTime
        $horaVueloSalidaObj = new DateTime($horaVueloSalida);

        // Resta tres horas
        $horaVueloSalidaObj->modify('-3 hours');

        // Obtén el valor modificado en formato de hora (HH:MM:SS)
        $horaVueloSalida = $horaVueloSalidaObj->format('H:i:s');
    }

    /* Previamente se ha Verificado si el email existe en la tabla y obtener el tipo de usuario */

    // Validación para los usuarios tipo traveler
    if (isset($_SESSION['travelerUser'])) {
        $fechaMinima = (new DateTime())->modify('+2 days')->format('Y-m-d');

        // Validar fecha_entrada
        if ($fechaEntrada && $fechaEntrada < $fechaMinima) {
            die("Error: Los usuarios traveler solo pueden hacer reservas con fecha de entrada a partir de 48 horas después del día actual.");
        }

        // Validar fecha_vuelo_salida
        if ($fechaVueloSalida && $fechaVueloSalida < $fechaMinima) {
            die("Error: Los usuarios traveler solo pueden hacer reservas con fecha de vuelo de salida a partir de 48 horas después del día actual.");
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
        'tipo_creador_reserva' => $tipo_creador_reserva // Nuevo campo
    ];

    // Validación de id_hotel en la base de datos antes de crear la/s reserva/s y genera lcoalizadores únicos
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