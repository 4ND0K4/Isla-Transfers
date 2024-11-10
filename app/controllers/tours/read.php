<?php
require_once(__DIR__ . '/../../models/db.php');
require_once(__DIR__ . '/../../models/tour.php');

$db = db_connect();
if (!$db) {
    die("Error al conectar con la base de datos");
}

$tour = new Tour($db); // Pasar la conexión al constructor de Tour
$resultados = $tour->readAllTours();

$tours = [];
if (count($resultados) > 0) {
    foreach ($resultados as $resultado) {
        $tours[] = array(
            'id_excursion' => $resultado['id_excursion'],
            'fecha_excursion' => $resultado['fecha_excursion'],
            'hora_entrada_excursion' => $resultado['hora_entrada_excursion'],
            'hora_salida_excursion' => $resultado['hora_salida_excursion'],
            'descripcion' => $resultado['descripcion'],
            'num_excursionistas' => $resultado['num_excursionistas'],
            'email_cliente' => $resultado['email_cliente'],
            'id_hotel' => $resultado['id_hotel'],
            'id_vehiculo' => $resultado['id_vehiculo']
        );
    }
}

?>
