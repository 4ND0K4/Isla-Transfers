<?php
require_once __DIR__ . '/../../models/db.php';
require_once __DIR__ . '/../../models/vehicle.php';

$db = db_connect();
if(!$db) {
    die("Error al conectar con la base de datos");
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
        header('Location: /views/vehicle.php');
    } else {
        echo "Error al crear la reserva";
    }
}

?>
