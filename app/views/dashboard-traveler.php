<?php
session_start();
// Comprueba la sesión pertenece a travelerUser
$isTraveler = isset($_SESSION['travelerUser']);
$emailCliente = $isTraveler ? $_SESSION['travelerUser'] : '';
// Declaraciones de inclusión
include '../controllers/travelers/getSession.php';
include '../controllers/travelers/update.php';
//Inserción del campo Id_hotel
$hotelsStmt = $db->prepare("SELECT Id_hotel FROM tranfer_hotel");
$hotelsStmt->execute();
$hotels = $hotelsStmt->fetchAll(PDO::FETCH_COLUMN);
// Array de nombres de hoteles asignados manualmente
$hotelNames = [
    1 => 'Paraíso Escondido Retreat',
    2 => 'Corazón Isleño Inn',
    3 => 'Oasis Resort',
    4 => 'El faro Suites',
    5 => 'Costa Salvaje Eco Lodge',
    6 => 'Arenas Doradas Resort'
];
//Inclusión del campo name para el saludo inicial
$_SESSION['travelerName'] = $travelerData['name'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Isla Transfer - Viajeros</title>
    <meta name="author" content="PHPOWER" />
    <meta name="description" content="La página de inicio del panel de Clientes particulares (Viajeros) de Isla Transfer
    es accesible cuando el usuario viajero se identifica con sus credenciales. Desde aquí se puede acceder
    a la gestión de todas las acciones disponibles en la aplicación web para este tipo de usuario" />
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+Paaji+2:wght@400..800&family=Caveat&family=Roboto+Flex:opsz@8..144&display=swap" rel="stylesheet">
    <!-- Enlaces CDN -->
    <!-- Enlaces CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Enlaces Hojas Estilo-->
    <link rel="stylesheet" href="../assets/css/general.css">
    <link rel="stylesheet" href="../assets/css/traveler.css">
    <!-- Estilos para el header del FullCalendario. No funciona desde las hojas externas -->
    <style>
        /* CSS Personalizado para la Barra de Herramientas */
        .fc .fc-prev-button,
        .fc .fc-next-button,
        .fc .fc-today-button {
            background-color: #e2e3e5 !important;
            color: #000000 !important;
            border: none !important;
            font-weight: bold !important;
        }
        .fc .fc-prev-button:hover,
        .fc .fc-next-button:hover,
        .fc .fc-today-button:hover {
            background-color: #fff3cd !important;
            color: #000000 !important;
        }
        .fc .fc-toolbar-title {
            color: #28a745 !important;
            font-size: 1.5em !important;
            font-weight: bold !important;
            font-family: Arial, sans-serif !important;
        }
        .fc .fc-button-group .fc-button {
            background-color: #e2e3e5 !important;
            color: #000000 !important;
            border: none !important;
        }
        .fc .fc-button-group .fc-button:hover {
            background-color: #d4edda !important;
            color: #000000 !important;
        }
    </style>
    <!-- Librería de sweetAlert2-->
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
                    span.style.color = '#28a745';
                    span.style.padding = '5px';
                    span.style.display = 'block';
                    return { domNodes: [span] };
                },
                //Estilos de la celda today en el calendario
                dayCellDidMount: function(info) {
                    if (info.isToday) {
                        info.el.style.backgroundColor = '#fff3cd';
                        info.el.style.color = '#28a745';
                        info.el.style.fontWeight = 'bold';
                    }
                    let dayNumberElement = info.el.querySelector('.fc-daygrid-day-number');
                    if (dayNumberElement) {
                        dayNumberElement.style.color = '#28a745';
                        dayNumberElement.style.fontWeight = 'bold';
                        dayNumberElement.style.textDecoration = 'none';
                    }
                },
                //Estilo para las reservas insertadas en las celdas
                eventDidMount: function(info) {
                    console.log(info.event.extendedProps);
                    // Verifica el creador de la reserva y cambia el color del evento
                    if (info.event.extendedProps.tipo_creador_reserva === 1) {
                        info.el.style.backgroundColor = '#17a2b8'; // Color para reservas creadas por el admin (por ejemplo, gris oscuro)
                        info.el.style.color = '#ffffff';
                    } else if (info.event.extendedProps.tipo_creador_reserva === 2) {
                        info.el.style.backgroundColor = '#ffc107'; // Color para reservas creadas por el traveler (por ejemplo, gris claro)
                        info.el.style.color = '#ffffff';
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
                        </p>
                            <div class="mt-3">
                                <button onclick="editarReserva(${info.event.id})" class="btn btn-success">Editar</button>
                                <button onclick="eliminarReserva('${info.event.id}')" class="btn btn-danger">Eliminar</button>
                            </div>`,
                        icon: 'info',
                        confirmButtonText: '<i class="bi bi-x text-dark"></i>',
                        customClass: {
                            popup: 'swal-wide' // Clase personalizada para ajustar el ancho
                        },
                        didOpen: () => {
                            // Estilo de fondo de la card
                            const swalPopup = Swal.getPopup();
                            swalPopup.style.backgroundColor = '#fff3cd';  // Color de fondo
                            swalPopup.style.borderRadius = '10px';        // Bordes redondeados
                            swalPopup.style.color = '#343a40';            // Color del texto
                            swalPopup.style.boxShadow = '0px 4px 10px rgba(0, 0, 0, 0.2)'; // Sombra de la tarjeta
                            //Estilo botón Cerrar
                            const confirmButton = Swal.getConfirmButton();
                            confirmButton.style.backgroundColor = '#fff3cd'; // Color fondo
                            confirmButton.style.color = 'white'; // Color texto
                            confirmButton.style.fontSize = '16px'; // Tamaño de fuente
                            confirmButton.style.fontWeight = 'bold'; // Negrita
                            confirmButton.style.fontFamily = 'Arial, sans-serif'; // Fuente
                            confirmButton.style.padding = '1px'; // Padding
                            confirmButton.style.borderRadius = '8px'; // Bordes redondeados
                            confirmButton.style.border = '0px'; // Color borde
                            confirmButton.style.boxShadow = '0px 4px 10px rgba(0, 0, 0, 0.2)'; // Sombra
                            confirmButton.style.transition = 'all 0.3s ease'; // Transición
                            confirmButton.style.margin = '10px'; // Espacio externo

                            // Efecto hover
                            confirmButton.onmouseover = () => {
                                confirmButton.style.backgroundColor = '#6c757d'; // Cambio de color en hover
                                confirmButton.style.transform = 'scale(1.05)'; // Efecto de aumento
                            };
                            confirmButton.onmouseout = () => {
                                confirmButton.style.backgroundColor = '#fff3cd';
                                confirmButton.style.transform = 'scale(1)';
                            };
                            //Estilo icono superior decorativo
                            const iconElement = Swal.getIcon();
                            iconElement.style.color = '#ffc107'; // Cambia el color del ícono
                            iconElement.style.borderColor = '#ffc107'; // Cambia el color del círculo
                        }
                    });
                }
            });
            calendar.render();
        });
        // Función para abrir el modal de actualización y cerrar SweetAlert2
        function editarReserva(idReserva) {
            Swal.close(); // Cierra el modal de SweetAlert2
            abrirModalActualizarReserva(idReserva); // Abre el modal de Bootstrap para editar la reserva
        }

        // Función para la confirmación de eliminación y cierre de SweetAlert2
        function eliminarReserva(idReserva) {
            Swal.close(); // Cierra el modal de SweetAlert2
            confirmarEliminacion(`/controllers/bookings/delete.php?id_booking=${idReserva}`);
        }
    </script>
</head>
<body>
    <!-- //////////////////////////////////////////////// NAV //////////////////////////////////////////////// -->

    <nav class="navbar navbar-expand-xl bg-transparent">
        <div class="container-fluid">
            <!-- Logo -->
            <a class="navbar-brand fs-4 ps-5" href="dashboard-traveler.php" id="logo">
                <img src="../assets/img/logo.png" alt="Palmera con sol de fondo" width="30" height="24" class="d-inline-block align-text-top">
                Isla Transfer <!-- Nombre -->
            </a>
            <ul class="nav nav-pills justify-content-end">
                <li class="nav-item text-center">
                    <!-- BOTÓN ACTUALIZAR -->
                    <button onclick="abrirModalActualizar(<?php echo htmlspecialchars(json_encode($travelerData)); ?>)" class="btn btn-primary bg-transparent border-0 fs-5 fw-bold text-success" data-bs-toggle="modal" data-bs-target="#updateTravelerModal"><i class="bi bi-person-gear px-2 text-success"></i>Perfil</button>
                </li>
                <li class="pt-2">
                    <!-- Cerrar sesión -->
                    <a href="../controllers/travelers/login.php?action=logout" class="fs-5 pt-3 px-3 text-decoration-none text-danger"><i class="bi bi-box-arrow-left fs-5"></i></i> Cerrar sesión</a>
                </li>
            </ul>
        </div>
    </nav>
    
    <!-- ///////////////////////////////////////////// MENSAJES DE SUCCESS / ERROR ///////////////////////////////////////////// -->
    
     <!-- Mensajes de actualización Perfil success / error -->
     <div class="d-flex justify-content-end">
        <div class="col-2 text-center">
            <?php if (isset($_SESSION['update_traveler_success'])): ?>
                <div id="updateTravelerSuccess" class="alert alert-success fs-6" role="alert">
                    <?php echo $_SESSION['update_traveler_success']; ?>
                </div>
                <?php unset($_SESSION['update_traveler_success']); ?>
            <?php elseif (isset($_SESSION['update_traveler_error'])): ?>
                <div id="updateTravelerError" class="alert alert-danger fs-6" role="alert">
                    <?php echo $_SESSION['update_traveler_error']; ?>
                </div>
                <?php unset($_SESSION['update_traveler_error']); ?>
            <?php endif; ?>
        </div>
    </div>


    <div class="container">

        <!-- //////////////////////////////////////////////// BLOQUE PRINCIPAL //////////////////////////////////////////////// -->
        
        <!-- Título -->
        <h1 class="text-center pt-3 fw-light text-success">¡Hola, <?php echo htmlspecialchars($_SESSION['travelerName']); ?>!</h1>
        
        <!-- Subtítulo -->
        <h2 class="text-center text-secondary fw-bold pt-3">Añade, modifica y elimina tus reservas.</h2> 
        
        <!-- Botón de crear -->
        <div class="col text-center fw-bold">
            <button type="button" class="btn btn-lg text-warning" data-bs-toggle="modal" data-bs-target="#addBookingModal">
                <i class="bi bi-journal-plus display-3"></i>
            </button>
        </div>

         <!-- ///////////////////////////////////////////// MENSAJES DE SUCCESS / ERROR ///////////////////////////////////////////// -->

        <!-- Mensajes de creación de reserva success / error -->
        <div class="d-flex justify-content-center">
            <div class="col-4 text-center">
                <?php if (isset($_SESSION['create_booking_success'])): ?>
                    <div id="createBookingSuccess" class="alert alert-success fs-6" role="alert">
                        <?php echo $_SESSION['create_booking_success']; ?>
                    </div>
                    <?php unset($_SESSION['create_booking_success']); ?>
                <?php elseif (isset($_SESSION['create_booking_error'])): ?>
                    <div id="createBookingError" class="alert alert-danger fs-6" role="alert">
                        <?php echo $_SESSION['create_booking_error']; ?>
                    </div>
                    <?php unset($_SESSION['create_booking_error']); ?>
                <?php endif; ?>
            </div>
        </div>


        <!-- Mensajes de actualización Reserva success / error -->
        <div class="d-flex justify-content-center">
            <div class="col-4 text-center">
                <?php if (isset($_SESSION['update_booking_success'])): ?>
                    <div id="updateBookingSuccess" class="alert alert-success fs-6" role="alert">
                        <?php echo $_SESSION['update_booking_success']; ?>
                    </div>
                    <?php unset($_SESSION['update_booking_success']); ?>
                <?php elseif (isset($_SESSION['update_booking_error'])): ?>
                    <div id="updateBookingError" class="alert alert-danger fs-6" role="alert">
                        <?php echo $_SESSION['update_booking_error']; ?>
                    </div>
                    <?php unset($_SESSION['update_booking_error']); ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Mensajes de eliminación success / error -->
        <div class="d-flex justify-content-center">
            <div class="col-4 text-center">
                <?php if (isset($_SESSION['delete_booking_success'])): ?>
                    <div id="deleteBookingSuccess" class="alert alert-success fs-6" role="alert">
                        <?php echo $_SESSION['delete_booking_success']; ?>
                    </div>
                    <?php unset($_SESSION['delete_booking_success']); ?>
                <?php elseif (isset($_SESSION['delete_booking_error'])): ?>
                    <div id="deleteBookingError" class="alert alert-danger fs-6" role="alert">
                        <?php echo $_SESSION['delete_booking_error']; ?>
                    </div>
                    <?php unset($_SESSION['delete_booking_error']); ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Mensaje de error si se intenta crear con menos de 48 horas der antelacion -->
        <div class="d-flex justify-content-center">
            <div class="col-4 text-center">
                <?php if (isset($_SESSION['create_48_error'])): ?>
                    <div id="create48Error" class="alert alert-danger fs-6" role="alert">
                        <?php echo $_SESSION['create_48_error']; ?>
                    </div>
                    <?php unset($_SESSION['create_48_error']); ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Mensaje de error si se intenta modificar con menos de 48 horas der antelacion -->
        <div class="d-flex justify-content-center">
            <div class="col-4 text-center">
                <?php if (isset($_SESSION['update_48_error'])): ?>
                    <div id="update48Error" class="alert alert-danger fs-6" role="alert">
                        <?php echo $_SESSION['update_48_error']; ?>
                    </div>
                    <?php unset($_SESSION['update_48_error']); ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Mensaje de error si se intenta eliminar con menos de 48 horas der antelacion -->
        <div class="d-flex justify-content-center">
            <div class="col-4 text-center">
                <?php if (isset($_SESSION['delete_48_error'])): ?>
                    <div id="delete48Error" class="alert alert-danger fs-6" role="alert">
                        <?php echo $_SESSION['delete_48_error']; ?>
                    </div>
                    <?php unset($_SESSION['delete_48_error']); ?>
                <?php endif; ?>
            </div>
        </div>

    </div>

    <!-- //////////////////////////////////////////////// CALENDARIO //////////////////////////////////////////////// -->
    
    <div class="container">
        <div class="col-xl py-3">
            <div id="calendar"></div>
        </div>
    </div>

<!-- ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////// MODALS //////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->

    <!--Modal Creación Reservas-->
    <div class="modal fade" id="addBookingModal" tabindex="-1" aria-labelledby="addBookingModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning-subtle">
                    <h2 class="modal-title">Nueva Reserva</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body bg-light">
                    <form action="../controllers/bookings/create.php" method="POST">
                        <div class="pb-3">
                        <!-- Id_tipo_reserva Se elige entre las 3 opciones para abrir unos campos u otros -->
                            <select name="id_tipo_reserva" id="tipo_reserva" class="form-select form-select-sm" onchange="mostrarCampos()">
                                <option value="1">Aeropuerto-Hotel</option>
                                <option value="2">Hotel-Aeropuerto</option>
                                <option value="idayvuelta">Ida/Vuelta</option>
                            </select>
                        </div>
                        <!-- Bloque campos AEROPUERTO -> HOTEL -->
                        <div id="aeropuerto-hotel-fields" style="display:none;">
                            <div class="d-flex flex-row bd-highlight mb-3"> 
                                <!-- Fecha Entrada -->
                                <div class="form-floating mb-3 col-6">
                                    <input type="date" class="form-control" name="fecha_entrada" id="dateInInput" aria-describedby="helpDateIn" placeholder="Fecha_entrada">
                                    <label for="dateInInput">Día de llegada</label>
                                </div>
                                <!-- Hora Entrada -->
                                <div class="form-floating mb-3 col-6">
                                    <input type="time" class="form-control" name="hora_entrada" id="hourInInput" aria-describedby="helpHourIn" placeholder="Hora de entrada">
                                    <label for="hourInInput">Hora de llegada</label>
                                </div>
                            </div>
                            <div class="d-flex flex-row bd-highlight mb-3"> 
                                <!-- Numero Vuelo Entrada -->
                                <div class="form-floating mb-3 col-6">
                                    <input type="text" class="form-control" name="numero_vuelo_entrada" id="numFlightInInput" aria-describedby="helpNumFlightIn" placeholder="Numero vuelo de entrada">
                                    <label for="numFlightInInput">Numero vuelo</label>
                                </div>
                                <!-- Origen Vuelo Entrada -->
                                <div class="form-floating mb-3 col-6">
                                    <input type="text" class="form-control" name="origen_vuelo_entrada" id="originFlightInInput" aria-describedby="helpOriginFlightIn" placeholder="Origen vuelo de entrada">
                                    <label for="originFlightInInput">Aeropuerto de origen</label>
                                </div>
                            </div>
                        </div>

                        <!-- Bloque de campos HOTEL -> AEROPUERTO-->
                        <div id="hotel-aeropuerto-fields" style="display:none;">
                            <div class="d-flex flex-row bd-highlight mb-3">
                                <!-- Fecha Vuelo Salida -->
                                <div class="form-floating mb-3 col-6">
                                    <input type="date" class="form-control" name="fecha_vuelo_salida" id="dateFlightOutInput" aria-describedby="helpDateFlightOut" placeholder="Fecha vuelo de salida">
                                    <label for="dateFlightOutInput">Fecha vuelo de salida</label>
                                </div>
                                <!-- Hora Vuelo Salida -->
                                <div class="form-floating mb-3 col-6">
                                    <input type="time" class="form-control" name="hora_vuelo_salida" id="hourFlightOutInput" aria-describedby="helpHourFlightOut" placeholder="Hora vuelo de salida">
                                    <label for="hourFlightOutInput">Hora vuelo de salida</label>
                                </div>
                            </div>
                        </div>

                        <!-- Campos comunes para ambos trayectos -->
                        <div>
                            <div class="d-flex flex-row bd-highlight mb-3">
                                <!-- Id Destino-->
                                <div class="form-floating mb-3 col-6">
                                    <!--<input type="number" class="form-control" name="id_destino" id="idDestinationInput"  aria-describedby="helpIdDestination" placeholder="Id de destino" required>-->
                                    <select name="id_destino" id="idDestinationInput" class="form-select" required>
                                        <option value="">Selecciona un hotel</option>
                                        <?php foreach ($hotels as $hotelId): ?>
                                            <option value="<?php echo $hotelId; ?>">
                                                <?php echo isset($hotelNames[$hotelId]) ? $hotelNames[$hotelId] : "Hotel Desconocido ($hotelId)"; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <label for="idDestinationInput">Hotel</label>
                                </div>
                                <!-- Número Viajeros -->
                                <div class="form-floating mb-3 col-6">
                                    <input type="number" class="form-control" name="num_viajeros" id="numTravelersInput" aria-describedby="helpNumTravelers" placeholder="Numero de viajeros">
                                    <label for="numTravelersInput">Número de viajeros</label>
                                </div>
                            </div>
                            <!-- Email Cliente -->
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" name="email_cliente" id="emailClientInput" aria-describedby="helpEmailClient"
                                       placeholder="Email del cliente"
                                       value="<?php echo htmlspecialchars($emailCliente); ?>"
                                    <?php echo $isTraveler ? 'readonly' : ''; ?>
                                       required>
                                <label for="emailClientInput">Email del cliente</label>
                            </div>

                        </div>
                        <!-- Spinner de carga y botón de creación -->
                        <div class="text-center my-3" id="loadingSpinner" style="display: none;">
                            <div class="spinner-border text-warning" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <!-- Botones de envio y cierre -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning border-warning-subtle fw-bold text-white" name="addBooking" id="createBookingButton">Crear</button>
                        </div>
                        <div class="modal-footer"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal Actualizar Reserva -->
    <div class="modal fade" id="updateBookingModal" tabindex="-1" aria-labelledby="updateBookingModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning-subtle">
                    <h2 class="modal-title">Actualice la reserva</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                <div class="modal-body bg-light">
                    <form action="../controllers/bookings/update.php" method="POST">
                            <!-- Campo oculto de Id reserva -->
                            <input type="hidden" id="updateIdBookingInput" name="id_reserva">

                            <!-- Campo oculto para id_vehiculo, con valor predeterminado 1 si está vacío -->
                            <input type="hidden" name="id_vehiculo" id="updateIdVehicleInput" value="1">

                            <!-- Campo oculto para tipo_creador_reserva, con valor 1 o 2 -->
                            <input type="hidden" id="updateTipoCreadorReserva" name="tipo_creador_reserva">

                            <!-- Campo oculto Id Tipo Reserva -->
                            <div class="form-floating mb-3">
                                <input type="hidden" class="form-control" name="id_tipo_reserva" id="updateIdTypeBookingInput" placeholder="Tipo de reserva" onchange="mostrarCampos('update')">
                            </div>

                             <!-- Localizador -->
                             <div class="form-floating mb-3">
                                <input type="hidden" class="form-control" name="localizador" id="updateLocatorInput" placeholder="Localizador">
                            </div>

                            <!-- Email Cliente -->
                            <div class="form-floating mb-3">
                                <input type="hidden" class="form-control" name="email_cliente" id="updateEmailClientInput" aria-describedby="helpEmailClient" placeholder="Email del cliente">
                            </div>

                        <!-- Campos específicos para Aeropuerto - Hotel (id_tipo_reserva = 1) -->

                        <!-- Fecha de llegada -->
                        <div id="aeropuerto-hotel-fields-update" style="display: none;">
                            
                            <div class="d-flex flex-row bd-highlight mb-3">          
                        
                                <div class="form-floating mb-3 col-6">
                                    <input type="date" class="form-control" name="fecha_entrada" id="updateDateInInput" placeholder="Fecha de entrada">
                                    <label for="updateDateInInput">Fecha Llegada</label>
                                </div>

                                <!-- Hora de llegada -->
                                <div class="form-floating mb-3 col-6">
                                    <input type="time" class="form-control" name="hora_entrada" id="updateHourInInput" placeholder="Hora de entrada">
                                    <label for="updateHourInInput">Hora Llegada</label>
                                </div>

                            </div>

                            <div class="d-flex flex-row bd-highlight mb-3">

                                <!-- Número de vuelo -->
                                <div class="form-floating mb-3 col-6">
                                    <input type="text" class="form-control" name="numero_vuelo_entrada" id="updateNumFlightInInput" placeholder="Número de vuelo de entrada">
                                    <label for="updateNumFlightInInput">Número Vuelo Llegada</label>
                                </div>

                                <!-- Origen del vuelo -->
                                <div class="form-floating mb-3 col-6">
                                    <input type="text" class="form-control" name="origen_vuelo_entrada" id="updateOriginFlightInInput" placeholder="Origen del vuelo de entrada">
                                    <label for="updateOriginFlightInInput">Origen Vuelo</label>
                                </div>

                            </div>

                        </div>

                             <!-- Campos específicos para Hotel - Aeropuerto (id_tipo_reserva = 2) -->
                        <div id="hotel-aeropuerto-fields-update" style="display: none;">
                            
                            <div class="d-flex flex-row bd-highlight mb-3">

                                <!-- Fecha de salida -->
                                <div class="form-floating mb-3 col-6">
                                    <input type="date" class="form-control" name="fecha_vuelo_salida" id="updateDateFlightOutInput" placeholder="Fecha del vuelo de salida">
                                    <label for="updateDateFlightOutInput">Fecha Vuelo Salida</label>
                                </div>

                                <!-- Hora de salida -->
                                <div class="form-floating mb-3 col-6">
                                    <input type="time" class="form-control" name="hora_vuelo_salida" id="updateHourFlightOutInput" placeholder="Hora del vuelo de salida">
                                    <label for="updateHourFlightOutInput">Hora Vuelo Salida</label>
                                </div>

                            </div>

                        </div>

                        <div class="d-flex flex-row bd-highlight mb-3">

                            <!-- Id Destino-->
                            <div class="form-floating mb-3 col-6">
                                <!--<input type="number" class="form-control" name="id_destino" id="idDestinationInput"  aria-describedby="helpIdDestination" placeholder="Id de destino" required>-->
                                <select name="id_destino" id="updateIdDestinationInput" class="form-select" required>
                                    <option value="">Selecciona un hotel</option>
                                    <?php foreach ($hotels as $hotelId): ?>
                                        <option value="<?php echo $hotelId; ?>">
                                            <?php echo isset($hotelNames[$hotelId]) ? $hotelNames[$hotelId] : "Hotel Desconocido ($hotelId)"; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <label for="updateIdDestinationInput">Hotel</label>
                            </div>

                             <!-- Número Viajeros -->
                            <div class="form-floating mb-3 col-6">
                                <input type="number" class="form-control" name="num_viajeros" id="updateNumTravelersInput" aria-describedby="helpNumTravelers" placeholder="Numero de viajeros">
                                <label for="updateNumTravelersInput">Número de viajeros</label>
                            </div>

                        </div>

                        <!-- Botón Modificar -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning fw-bold text-white" name="updateBooking">Actualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal de Confirmación de Eliminación -->
    <div class="modal fade" id="confirmarEliminacionModal" tabindex="-1" aria-labelledby="confirmarEliminacionLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning-subtle">
                    <h2 class="modal-title" id="confirmarEliminacionLabel">Confirmar Eliminación</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body bg-light">
                    ¿Estás seguro de que deseas eliminar esta reserva?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" id="btnEliminar" class="btn btn-danger" data-url="">Eliminar</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal de actualizar Perfil viajero -->
    <div class="modal fade" id="updateTravelerModal" tabindex="-1" aria-labelledby="updateTravelerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header  bg-warning-subtle">
                    <h2 class="modal-title">Modificar Perfil</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body bg-light">
                    <form action="../controllers/travelers/update.php" method="POST">

                        <!-- Id Viajero -->
                        <div class="container mt-4">
                            <input type="hidden" name="id_traveler" id="updateIdTravelerInput">
                        </div>

                        <!-- E-mail-->
                        <div class="mb-3">
                            <label class="form-label text-warning" for="updateEmailInput">Email</label>
                            <input class="form-control" type="email" name="email" id="updateEmailInput" placeholder="Introduce tu email">
                        </div>
                        <!-- Password -->
                        <div class="mb-3">
                            <label class="form-label text-warning" for="updatePasswordInput">Password</label>
                            <input class="form-control" type="password" name="password" id="updatePasswordInput" placeholder="Introduce una nueva contraseña">
                        </div>

                            <!--Separador-->
                            <hr class="hr hr-blurry text-warning">

                        <!-- Nombre -->
                        <div class="mb-3">
                            <label class="form-label text-warning" for="updateNameInput">Nombre</label>
                            <input class="form-control" type="text" name="name" id="updateNameInput" placeholder="Introduce tu nombre">
                        </div>
                        <!-- Apellido 1 -->
                        <div class="mb-3">
                            <label class="form-label text-warning" for="updateSurname1Input">Apellido1</label>
                            <input class="form-control" type="text" name="surname1" id="updateSurname1Input" placeholder="Introduce tu primer apellido">
                        </div>
                        <!-- Apellido 2 -->
                        <div class="mb-3">
                            <label class="form-label text-warning" for="updateSurname2Input">Apellido2</label>
                            <input class="form-control" type="text" name="surname2" id="updateSurname2Input" placeholder="Introduce tu segundo apellido">
                        </div>

                            <!--Separador-->
                            <div class="bg-warning">
                                <hr class="text-warning">
                            </div>

                        <!-- Direccion -->
                        <div class="mb-3">
                            <label class="form-label text-warning" for="updateAddressInput">Dirección</label>
                            <input class="form-control" type="text" name="address" id="updateAddressInput" placeholder="Introduce tu dirección aquí">
                        </div>

                        <!-- Codigo Postal -->
                        <div class="mb-3">
                            <label class="form-label text-warning" for="updateZipCodeInput">Código Postal</label>
                            <input class="form-control" type="text" name="zipCode" id="updateZipCodeInput" placeholder="Introduce tu código postal">
                        </div>

                        <!-- Ciudad -->
                        <div class="mb-3">
                            <label class="form-label text-warning" for="updateCityInput">Ciudad</label>
                            <input class="form-control" type="text" name="city" id="updateCityInput" placeholder="Introduce tu ciudad">
                        </div>

                        <!-- Pais -->
                        <div class="mb-3">
                            <label class="form-label text-warning" for="updateCountryInput">País</label>
                            <input class="form-control" type="text" name="country" id="updateCountryInput" placeholder="Introduce tu país">
                        </div>

                        <!-- Botones de envio y cierre -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning fw-bold text-white" name="updateTraveler">Modificar</button>
                        </div>
                        <div class="modal-footer"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<!-- ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////// EVENTS //////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
<script>
   function hideMessage(successId, errorId) {
    setTimeout(function() {
        var successDiv = document.getElementById(successId);
        var errorDiv = document.getElementById(errorId);

        [successDiv, errorDiv].forEach(function(div) {
            if (div) {
                div.style.transition = "opacity 0.5s";
                div.style.opacity = "0";
                setTimeout(function() {
                    div.style.display = "none";
                }, 500);
            }
        });
    }, 5000);
}

// Llamar a la función para cada par de mensajes
hideMessage("updateTravelerSuccess", "updateTravelerError");
hideMessage("createBookingSuccess", "createBookingError");
hideMessage("updateBookingSuccess", "updateBookingError");
hideMessage("deleteBookingSuccess", "deleteBookingError");
hideMessage("create48Error");
hideMessage("update48Error");
hideMessage("delete48Error");


document.addEventListener('DOMContentLoaded', function () {
        // Configuración del formulario de creación de reservas
        const addBookingForm = document.querySelector('#addBookingModal form');
        const createBookingButton = document.getElementById('createBookingButton');
        const loadingSpinner = document.getElementById('loadingSpinner');

        // Mostrar spinner y desactivar el botón al enviar el formulario
        addBookingForm.addEventListener('submit', function () {
            loadingSpinner.style.display = 'block';
            createBookingButton.disabled = true;
        });

        // Mostrar campos según el tipo de reserva
        document.getElementById("tipo_reserva").addEventListener('change', function () {
            mostrarCampos("add");
        });

        var addBookingModal = document.getElementById('addBookingModal');
        addBookingModal.addEventListener('shown.bs.modal', function () {
            document.getElementById("tipo_reserva").value = "idayvuelta";
            mostrarCampos("add");
        });
    });

    /* Función para mostrar u ocultar los campos específicos de cada tipo de reserva */
    function mostrarCampos(modalType) {
        let tipoReserva, aeropuertoHotelFields, hotelAeropuertoFields;

        if (modalType === "add") {
            tipoReserva = document.getElementById("tipo_reserva").value;
            aeropuertoHotelFields = document.getElementById("aeropuerto-hotel-fields");
            hotelAeropuertoFields = document.getElementById("hotel-aeropuerto-fields");
        } else if (modalType === "update") {
            tipoReserva = document.getElementById("updateIdTypeBookingInput").value;
            aeropuertoHotelFields = document.getElementById("aeropuerto-hotel-fields-update");
            hotelAeropuertoFields = document.getElementById("hotel-aeropuerto-fields-update");
        }

        if (aeropuertoHotelFields && hotelAeropuertoFields) {
            // Mostrar ambos campos si es "idayvuelta", o solo uno según el tipo de reserva
            aeropuertoHotelFields.style.display = (tipoReserva == "1" || tipoReserva === "idayvuelta") ? "block" : "none";
            hotelAeropuertoFields.style.display = (tipoReserva == "2" || tipoReserva === "idayvuelta") ? "block" : "none";
        }
    }

    /* Función para actualizar la reserva según su id_tipo_reserva */
    function abrirModalActualizarReserva(idReserva) {
        // Llamada AJAX para obtener los datos de la reserva desde el servidor
        fetch(`../controllers/bookings/getBooking.php?id_reserva=${idReserva}`)
            .then(response => response.json())
            .then(booking => {
                if (booking.error) {
                    console.error('Error al obtener los datos de la reserva:', booking.error);
                    return;
                }

                // Configurar los campos comunes en el modal de actualización
                document.getElementById('updateIdBookingInput').value = booking.id_reserva || '';
                document.getElementById('updateLocatorInput').value = booking.localizador || '';
                document.getElementById('updateIdTypeBookingInput').value = booking.id_tipo_reserva || '';
                document.getElementById('updateEmailClientInput').value = booking.email_cliente || '';
                document.getElementById('updateNumTravelersInput').value = booking.num_viajeros || '';
                document.getElementById('updateIdVehicleInput').value = booking.id_vehiculo || '';
                document.getElementById('updateIdDestinationInput').value = booking.id_destino || '';
                document.getElementById('updateTipoCreadorReserva').value = booking.tipo_creador_reserva || ''; //añadido
                // Mostrar los campos específicos según el tipo de reserva
                mostrarCampos("update");

                // Campos específicos para Aeropuerto - Hotel
                if (booking.id_tipo_reserva == 1 || booking.id_tipo_reserva == 'idayvuelta') {
                    document.getElementById('updateDateInInput').value = booking.fecha_entrada || '';
                    document.getElementById('updateHourInInput').value = booking.hora_entrada || '';
                    document.getElementById('updateNumFlightInInput').value = booking.numero_vuelo_entrada || '';
                    document.getElementById('updateOriginFlightInInput').value = booking.origen_vuelo_entrada || '';
                }

                // Campos específicos para Hotel - Aeropuerto
                if (booking.id_tipo_reserva == 2 || booking.id_tipo_reserva == 'idayvuelta') {
                    document.getElementById('updateDateFlightOutInput').value = booking.fecha_vuelo_salida || '';
                    document.getElementById('updateHourFlightOutInput').value = booking.hora_vuelo_salida || '';
                }

                // Mostrar el modal de actualización
                var modal = new bootstrap.Modal(document.getElementById('updateBookingModal'));
                modal.show();
            })
            .catch(error => {
                console.error('Error al obtener los datos de la reserva:', error);
            });
    }

    /* Función para la confirmación de la eliminación en el modal de Delete */
    function confirmarEliminacion(url) {
        const btnEliminar = document.getElementById('btnEliminar');
        btnEliminar.setAttribute('data-url', url);

        btnEliminar.onclick = function () {
            const urlToDelete = btnEliminar.getAttribute('data-url');
            if (urlToDelete) {
                window.location.href = urlToDelete;
            }
        };

        // Mostrar el modal de confirmación de eliminación
        const modal = new bootstrap.Modal(document.getElementById('confirmarEliminacionModal'));
        modal.show();
    }

    /* Lectura del array travelerData */
    const travelerData = <?php echo isset($travelerData) ? json_encode($travelerData) : 'null'; ?>;

    /* Modal actualizar perfil */
    function abrirModalActualizar() {
        console.log('Se ejecuta la función abrirModalActualizar');
        console.log('Datos de travelerData:', travelerData);
        document.querySelector('#updateIdTravelerInput').value = travelerData.id_traveler || '';
        document.querySelector('#updateNameInput').value = travelerData.name || '';
        document.querySelector('#updateSurname1Input').value = travelerData.surname1 || '';
        document.querySelector('#updateSurname2Input').value = travelerData.surname2 || '';
        document.querySelector('#updateEmailInput').value = travelerData.email || '';
        document.querySelector('#updateAddressInput').value = travelerData.address || '';
        document.querySelector('#updateZipCodeInput').value = travelerData.zipCode || '';
        document.querySelector('#updateCityInput').value = travelerData.city || '';
        document.querySelector('#updateCountryInput').value = travelerData.country || '';
        document.querySelector('#updatePasswordInput').value = '';

        var modal = new bootstrap.Modal(document.getElementById('updateTravelerModal'));
        modal.show();
    }
</script>
<!-- Archivos para accionar los modales -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
