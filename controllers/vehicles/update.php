<?php
require_once __DIR__ . '/../../models/db.php';
require_once __DIR__ . '/../../models/vehicle.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vehicle = new Vehicle();

    // Asignar valores del POST a las propiedades del objeto $acto
    $vehicle->Id_vehiculo = $_POST['id_vehicle'];
    $vehicle->Descripcion = $_POST['description'];
    $vehicle->Email_conductor = $_POST['email_rider'];
    $vehicle->Password = $_POST['pass'];


    if ($vehicle->updateVehicle()) {
        header('Location: /views/vehicle.php');
    } else {
        echo "Error al actualizar el vehiculo";
    }
}
?>

