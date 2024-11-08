<?php
session_start();
?>
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
                <img src="../assets/img/logo.png" alt="Palmera con sol de fondo" width="30" height="24" class="d-inline-block align-text-top">
                Isla Transfer
            </a>
            <ul class="nav nav-pills justify-content-end">
                <li>
                    <button type="button" class="btn btn-primary border-0 bg-transparent fs-5 fw-bold text-success" data-bs-toggle="modal" data-bs-target="#logpanels">Iniciar sesión</button>
                </li>
            </ul>
        </div>
    </nav>
    <!-- BLOQUE PRINCIPAL -->
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <!-- Imagen decorativa -->
            <div class="col-xl-8">
                <img src="../assets/img/login-hotel.jpg" alt="Hamaca en la piscina de un hotel resort con mar de fondo." class="logo img-fluid mb-4 mb-md-0 w-75">
            </div>
            <div class="col-xl-4">
                <!-- Título -->
                <h1 class="fw-bold display-5 pb-5">Tu hotel en Isla Transfer</h1>
                <!-- Subtítulo -->
                <h2 class="fs-5 pb-3">Introduce tus credenciales</h2>
                <form action="../controllers/hotels/login.php" method="post">
                    <!-- Campo de usuario -->
                    <div class="mb-3">
                        <label for="username" class="form-label text-success fw-bold">Usuario</label>
                        <input type="text" class="form-control w-75" id="username" name="username" placeholder="Introduce el usuario" required>
                    </div>
                    <!-- Password -->
                    <div class="mb-3">
                        <label for="pass" class="form-label text-success fw-bold">Contraseña</label>
                        <input type="password" class="form-control w-75" id="pass" name="pass" placeholder="Introduce el password" required>
                    </div>
                    <!-- Botón -->
                    <div class="d-grid gap-2 w-75">
                        <button type="submit" class="btn btn-success text-white">Acceder</button>
                    </div>
                    <!-- Mensaje de error al loguearte -->
                    <div class="d-grid gap-2 w-75">
                        <?php if (isset($_SESSION['login_error'])) : ?>
                            <div class="alert alert-danger" role="alert" id="loginError">
                                <?php echo $_SESSION['login_error']; ?>
                                <?php unset($_SESSION['login_error']); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </form>
                <!-- Separador -->
                <div class="d-grid gap-2 w-75">
                    <hr>
                </div>
                <!-- Botón (sin acción) para acceder a un formulario de inscripción -->
                <div class="d-grid gap-2 w-75">
                    <button type="button" class="btn btn-warning text-white" data-bs-toggle="modal" data-bs-target="#">Formulario de Inscripción</button>
                </div>
            </div>
        </div>
    </div>

<!-- ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////// MODALS //////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->

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
                            <!-- Enlace a viajeros -->
                            <div class="col-xl-5  bg-warning-subtle vh-50 vw-50 p-5">
                                <a href="login-traveler.php" class="fs-2 text-decoration-none text-secondary">Inicia sesión para viajeros</a>
                            </div>
                            <!-- Enlace a administradores -->
                            <div class="col-xl-5 bg-secondary-subtle vh-50 vw-50 p-5">
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

<!-- ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////// EVENTS //////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->

<script>
    // Temporizador para mensaje de error de login
    setTimeout(function() {
        var errorDiv = document.getElementById("loginError");
        if (errorDiv) {
            // Animación desvanecimiento mensaje
            errorDiv.style.transition = "opacity 0.5s";
            errorDiv.style.opacity = "0";
            setTimeout(function() {
                errorDiv.style.display = "none";
            }, 500); // Tiempo transición en ocultar
        }
    }, 3000); // Tiempo de duración
</script>
<!-- Archivos para accionar los modales -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</body>
</html>