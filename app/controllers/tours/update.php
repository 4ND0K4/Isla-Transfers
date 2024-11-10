<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../models/db.php';
require_once __DIR__ . '/../../models/tour.php';

$db = db_connect();
if (!$db) {
    $_SESSION['update_tour_error'] = "Error al conectar con la base de datos.";
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

$tour = new Tour($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Asignar valores del POST a las propiedades del objeto $tour
    $tour->Id_excursion = $_POST['id_excursion'];
    $tour->Fecha_excursion = $_POST['fecha_excursion'];
    $tour->Hora_entrada_excursion = $_POST['hora_entrada_excursion'];
    $tour->Hora_salida_excursion = $_POST['hora_salida_excursion'];
    $tour->Descripcion = $_POST['descripcion'];
    $tour->Num_excursionistas = $_POST['num_excursionistas'];
    $tour->Email_cliente = $_POST['email_cliente'];
    $tour->Id_vehiculo = !empty($_POST['id_vehiculo']) ? $_POST['id_vehiculo'] : 1; // Valor predeterminado a 1 si no se especifica

    // Validar que el Id_hotel existe en la tabla transfer_hotel
    $id_hotel = $_POST['id_hotel'];
    $query = 'SELECT COUNT(*) FROM tranfer_hotel WHERE Id_hotel = :id_hotel';
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id_hotel', $id_hotel, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->fetchColumn() > 0) {
        $tour->Id_hotel = $id_hotel; // Asigna el Id_hotel si existe
    } else {
        $_SESSION['update_tour_error'] = "El hotel seleccionado no existe.";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }
   
    // Mensaje de éxito o error en la sesión y redirección
    if ($tour->updateTour()) {
        $_SESSION['update_tour_success'] = "Excursión actualizada correctamente.";
    } else {
        $_SESSION['update_tour_error'] = "Error al actualizar la excursión.";
    }

    // Redirigir a la página de origen para mostrar el mensaje
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

?>
