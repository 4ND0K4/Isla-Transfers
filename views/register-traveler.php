<?php include 'modal.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Registro de Viajeros</title>
    <meta name="author" content="PHPOWER" />
    <meta name="description" content="La página de registro de los viajeros de Isla Transfer.
    Hay un formulario con los campos necesarios para registrar este tipo de usuario." />
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+Paaji+2:wght@400..800&family=Caveat&family=Roboto+Flex:opsz@8..144&display=swap" rel="stylesheet">
    <!-- Enlaces -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Enlaces Hojas Estilo -->
    <link rel="stylesheet" href="../assets/css/general.css">
    <link rel="stylesheet" href="../assets/css/traveler.css">
</head>
<body>
<nav class="navbar navbar-expand-xl bg-light">
    <div class="container-fluid">
        <a class="navbar-brand fs-4 ps-5" href="login-traveler.php" id="logo">
            <img src="../assets/img/logo.png" alt="" width="30" height="24" class="d-inline-block align-text-top">
            Isla Transfer
        </a>
        <ul class="nav nav-pills justify-content-end">
            <li class="nav-item">
                <a href="login-traveler.php" class="text-decoration-none fs-5 fw-bold text-warning">Iniciar sesión</a>
            </li>
        </ul>
    </div>
</nav>
<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="col-md-4">
        <h1 class="text-center text-secondary mb-3">Panel de registro</h1>

        <form class="w-100" role="form" id="myform" action="../controllers/travelers/register.php" method="POST">

            <div>
                <input type="hidden" id="floatingInput" name="id_traveler">
            </div>

            <div class="mb-1">
                <label class="form-label text-warning">Nombre</label>
                <input class="form-control" type="text" name="name" placeholder="Introduce tu nombre" required>
            </div>

            <div class="mb-3">
                <label class="form-label text-warning">Apellido1</label>
                <input class="form-control" type="text" name="surname1" placeholder="Introduce tu primer apellido" required>
            </div>

            <div class="mb-3">
                <label class="form-label text-warning">Apellido2</label>
                <input class="form-control" type="text" name="surname2" placeholder="Introduce tu segundo apellido" required>
            </div>

            <div class="mb-3">
                <label class="form-label text-warning">Email</label>
                <input class="form-control" type="email" name="email" placeholder="Introduce tu email" required>
            </div>

            <div class="mb-3">
                <label class="form-label text-warning">Password</label>
                <input class="form-control" type="password" name="password" placeholder="Introduce tu contraseña" required>
            </div>

            <!-- CAMPOS PARA LA ACTUALIZACIÓN

            <div class="mb-3">
                <label class="form-label">Dirección</label>
                <input class="form-control" type="text" name="address" placeholder="Introduce tu dirección aquí" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Código Postal</label>
                <input class="form-control" type="text" name="zipCode" placeholder="Introduce tu código postal" required>
            </div>


            <div class="mb-3">
                <label class="form-label">Ciudad</label>
                <input class="form-control" type="text" name="city" placeholder="Introduce tu ciudad" required>
            </div>

            <div class="mb-3">
                <label class="form-label">País</label>
                <input class="form-control" type="text" name="country" placeholder="Introduce tu país" required>
            </div>

           -->

            <div class="d-grid gap-2">
                <button class="btn btn-warning" type="submit" value="Registrarse"> Continuar </button>
            </div>
        </form>
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger" role="alert">
                Error al registrarse. Por favor, complete los campos.
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success" role="alert">
                Registro exitoso. Por favor, inicie sesión.
            </div>
        <?php endif; ?>

    </div>
</div>
<!-- ARCHIVOS FINALES PARA QUE FUNCIONEN MODALES -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</body>
</html>