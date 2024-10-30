<?php
require_once __DIR__ . '/../../models/db.php';
require_once __DIR__ . '/../../models/vehicle.php';

$db = db_connect();
if(!$db) {
    die("Error al conectar con la base de datos");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    //Se instancia el objeto
    $vehicle = new Vehicle();
    //Se obtienen los valores
    $vehicle->Id_vehiculo = $_POST['id_vehicle'];
    $vehicle->Descripcion = $_POST['description'];
    $vehicle->Email_conductor = $_POST['email_rider'];
    $vehicle->Password = $_POST['pass'];


    if ($vehicle->addVehicle()) {
        header('Location: /views/vehicle.php');
    } else {
        echo "Error al crear la reserva";
    }
}
?>
