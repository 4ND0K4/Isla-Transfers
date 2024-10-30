<?php
require_once(__DIR__ . '/../../models/db.php');
require_once(__DIR__ . '/../../models/vehicle.php');


$db = db_connect();
$vehicles = [];

if (!$db) {
    die("Error al conectar con la base de datos");
}

$vehicle = new Vehicle();

$resultados = $vehicle->readAllVehicles();

if(count($resultados)> 0){
    foreach($resultados as $resultado){
        $vehicles[] = array(
            'id_vehicle' => $resultado['id_vehiculo'],
            'description' => $resultado['descripcion'],
            'email_rider' => $resultado['email_conductor'],
            'pass' => $resultado['password']
        );
    }
}

?>
