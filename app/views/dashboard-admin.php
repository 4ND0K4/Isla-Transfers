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
                locale: "es", //idioma
                firstDay: 1, //Inicia en lunes
                //Colocación de los elementos del header
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                //Cambio de nombres del header
                buttonText: {
                    today: 'Hoy',
                    month: 'Mes',
                    week: 'Semana',
                    day: 'Día'
                },
                events: '../controllers/bookings/getCalendar.php',
                //Estilos del today
                dayHeaderContent: function(arg) {
                    let span = document.createElement('span');
                    span.innerText = arg.text;
                    span.style.color = '#343a40';
                    span.style.padding = '5px';
                    span.style.display = 'block';
                    return { domNodes: [span] };
                },
                //Estilos de la celda today en el calendario
                dayCellDidMount: function(info) {
                    if (info.isToday) {
                        info.el.style.backgroundColor = '#e2e3e5';
                        info.el.style.color = '#343a40';
                        info.el.style.fontWeight = 'bold';
                    }
                    let dayNumberElement = info.el.querySelector('.fc-daygrid-day-number');
                    if (dayNumberElement) {
                        dayNumberElement.style.color = '#343a40';
                        dayNumberElement.style.fontWeight = 'bold';
                        dayNumberElement.style.textDecoration = 'none';
                    }
                },
                //Estilo para las reservas insertadas en las celdas
                eventDidMount: function(info) {
                    if (info.event.extendedProps.id_tipo_reserva == 1) {
                        info.el.style.backgroundColor = '#0d6efd';
                        info.el.style.color = '#ffffff'; // Color del texto a blanco
                    } else if (info.event.extendedProps.id_tipo_reserva == 2) {
                        info.el.style.backgroundColor = '#dc3545';
                        info.el.style.color = '#ffffff'; // Color del texto a blanco
                    }
                },
                //Estilo de las cards (con sweetAlert2)
                eventClick: function(info) {
                    Swal.fire({
                        title: '<strong style="color: #343a40; font-size: 1em; font-weight: bold;">Detalles de la Reserva</strong>',
                        html: `
                        <p style="color: #6c757d; font-size: 1em; text-align: left; margin-left: 20px;">
                            <strong>Ruta:</strong> <!--Tipo de Reserva-->${info.event.extendedProps.id_tipo_reserva == 1 ? 'Aeropuerto-Hotel' : 'Hotel-Aeropuerto'} -
                            <strong>Origen/Destino:</strong> <!--Hotel-->${info.event.title}
                        </p>
                        <p style="color: #6c757d; font-size: 1em; text-align: left; margin-left: 20px;">
                            <strong>Día:</strong> <!--Día recogida entrada/salida-->${info.event.start.toLocaleDateString()}
                            <strong>Hora:</strong> <!--Hora recogida entrada/salida-->${info.event.start.toLocaleTimeString()}
                        </p>
                        <p style="color: #6c757d; font-size: 1em; text-align: left; margin-left: 20px;">
                            <strong>Nº vuelo:</strong> ${info.event.extendedProps.numero_vuelo_entrada} -
                            <strong>Origen:</strong> ${info.event.extendedProps.origen_vuelo_entrada}
                        </p>
                        <p style="color: #6c757d; font-size: 1em; text-align: left; margin-left: 20px;">
                            <strong>Vehículo:</strong> ${info.event.extendedProps.id_vehiculo} -
                            <strong>Nº viajeros:</strong> ${info.event.extendedProps.num_viajeros}
                        </p>
                        <p style="color: #6c757d; font-size: 1em; text-align: left; margin-left: 20px;">
                            <strong>Cliente:</strong> ${info.event.extendedProps.email_cliente}<br>
                        </p>
                            <hr>
                        <p style="color: #6c757d; font-size: 1em; text-align: left; margin-left: 20px;">
                            <strong>ID:</strong> ${info.event.id} -
                            <strong>Localizador:</strong> ${info.event.extendedProps.localizador}
                        </p>`,
                        icon: 'info',
                        confirmButtonText: '<span style="color: white; font-weight: bold;">Cerrar</span>',
                        customClass: {
                            popup: 'swal-wide' // Clase personalizada para ajustar el ancho
                        },
                        didOpen: () => {
                            //Estilo botón Cerrar
                            const confirmButton = Swal.getConfirmButton();
                            confirmButton.style.backgroundColor = '#6c757d'; // Color fondo
                            confirmButton.style.color = 'white'; // Color texto
                            confirmButton.style.fontSize = '16px'; // Tamaño de fuente
                            confirmButton.style.fontWeight = 'bold'; // Negrita
                            confirmButton.style.fontFamily = 'Arial, sans-serif'; // Fuente
                            confirmButton.style.padding = '10px 20px'; // Padding
                            confirmButton.style.borderRadius = '8px'; // Bordes redondeados
                            confirmButton.style.border = '2px solid #6c757d'; // Color borde
                            confirmButton.style.boxShadow = '0px 4px 10px rgba(0, 0, 0, 0.2)'; // Sombra
                            confirmButton.style.transition = 'all 0.3s ease'; // Transición
                            confirmButton.style.margin = '10px'; // Espacio externo

                            // Efecto hover
                            confirmButton.onmouseover = () => {
                                confirmButton.style.backgroundColor = '#e2e3e5'; // Cambio de color en hover
                                confirmButton.style.transform = 'scale(1.05)'; // Efecto de aumento
                            };
                            confirmButton.onmouseout = () => {
                                confirmButton.style.backgroundColor = '#6c757d';
                                confirmButton.style.transform = 'scale(1)';
                            };
                            //Estilo icono superior decorativo
                            const iconElement = Swal.getIcon();
                            iconElement.style.color = '#e2e3e5'; // Color del ícono
                            iconElement.style.borderColor = '#e2e3e5'; // Color del círculo
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
                        <!-- logout -->
                        <a href="../controllers/adminController.php?action=logout" class="text-danger text-decoration-none"><i class="bi bi-plugin text-danger"></i> Cerrar sesión</a>
                    </div>
                </div>
            </section>
        </div>
    </nav>
    <!-- Calendario -->
    <h1 class="text-center py-5">CALENDARIO DE RESERVAS</h1>
    <div class="container">
            <div class="col-xl">
                <div id="calendar"></div>
            </div>
    </div>
    <div class="text-center p-5">
        <a href="../controllers/adminController.php?action=logout" class="text-danger text-decoration-none fs-5"><i class="bi bi-plugin text-danger"></i> Cerrar sesión</a>
    </div>
<!-- Archivos para accionar los modales -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</body>
</html>