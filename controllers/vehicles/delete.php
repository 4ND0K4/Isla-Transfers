<?php
require_once __DIR__ . '/../../models/vehicle.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id_vehicle'])) {
    $id_vehicle = $_GET['id_vehicle'];
    $vehicle = new Vehicle();
    if ($vehicle->deleteVehicle($id_vehicle)) {
        header('Location: /views/vehicle.php');
        exit;
    } else {
        echo "Error al borrar el acto";
    }
}

?>