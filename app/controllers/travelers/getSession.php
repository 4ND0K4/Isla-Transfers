<?php
// Verificación de la sesión para la protección de acceso
if (!isset($_SESSION['travelerUser'])) {
    header("Location: /index.php");
    exit();
}

require_once(__DIR__ . '/../../models/db.php');
require_once(__DIR__ . '/../../models/traveler.php');

$db = db_connect();
if (!$db) {
    die("Error al conectar con la base de datos");
}

$travelerModel = new Traveler($db);

// Código existente que devuelve el usuario de la sesión actual
$resultado = $travelerModel->findByEmail($_SESSION['travelerUser']);

// Verificar si es una solicitud AJAX para obtener datos del viajero por email
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = $_POST['email'];
    $traveler = $travelerModel->findByEmail($email);

    if ($traveler) {
        // Devolver los datos del viajero en formato JSON
        echo json_encode([
            'id_viajero' => $traveler['id_viajero'],
            'nombre' => $traveler['nombre'],
            'apellido1' => $traveler['apellido1'],
            'apellido2' => $traveler['apellido2'],
            'direccion' => $traveler['direccion'],
            'codigoPostal' => $traveler['codigoPostal'],
            'ciudad' => $traveler['ciudad'],
            'pais' => $traveler['pais'],
            'email' => $traveler['email']
        ]);
    } else {
        // Devolver un error si no se encuentra el viajero
        echo json_encode(['error' => 'No se encontró ningún viajero con ese email']);
    }
    exit();
}

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

