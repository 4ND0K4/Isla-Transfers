<?php
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

    if ($vehicle->updateVehicle()) {
        header('Location: /views/vehicle.php');
    } else {
        echo "Error al actualizar el vehiculo";
    }
}
?>
