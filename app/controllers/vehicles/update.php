<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../models/db.php';
require_once __DIR__ . '/../../models/vehicle.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vehicle = new Vehicle();

    // Asignar valores del POST a las propiedades del objeto $vehicle
    $vehicle->Id_vehiculo = $_POST['id_vehicle'];
    $vehicle->Descripcion = $_POST['description'];
    $vehicle->Email_conductor = $_POST['email_rider'];

    // Actualizar solo si se proporciona una nueva contraseña y hashearla
    if (!empty($_POST['pass'])) {
        $vehicle->Password = password_hash($_POST['pass'], PASSWORD_BCRYPT);
    }

    // Mensaje de éxito o error en la sesión y redirección
    if ($vehicle->updateVehicle()) {
        $_SESSION['update_vehicle_success'] = "Vehículo actualizado correctamente.";
    } else {
        $_SESSION['update_vehicle_error'] = "Error al actualizar el vehículo.";
    }

    // Redirigir a la página de origen para mostrar el mensaje
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}
?>

