<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../models/db.php';
require_once __DIR__ . '/../../models/vehicle.php';

$db = db_connect();
if(!$db) {
    $_SESSION['create_vehicle_error'] = "Error al conectar con la base de datos.";
    header('Location: /views/vehicle.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Instancia del objeto
    $vehicle = new Vehicle();

    // Obtiene y asigna los valores
    $vehicle->Id_vehiculo = $_POST['id_vehicle'];
    $vehicle->Descripcion = $_POST['description'];
    $vehicle->Email_conductor = $_POST['email_rider'];

    // Hashea la contraseña antes de asignarla
    $vehicle->Password = password_hash($_POST['pass'], PASSWORD_BCRYPT);

    // Agrega el vehículo
    if ($vehicle->addVehicle()) {
        $_SESSION['create_vehicle_success'] = "Vehículo creado correctamente.";
    } else {
        $_SESSION['create_vehicle_error'] = "Error al crear el vehículo.";
    }

    // Redirigir a la página de origen para mostrar el mensaje
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}
?>

