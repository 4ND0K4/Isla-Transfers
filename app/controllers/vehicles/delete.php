<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../models/vehicle.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id_vehicle'])) {
    $id_vehicle = $_GET['id_vehicle'];
    $vehicle = new Vehicle();
    
    // Intenta eliminar el vehículo y establece el mensaje correspondiente
    if ($vehicle->deleteVehicle($id_vehicle)) {
        $_SESSION['delete_vehicle_success'] = "Vehículo eliminado correctamente.";
    } else {
        $_SESSION['delete_vehicle_error'] = "Error al intentar eliminar el vehículo.";
    }

    // Redirigir a la página de origen para mostrar el mensaje
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}
?>
