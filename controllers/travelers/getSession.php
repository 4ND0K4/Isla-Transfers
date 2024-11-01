<?php
if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header("Location: /index.php");
    exit();
}

require_once(__DIR__ . '/../../models/db.php');
require_once(__DIR__ . '/../../models/traveler.php');

$db = db_connect();
$travelers = [];

if (!$db) {
    die("Error al conectar con la base de datos");
}

$traveler = new Traveler($db);

// Solo devuelve el usuario de la sesión actual
$resultado = $traveler->findByEmail($_SESSION['user']);

if ($resultado) {
    $travelerData = array(
        'id_traveler' => $resultado['id_viajero'],
        'name' => $resultado['nombre'],
        'surname1' => $resultado['apellido1'],
        'surname2' => $resultado['apellido2'],
        'address' => $resultado['direccion'],
        'zipCode' => $resultado['codigoPostal'],
        'city' => $resultado['ciudad'],
        'country' => $resultado['pais'],
        'email' => $resultado['email'],
        'password' => $resultado['password']
    );
} else {
    die("Error: No se encontró el usuario en la base de datos.");
}
