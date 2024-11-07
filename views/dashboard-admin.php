<?php
session_start();
if (!isset($_SESSION['admin'])) {
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
    <link rel="stylesheet" href="../assets/css/general.css">
    <link rel="stylesheet" href="../assets/css/admin.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- FullCalendar.io -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: "es", // En español
                firstDay: 1, // Comienza en lunes
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: '../controllers/bookings/getCalendar.php',

                // Cambia el estilo de los nombres de los días
                dayHeaderContent: function(arg) {
                    let span = document.createElement('span');
                    span.innerText = arg.text;
                    span.style.color = '#343a40'; // Color del texto
                    span.style.padding = '5px';
                    span.style.display = 'block';
                    return { domNodes: [span] };
                },
                // Cambia el color de la celda de hoy
                dayCellDidMount: function(info) {
                    if (info.isToday) {
                        info.el.style.backgroundColor = '#e2e3e5'; // Color de fondo para el día actual
                        info.el.style.color = '#343a40'; // Color del texto del día actual
                        info.el.style.fontWeight = 'bold'; // Negrita para destacar el día actual
                    }
                    // Cambia el color del número de día para cada celda
                    let dayNumberElement = info.el.querySelector('.fc-daygrid-day-number');
                    if (dayNumberElement) {
                        dayNumberElement.style.color = '#343a40'; // Color para el número de cada día
                        dayNumberElement.style.fontWeight = 'bold'; // Negrita para el número del día
                        dayNumberElement.style.textDecoration = 'none'; // Elimina el subrayado
                    }
                },
                eventDidMount: function(info) {
                    console.log(info.event.extendedProps);
                    // Verifica el tipo de reserva y cambia el color del evento
                    if (info.event.extendedProps.id_tipo_reserva == 1) { // Aeropuerto-Hotel
                        info.el.style.backgroundColor = '#0d6efd'; // Color verde
                    } else if (info.event.extendedProps.id_tipo_reserva == 2) { // Hotel-Aeropuerto
                        info.el.style.backgroundColor = '#dc3545'; // Color rojo
                    }
                },
                eventClick: function(info) {
                    Swal.fire({
                        title: 'Detalles de la Reserva',
                        html: `
                    <strong>Tipo de Reserva:</strong> ${info.event.extendedProps.id_tipo_reserva == 1 ? 'Aeropuerto-Hotel' : 'Hotel-Aeropuerto'}<br>
                    <strong>Dia llegada:</strong> ${info.event.start} <br>
                    <strong>Hora</strong> ${info.event.extendedProps.hora_entrada} <br>
                    <strong>Hora</strong> ${info.event.extendedProps.hora_vuelo_salida} <br>
                    <strong>Hotel</strong> ${info.event.title} <br>
                    <strong>Cliente:</strong> ${info.event.extendedProps.email_cliente} <br>
                    <strong>Origen vuelo</strong> ${info.event.extendedProps.origen_vuelo_entrada} <br>
                    <strong>Nº viajeros</strong> ${info.event.extendedProps.num_viajeros} <br>
                    <strong>Vehículo</strong> ${info.event.extendedProps.id_vehiculo} <br>
                    <strong>Localizador</strong> ${info.event.extendedProps.localizador} <br>
                    <strong>Reserva ID:</strong> ${info.event.id} <br>
                `,
                        icon: 'info',
                        confirmButtonText: 'Cerrar',
                        customClass: {
                            confirmButton: 'my-confirm-button'
                        }
                    });
                }
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
                        <a href="booking.php" class="nav-link text-white text-center">RESERVAS</a>
                    </li>
                    <div class="vr bg-light"></div>
                    <li class="nav-item p-3 py-md-1">
                        <a href="hotel.php" class="nav-link text-white text-center">HOTELES</a>
                    </li>
                    <div class="vr bg-light"></div>
                    <li class="nav-item p-3 py-md-1">
                        <a href="vehicle.php" class="nav-link text-white text-center">VEHÍCULOS</a>
                    </li>
                </ul>
                <div class="d-lg-none align-self-center py-3">
                    <!-- En tu archivo dashboard-admin.php -->
                    <a href="../controllers/adminController.php?action=logout" class="text-danger text-decoration-none"><i class="bi bi-person-gear px-2 text-danger"></i>Cerrar sesión</a>
                </div>
            </div>
        </section>
    </div>
</nav>
<h1 class="text-center py-3">CALENDARIO DE RESERVAS</h1>
<div class="container">
        <div class="col-xl">
            <div id="calendar"></div>
        </div>
</div>
<div class="text-end p-5">
    <a href="../controllers/adminController.php?action=logout" class="text-danger text-decoration-none fs-5"><i class="bi bi-person-gear px-2 text-danger"></i>Cerrar sesión</a>
</div>
<!-- Archivos para accionar los modales -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</body>
</html>