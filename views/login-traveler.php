<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Login de Viajeros</title>
    <meta name="author" content="PHPOWER" />
    <meta name="description" content="La página de login de los viajeros de Isla Transfer.
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
    <link rel="stylesheet" href="../assets/css/traveler.css">
</head>
<body>
<!-- NAV -->
<nav class="navbar navbar-expand-xl bg-light px-3">
    <div class="container-fluid">
        <a class="navbar-brand fs-4" href="#" id="logo">
            <img src="../assets/img/logo.png" alt="" width="30" height="24" class="d-inline-block align-text-top">
            Isla Transfer
        </a>
        <ul class="nav nav-pills justify-content-end">
            <li class="nav-item">
                <button type="button" class="btn btn-primary bg-transparent border-0 fs-5 fw-bold text-warning" data-bs-toggle="modal" data-bs-target="#logpanels">Iniciar sesión</button>
            </li>
        </ul>
    </div>
</nav>
<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-xl-6">
            <h1 class="fw-bold display-5 pb-5 w-75">Reserva tu trayecto con Isla Transfers</h1>
            <p class="fs-5">¡Introduce tus credenciales para comenzar el viaje!</p>
            <form action="/index.php?user_type=travelerUser&action=login" method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label text-warning fw-bold">Correo electrónico</label>
                    <input type="email" class="form-control w-75" name="email" id="email"  placeholder="Introduce el email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label text-warning fw-bold">Contraseña</label>
                    <input type="password" class="form-control w-75" name="password" id="password" placeholder="Introduce el password" required>
                </div>

                <div class="d-grid gap-2 w-75">
                    <button type="submit" class="btn btn-warning">Acceder</button>
                </div>

                <hr>
                <div class="d-grid gap-2 w-75 bg-success">
                    <a href="register-traveler.php" class="btn btn-link text-white text-decoration-none bg-opacity-50">Registrarse</a>
                </div>
                <?php if (isset($_SESSION['login_error'])) : ?>
                    <div class="alert alert-danger w-75" role="alert">
                        <?php echo $_SESSION['login_error']; ?>
                        <?php unset($_SESSION['login_error']); ?>
                    </div>
                <?php endif; ?>
            </form>
            <!-- Mensaje de éxito si se ha creado un cliente particular-->
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success" role="alert">
                    Registro exitoso. Por favor, inicie sesión.
                </div>
            <?php endif; ?>
        </div>

        <div class="col-xl-6">
            <img src="../assets/img/login-traveler.jpg" alt="Imagen de login" class="logo img-fluid mb-4 mb-md-0 w-75">
        </div>
    </div>
</div>

<!-- MODAL INICIO SESIÓN-->
<div class="modal fade" id="logpanels" tabindex="-1" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-light-subtle">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Modal Body -->
            <div class="modal-body bg-light-subtle">
                <div class="container-fluid">
                    <div class="row justify-content-around">
                        <div class="col-xl-5  bg-success-subtle vh-50 vw-50 p-5">
                            <a href="login-hotel.php" class="fs-2 text-decoration-none text-secondary">Inicia sesión para hoteles</a>
                        </div>
                        <div class="col-xl-5 bg-info-subtle vh-50 vw-50 p-5">
                            <a href="login-admin.php" class="fs-2 text-decoration-none text-secondary">Inicia sesión para admins</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer bg-light-subtle"></div>
        </div>
    </div>
</div>
<!-- ARCHIVOS FINALES PARA QUE FUNCIONEN MODALES -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
