<?php
require_once __DIR__ . '/../../models/db.php';
require_once __DIR__ . '/../../models/booking.php';

$db = db_connect();
if (!$db) {
    die("Error al conectar con la base de datos");
}

//Función que crea un código alfanumérico aleatorio de 10 dígitos
function generateLocator($length = 10) {
    return substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $locator = generateLocator();

    $data = [
        'localizador' => $locator,
        'id_hotel' => $_POST['id_hotel'],
        'id_tipo_reserva' => $_POST['id_tipo_reserva'],
        'email_cliente' => $_POST['email_cliente'],
        'fecha_reserva' => date('Y-m-d H:i:s'),  // Fecha y hora actual
        'fecha_modificacion' => date('Y-m-d H:i:s'),  // Fecha y hora actual
        'id_destino' => $_POST['id_destino'] ?? null,
        'fecha_entrada' => $_POST['fecha_entrada'] ?? null,
        'hora_entrada' => $_POST['hora_entrada'] ?? null,
        'numero_vuelo_entrada' => $_POST['numero_vuelo_entrada'] ?? null,
        'origen_vuelo_entrada' => $_POST['origen_vuelo_entrada'] ?? null,
        'hora_vuelo_salida' => $_POST['hora_vuelo_salida'] ?? null,
        'fecha_vuelo_salida' => $_POST['fecha_vuelo_salida'] ?? null,
        'num_viajeros' => $_POST['num_viajeros'],
        'id_vehiculo' => $_POST['id_vehiculo'] ?? null
    ];

    // Registra los datos para diagnóstico en caso de error
    error_log(print_r($data, true));

    // Validación de id_hotel e id_destino en la base de datos
    $hotelStmt = $db->prepare("SELECT COUNT(*) FROM tranfer_hotel WHERE Id_hotel = :id_hotel");
    $hotelStmt->execute([':id_hotel' => $data['id_hotel']]);

    $destinoStmt = $db->prepare("SELECT COUNT(*) FROM tranfer_hotel WHERE Id_hotel = :id_destino");
    $destinoStmt->execute([':id_destino' => $data['id_destino']]);

    if ($hotelStmt->fetchColumn() && $destinoStmt->fetchColumn()) {
        $booking = new Booking($db);

        if ($_POST['id_tipo_reserva'] === 'idayvuelta') {
            // Crear ambas reservas para "ida y vuelta"
            $data['id_tipo_reserva'] = 1; // Aeropuerto-Hotel
            $booking->addBooking($data);

            $data['id_tipo_reserva'] = 2; // Hotel-Aeropuerto
            $booking->addBooking($data);
        } else {
            $booking->addBooking($data);
        }

        header("Location: /views/booking.php");  // Ajusta la ruta según tu estructura
        exit;
    } else {
        echo "Error: El id_hotel o id_destino no son válidos.";
    }
}
?>
