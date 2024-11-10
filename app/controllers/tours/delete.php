<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../models/db.php';
require_once __DIR__ . '/../../models/tour.php';

$db = db_connect();
if (!$db) {
    $_SESSION['delete_tour_error'] = "Error al conectar con la base de datos.";
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id_excursion'])) {
    $id_excursion = $_GET['id_excursion'];
    $tour = new Tour($db); // Pasar $db al instanciar Tour

    if ($tour->deleteTour($id_excursion)) {
        $_SESSION['delete_tour_success'] = "Excursión eliminada correctamente.";
    } else {
        $_SESSION['delete_tour_error'] = "Error al intentar eliminar la excursión.";
    }

    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

?>
