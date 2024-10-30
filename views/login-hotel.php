<?php include 'modal.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Login de Viajeros</title>
    <meta name="author" content="PHPOWER" />
    <meta name="description" content="La página de login de los clientes corporativos (Hoteles) de Isla Transfer.
    Hay un formulario con los campos necesarios para loguearte." />
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
<!-- NAV -->
<nav class="navbar navbar-expand-xl bg-light px-3">
    <div class="container-fluid">
        <a class="navbar-brand fs-4" href="login-hotel.php" id="logo">
            <img src="../assets/img/logo.png" alt="" width="30" height="24" class="d-inline-block align-text-top">
            Isla Transfer
        </a>
        <ul class="nav nav-pills justify-content-end">
            <li>
                <button type="button" class="btn btn-primary border-0 bg-transparent fs-5 fw-bold text-success" data-bs-toggle="modal" data-bs-target="#logpanels">Iniciar sesión</button>
            </li>
        </ul>
    </div>
</nav>
<!-- PRIMER BLOQUE -->
<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-xl-8">
            <img src="../assets/img/login-hotel.jpg" alt="Imagen de login de un hotel" class="logo img-fluid mb-4 mb-md-0 w-75">
        </div>
        <div class="col-xl-4">
            <h1 class="fw-bold display-5 pb-5">Tu hotel en Isla Transfer</h1>
            <p class="fs-5">Introduce tus credenciales</p>
            <form action="../controllers/hotels/login.php" method="post">
                <div class="mb-3">
                    <label for="username" class="form-label text-success fw-bold">Usuario</label>
                    <input type="text" class="form-control w-75" id="username" name="username" placeholder="Introduce el usuario" required>
                </div>
                <div class="mb-3">
                    <label for="pass" class="form-label text-success fw-bold">Contraseña</label>
                    <input type="password" class="form-control w-75" id="pass" name="pass" placeholder="Introduce el password" required>
                </div>
                <div class="d-grid gap-2 w-75">
                    <button type="submit" class="btn btn-success text-white">Acceder</button>
                </div>
                <?php if (isset($_SESSION['login_error'])) : ?>
                    <div class="alert alert-danger w-75" role="alert">
                        <?php echo $_SESSION['login_error']; ?>
                        <?php unset($_SESSION['login_error']); ?>
                    </div>
                <?php endif; ?>
            </form>
            <hr>
            <div class="d-grid gap-2 w-75">
                <button type="button" class="btn btn-warning text-white" data-bs-toggle="modal" data-bs-target="#">Formulario de Inscripción</button></div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
