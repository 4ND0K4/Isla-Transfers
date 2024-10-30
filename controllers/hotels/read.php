<?php
require_once(__DIR__ . '/../../models/db.php');
require_once(__DIR__ . '/../../models/hotel.php');


$db = db_connect();
$hotels = [];

if (!$db) {
    die("Error al conectar con la base de datos");
}

$hotel = new Hotel($db);

$resultados = $hotel->readAllHotels();

if(count($resultados)> 0){
    foreach($resultados as $resultado){
        $hotels[] = array(
            'idHotel' => $resultado['id_hotel'],
            'idZone' => $resultado['id_zona'],
            'commission' => $resultado['Comision'],
            'user' => $resultado['usuario'],
            'pass' => $resultado['password']
        );
    }
}

?>


