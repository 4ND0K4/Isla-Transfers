<?php
session_start();
if (!isset($_SESSION['is_admin_logged_in']) || !$_SESSION['is_admin_logged_in']) {
    header("Location: /views/login-admin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Panel de Administración</title>
    <meta name="author" content="PHPOWER" />
    <meta name="description" content="La página de inicio del panel de administración de Isla Transfer
    es accesible cuando el administrador se identifica con sus credenciales. Desde aquí se puede acceder
    a la gestión de todas las acciones disponibles en la aplicación web" />
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+Paaji+2:wght@400..800&family=Caveat&family=Roboto+Flex:opsz@8..144&display=swap" rel="stylesheet">
    <!-- Enlaces CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Enlaces Hojas Estilo -->
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <!-- FullCalendar.io -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script>
        //var addBookingModal;
        //addBookingModal=new bootstrap.Modal( document.getElementById('addBookingModal'),{ keyboard:false } ); //Seleccionamos bottstrapmodal haga referencia al ID del modal declarado y las opciones que al presionar tecla no haga reacción
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale:"es", //Hace que pase de calendario sajón a católico apostólico
                headerToolbar:{ //el header se divide en left, center, right
                    left:'prev,next today', //elementos prev next a la izq
                    center:'title', //nombre de mes, semana, día
                    right:'dayGridMonth,timeGridWeek,timeGridDay' //navegación por mes, semana y día
                },
                dateClick:function(informacion){
                    alert("Reserva: " + info.reserva.id_reserva + "\n" + info.reserva.extendedProps.email_cliente);
                    //alert("Pinchaste "+informacion.dateStr); //Nos da la informacion dle dia pulsado en el calendario
                    //addBookingModal.show(); //Hace que aparezca el modal
                },
                events:'../controllers/reservas/leerCalendar.php' //consultar datos y mostrarlos en el calendario
            });
            calendar.render();
        });
    </script>

</head>
<body>
<!-- MENU START NAV -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark border border-info">
    <!-- NAV CONTAINER START -->
    <div class="container-fluid">
        <a href="#" class="navbar-brand text-white fw-semibold fs-4">IT ADMIN</a>
        <!-- NAV BUTTON -->
        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="offcanvas"
                data-bs-target="#menuLateral">
            <span class="navbar-toggler-icon "></span>
        </button>

        <section class="offcanvas offcanvas-start bg-dark"
                 id="menuLateral"
                 tabindex="-1"
        >
            <div class="offcanvas-header"
                 data-bs-theme="dark">
                <h3 class="offcanvas-tittle text-white">IT ADMIN</h3>
                <button class="btn-close"
                        type="button"
                        aria-label="close"
                        data-bs-dismiss="offcanvas"
                ></button>
            </div>
            <div class="offcanvas-body d-flex flex-column justify-content-between px-0">
                <ul class="navbar-nav fs-5 justify-content-evenly">
                    <li class="nav-item p-3 py-md-1">
                        <a href="booking.php" class="nav-link text-info text-center">RESERVAS</a>
                    </li>
                    <li class="nav-item p-3 py-md-1">
                        <a href="hotel.php" class="nav-link text-info text-center">HOTELES</a>
                    </li>
                    <li class="nav-item p-3 py-md-1">
                        <a href="vehicle.php" class="nav-link text-info text-center">VEHÍCULOS</a>
                    </li>
                </ul>
                <div class="d-lg-none align-self-center py-3">
                    <a class="text-danger text-decoration-none" href="#"><i class="bi bi-person-gear px-2 text-danger"></i>logout</a>
                </div>
            </div>
        </section>
    </div>
</nav>
<h1 class="text-center py-3">CALENDARIO ADMINISTRADOR VA AQUÍ</h1>

<div class="container">
        <div class="col-xl">
            <div id='calendar'></div>
        </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</body>
</html>