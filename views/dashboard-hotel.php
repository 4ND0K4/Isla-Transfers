<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Login de Viajeros</title>
    <meta name="author" content="PHPOWER" />
    <meta name="description" content="La página de Panel principal de los clientes corporativos (Hoteles) de Isla Transfer.
    Para acceder a este panel el cliente debe estar logueado." />
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+Paaji+2:wght@400..800&family=Caveat&family=Roboto+Flex:opsz@8..144&display=swap" rel="stylesheet">
    <!-- Enlaces CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Enlaces Hojas Estilo -->
    <link rel="stylesheet" href="../assets/css/general.css">
    <link rel="stylesheet" href="../assets/css/hotel.css">
</head>
<body>
<nav class="navbar navbar-expand-xl bg-transparent">
    <div class="container-fluid">
        <a class="navbar-brand fs-4 ps-5" href="dashboard-hotel.php" id="logo">
            <img src="../assets/img/logo.png" alt="Palmera con sol de fondo" width="30" height="24" class="d-inline-block align-text-top">
            Isla Transfer
        </a>
        <ul class="nav nav-pills justify-content-end">
            <li class="nav-item">
                <!-- BOTÓN ACTUALIZAR -->
                <button class="btn btn-primary bg-transparent border-0 fs-5 fw-bold text-secondary"><i class="bi bi-person-gear px-2 text-secondary"></i>Perfil</button>
            </li>
            <li class="pt-2">
                <!--  -->
                <a href="../controllers/hotels/login.php?action=logout" class="fs-5 pt-3 text-decoration-none text-danger"><i class="bi bi-plug"></i>Cerrar sesión</a>
            </li>
        </ul>
    </div>
</nav>
<div class="container">
    <div class="justify-content-center align-items-center">
        <div class="col-xl">
            <h1 class="text-center fw-bold display-5 pb-5">Panel de clientes corporativos (Hoteles)</h1>
        </div>
        <div class="col-xl text-center">
            <img src="../assets/img/dashboard-hotel.png" alt="Imagen de una web en construcción" class="logo img-fluid mb-4 mb-md-0 w-75">
        </div>
    </div>
</div>
</body>
</html>

