<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../models/db.php';
require_once __DIR__ . '/../../models/tour.php';

$db = db_connect();
if (!$db) {
    $_SESSION['create_tour_error'] = "Error al conectar con la base de datos.";
    header('Location: /views/tour.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Instancia del objeto pasando la conexión $db
    $tour = new Tour($db);

    // Asignación de valores y validación
    $tour->Fecha_excursion = $_POST['fecha_excursion'];
    $tour->Hora_entrada_excursion = $_POST['hora_entrada_excursion'];
    $tour->Hora_salida_excursion = $_POST['hora_salida_excursion'];
    $tour->Descripcion = $_POST['descripcion'];
    $tour->Num_excursionistas = $_POST['num_excursionistas'];
    $tour->Email_cliente = $_POST['email_cliente'];
    $tour->Id_vehiculo = 1; // Valor predeterminado para Id_vehiculo

    $id_hotel = $_POST['id_hotel'];
    $validHotelStmt = $db->prepare("SELECT COUNT(*) FROM tranfer_hotel WHERE Id_hotel = :id_hotel");
    $validHotelStmt->execute([':id_hotel' => $id_hotel]);
    $isValidHotel = $validHotelStmt->fetchColumn() > 0;

    if (!$isValidHotel) {
        $_SESSION['create_tour_error'] = "El hotel ingresado no corresponde a ningún hotel en la base de datos.";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    $tour->Id_hotel = $id_hotel;

    if ($tour->addTour()) {
        $_SESSION['create_tour_success'] = "Excursión creada correctamente.";
    } else {
        $_SESSION['create_tour_error'] = "Error al crear la excursión.";
    }

    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

?>
