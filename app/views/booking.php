<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: /views/login-admin.php");
    exit();
}
include '../controllers/bookings/read.php';
include '../controllers/bookings/delete.php';
include '../controllers/bookings/update.php';
require_once __DIR__ . '/../models/db.php';
//
$db = db_connect();
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
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Panel Admin - Reservas</title>
    <meta name="author" content="PHPOWER" />
    <meta name="description" content="La página de reservas del panel de administración de Isla Transfer
    sirve para que el administrador pueda gestionar las reservas de la aplicación web." />
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+Paaji+2:wght@400..800&family=Caveat&family=Roboto+Flex:opsz@8..144&display=swap" rel="stylesheet">
    <!--Enlaces JS-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!--Enlaces CSS-->
    <link rel="stylesheet" href="../assets/css/general.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<!-- ///////////////////////////////////////////// NAV ///////////////////////////////////////////// -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">       
    <!-- Icono de flecha de vuelta al dashboard-admin -->
        <div class="container-fluid my-4">
            <a href="dashboard-admin.php" class=" text-secondary text-decoration-none fw-bold fs-3"><i class="bi bi-arrow-return-left"></i></a>
        </div>
    <!-- ///////////////////////////////////////////// MENSAJES DE SUCCESS / ERROR ///////////////////////////////////////////// -->
        <div class="container-fluid">
            <ul class="navbar-nav ms-auto"> 
                <li> 
                    <!-- Mensajes de creación de reserva success / error -->
                    <div class="d-flex justify-content-end">
                        <div class="col text-center">
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

                    <!-- Mensajes de modificación de reserva success / error -->
                    <div class="d-flex justify-content-end">
                        <div class="col text-center">
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
                
                    <!-- Mensajes de eliminación de reserva success / error -->
                    <div class="d-flex justify-content-end">
                        <div class="col text-center">
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

                    <!-- Mensaje de error si se intenta crear una reserva con el e-mail de un usuario que no está registrado -->
                    <div class="d-flex justify-content-end">
                        <div class="col text-center">
                            <?php if (isset($_SESSION['create_email_error'])): ?>
                                <div id="createEmailError" class="alert alert-danger fs-6" role="alert">
                                    <?php echo $_SESSION['create_email_error']; ?>
                                </div>
                                <?php unset($_SESSION['create_email_error']); ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Mensaje de error si se intenta crear una reserva con el e-mail de un usuario que no está registrado -->
                    <div class="d-flex justify-content-end">
                        <div class="col text-center">
                            <?php if (isset($_SESSION['create_hotel_error'])): ?>
                                <div id="createHotelError" class="alert alert-danger fs-6" role="alert">
                                    <?php echo $_SESSION['create_hotel_error']; ?>
                                </div>
                                <?php unset($_SESSION['create_hotel_error']); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
<!-- ///////////////////////////////////////////// BLOQUE PRINCIPAL ///////////////////////////////////////////// -->
    <div class="container-fluid pt-4">
        <!-- Título -->
        <div class="container-fluid">
            <h1 class="text-center fw-bold text-secondary">Gestión de Reservas</h1>
        </div>
        <!-- Botón creación de reservas -->
        <div class="row">
            <div class="col text-start pt-4 pb-2">
                <button type="button" class="btn btn-outline-dark fw-bold" data-bs-toggle="modal" data-bs-target="#addBookingModal">Nueva reserva</button>
            </div>
            <!-- Filtro por tipo de reserva -->
            <div class="col text-end pt-4 pb-2">
                <form method="GET" action="">
                    <label for="id_tipo_reserva">Filtrar:</label>
                    <select name="id_tipo_reserva" id="id_tipo_reserva">
                        <option value="">Todos</option>
                        <option value="1" <?php if ($Id_tipo_reserva == 1) echo 'selected'; ?>>Aeropuerto - Hotel</option>
                        <option value="2" <?php if ($Id_tipo_reserva == 2) echo 'selected'; ?>>Hotel - Aeropuerto</option>
                    </select>
                    <button type="submit">Filtrar</button>
                </form>
            </div>
        </div>
        <!-- Tabla -->
        <div class="row">
            <div class="col">
                <div class="table-responsive">
                    <table class="table table-secondary table-striped table-hover w-100 h-100">
                        <thead>
                        <tr>
                            <th scope="col">Id reserva</th>
                            <th scope="col">Localizador</th>
                            <th scope="col">Hotel</th>
                            <th scope="col">Tipo de Reserva</th>
                            <th scope="col">Email Cliente</th>
                            <th scope="col">Fecha de Reserva</th>
                            <th scope="col">Número de Viajeros</th>
                            <?php if ($Id_tipo_reserva == 1 || !$Id_tipo_reserva): ?>
                                <th scope="col">Fecha Llegada</th>
                                <th scope="col">Hora Llegada</th>
                                <th scope="col">Número Vuelo Llegada</th>
                                <th scope="col">Origen Vuelo</th>
                            <?php endif; ?>
                            <?php if ($Id_tipo_reserva == 2 || !$Id_tipo_reserva): ?>
                                <th scope="col">Hora Vuelo Salida</th>
                                <th scope="col">Fecha Vuelo Salida</th>
                            <?php endif; ?>
                            <th scope="col"><!--Botones--></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($bookings as $booking): ?>
                            <tr>
                                <td><?php echo $booking['id_reserva']; ?></td>
                                <td><?php echo $booking['localizador']; ?></td>
                                <td><?php echo $booking['id_hotel']; ?></td>
                                <td><?php echo $booking['id_tipo_reserva'] == 1 ? 'Aeropuerto - Hotel' : 'Hotel - Aeropuerto'; ?></td>
                                <td><?php echo $booking['email_cliente']; ?></td>
                                <td><?php echo $booking['fecha_reserva']; ?></td>
                                <td><?php echo $booking['num_viajeros']; ?></td>
                                <?php if ($booking['id_tipo_reserva'] == 1 || !$Id_tipo_reserva): ?>
                                    <td><?php echo $booking['fecha_entrada']; ?></td>
                                    <td><?php echo $booking['hora_entrada']; ?></td>
                                    <td><?php echo $booking['numero_vuelo_entrada']; ?></td>
                                    <td><?php echo $booking['origen_vuelo_entrada']; ?></td>
                                <?php endif; ?>
                                <?php if ($booking['id_tipo_reserva'] == 2 || !$Id_tipo_reserva): ?>
                                    <td><?php echo $booking['hora_vuelo_salida']; ?></td>
                                    <td><?php echo $booking['fecha_vuelo_salida']; ?></td>
                                <?php endif; ?>
                                <td>
                                    <!-- Botón actualizar -->
                                    <div class="btn-group py-1" role="group">
                                        <button onclick="abrirModalActualizar(<?php echo htmlspecialchars(json_encode($booking)); ?>)" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil-square"></i></button>
                                    </div>
                                    <!-- Botón eliminar -->
                                    <div class="btn-group py-1" role="group">
                                        <a role="button" class="btn btn-sm btn-outline-danger" href="#" onclick="confirmarEliminacion('../controllers/bookings/delete.php?id_booking=<?php echo $booking['id_reserva']; ?>')">
                                            <i class="bi bi-trash-fill"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<!-- ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////// MODALS //////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->

<!--Modal creacion Reservas-->
<div class="modal fade" id="addBookingModal" tabindex="-1" aria-labelledby="addBookingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-secondary-subtle">
                <h2 class="modal-title text-center">Añade una nueva reserva</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Se elige entre las 3 opciones para abrir unos campos u otros -->
                <form action="../controllers/bookings/create.php" method="POST">

                    <div class="pb-3">
                        <select name="id_tipo_reserva" id="tipo_reserva" class="form-select form-select-sm" aria-label="multiple select" onchange="mostrarCampos()">
                            <option value="1">Aeropuerto-Hotel</option>
                            <option value="2">Hotel-Aeropuerto</option>
                            <option value="idayvuelta">Ida/Vuelta</option>
                        </select>
                    </div>

                    <!-- AEROPUERTO -> HOTEL -->
                    <div id="aeropuerto-hotel-fields" style="display:none;">

                        <!-- Fecha Entrada -->
                        <div class="form-floating mb-3">
                            <input type="date" class="form-control" name="fecha_entrada" id="dateInInput" aria-describedby="helpDateIn" placeholder="Fecha_entrada">
                            <label for="dateInInput">Día de llegada</label>
                        </div>
                        <!-- Hora Entrada -->
                        <div class="form-floating mb-3">
                            <input type="time" class="form-control" name="hora_entrada" id="hourInInput" aria-describedby="helpHourIn" placeholder="Hora de entrada">
                            <label for="hourInInput">Hora de llegada</label>
                        </div>

                        <!-- Numero Vuelo Entrada -->
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="numero_vuelo_entrada" id="numFlightInInput" aria-describedby="helpNumFlightIn" placeholder="Numero vuelo de entrada">
                            <label for="numFlightInInput">Numero vuelo</label>
                        </div>
                        <!-- Origen Vuelo Entrada -->
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="origen_vuelo_entrada" id="originFlightInInput" aria-describedby="helpOriginFlightIn" placeholder="Origen vuelo de entrada">
                            <label for="originFlightInInput">Aeropuerto de origen</label>
                        </div>
                    </div>

                    <!-- HOTEL -> AEROPUERTO-->
                    <div id="hotel-aeropuerto-fields" style="display:none;">
                        <!-- Fecha Vuelo Salida -->
                        <div class="form-floating mb-3">
                            <input type="date" class="form-control" name="fecha_vuelo_salida" id="dateFlightOutInput" aria-describedby="helpDateFlightOut" placeholder="Fecha vuelo de salida">
                            <label for="dateFlightOutInput">Fecha vuelo de salida</label>
                        </div>
                        <!-- Hora Vuelo Salida -->
                        <div class="form-floating mb-3">
                            <input type="time" class="form-control" name="hora_vuelo_salida" id="hourFlightOutInput" aria-describedby="helpHourFlightOut" placeholder="Hora vuelo de salida">
                            <label for="hourFlightOutInput">Hora vuelo de salida</label>
                        </div>
                    </div>

                    <!-- Campos comunes para ambos trayectos -->
                    <div>
                        <!-- Id Destino-->
                        <div class="form-floating mb-3">
                            <!--<input type="number" class="form-control" name="id_destino" id="idDestinationInput"  aria-describedby="helpIdDestination" placeholder="Id de destino" required>-->
                            <select name="id_destino" id="idDestinationInput" class="form-select" required>
                                <option value="">Selecciona un Id de Destino</option>
                                <?php foreach ($hotels as $hotelId): ?>
                                    <option value="<?php echo $hotelId; ?>">
                                        <?php echo isset($hotelNames[$hotelId]) ? $hotelNames[$hotelId] : "Hotel Desconocido ($hotelId)"; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <label for="idDestinationInput">Id de destino</label>
                        </div>
                        <!-- Número Viajeros -->
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" name="num_viajeros" id="numTravelersInput" aria-describedby="helpNumTravelers" placeholder="Numero de viajeros">
                            <label for="numTravelersInput">Número de viajeros</label>
                        </div>
                        <!-- Email Cliente -->
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" name="email_cliente" id="emailClientInput" aria-describedby="helpEmailClient" placeholder="Email del cliente" required>
                            <label for="emailClientInput">Email del cliente</label>
                        </div>
                    </div>
                    <!-- Botones de envio y cierre -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-dark border-dark-subtle fw-bold text-white" name="addBooking">Crear</button>
                    </div>
                    <div class="modal-footer"></div>
                </form>
            </div>
        </div>
    </div>
</div>

<!--Modal modificación Reservas-->
<div class="modal fade" id="updateBookingModal" tabindex="-1" aria-labelledby="updateBookingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-secondary-subtle">
                <h2 class="modal-title">Actualice la reserva</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="../controllers/bookings/update.php" method="POST">
                    <input type="hidden" id="updateIdBookingInput" name="id_reserva">

                    <!-- Campo oculto para id_vehiculo, con valor predeterminado si está vacío -->
                    <input type="hidden" name="id_vehiculo" id="updateIdVehicleInput" value="1">


                    <!-- Id Tipo Reserva -->
                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" name="id_tipo_reserva" id="updateIdTypeBookingInput" placeholder="Tipo de reserva" onchange="mostrarCampos('update')">
                        <label for="updateIdTypeBookingInput">Tipo de reserva (1: Aeropuerto-Hotel, 2: Hotel-Aeropuerto)</label>
                    </div>

                    <!-- Localizador -->
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="localizador" id="updateLocatorInput" placeholder="Localizador" readonly>
                        <label for="updateLocatorInput">Localizador</label>
                    </div>

                    <!-- Número Viajeros -->
                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" name="num_viajeros" id="updateNumTravelersInput" aria-describedby="helpNumTravelers" placeholder="Numero de viajeros">
                        <label for="updateNumTravelersInput">Número de viajeros</label>
                    </div>

                    <!-- Email Cliente -->
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" name="email_cliente" id="updateEmailClientInput" aria-describedby="helpEmailClient" placeholder="Email del cliente" required>
                        <label for="updateEmailClientInput">Email del cliente</label>
                    </div>

                    <!-- Id Destino-->
                    <div class="form-floating mb-3">
                        <!--<input type="number" class="form-control" name="id_destino" id="idDestinationInput"  aria-describedby="helpIdDestination" placeholder="Id de destino" required>-->
                        <select name="id_destino" id="updateIdDestinationInput" class="form-select" required>
                            <option value="">Selecciona un Id de Destino</option>
                            <?php foreach ($hotels as $hotelId): ?>
                                <option value="<?php echo $hotelId; ?>">
                                    <?php echo isset($hotelNames[$hotelId]) ? $hotelNames[$hotelId] : "Hotel Desconocido ($hotelId)"; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <label for="updateIdDestinationInput">Id de destino</label>
                    </div>

                    <!-- Campos específicos para Aeropuerto - Hotel (id_tipo_reserva = 1) -->
                    <div id="aeropuerto-hotel-fields-update" style="display: none;">
                        <div class="form-floating mb-3">
                            <input type="date" class="form-control" name="fecha_entrada" id="updateDateInInput" placeholder="Fecha de entrada">
                            <label for="updateDateInInput">Fecha Llegada</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="time" class="form-control" name="hora_entrada" id="updateHourInInput" placeholder="Hora de entrada">
                            <label for="updateHourInInput">Hora Llegada</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="numero_vuelo_entrada" id="updateNumFlightInInput" placeholder="Número de vuelo de entrada">
                            <label for="updateNumFlightInInput">Número Vuelo Llegada</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="origen_vuelo_entrada" id="updateOriginFlightInInput" placeholder="Origen del vuelo de entrada">
                            <label for="updateOriginFlightInInput">Origen Vuelo</label>
                        </div>
                    </div>

                    <!-- Campos específicos para Hotel - Aeropuerto (id_tipo_reserva = 2) -->
                    <div id="hotel-aeropuerto-fields-update" style="display: none;">
                        <div class="form-floating mb-3">
                            <input type="date" class="form-control" name="fecha_vuelo_salida" id="updateDateFlightOutInput" placeholder="Fecha del vuelo de salida">
                            <label for="updateDateFlightOutInput">Fecha Vuelo Salida</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="time" class="form-control" name="hora_vuelo_salida" id="updateHourFlightOutInput" placeholder="Hora del vuelo de salida">
                            <label for="updateHourFlightOutInput">Hora Vuelo Salida</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-dark fw-bold text-white" name="updateBooking">Modificar</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modal de Confirmación de Eliminación -->
<div class="modal fade" id="confirmarEliminacionModal" tabindex="-1" aria-labelledby="confirmarEliminacionLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-secondary-subtle">
                <h2 class="modal-title" id="confirmarEliminacionLabel">Confirmar Eliminación</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar esta reserva?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" id="btnEliminar" class="btn btn-danger" data-url="">Eliminar</button>
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
hideMessage("createBookingSuccess", "createBookingError");
hideMessage("updateBookingSuccess", "updateBookingError");
hideMessage("deleteBookingSuccess", "deleteBookingError");
hideMessage("createEmailError");
hideMessage("createHotelError");


    document.addEventListener('DOMContentLoaded', function () {
        // Configuración para el modal de creación
        document.getElementById("tipo_reserva").addEventListener('change', function () {
            mostrarCampos("add");
        });

        var addBookingModal = document.getElementById('addBookingModal');
        addBookingModal.addEventListener('shown.bs.modal', function () {
            document.getElementById("tipo_reserva").value = "idayvuelta";
            mostrarCampos("add");
        });
    });

    // Función para mostrar u ocultar los campos específicos de cada tipo de reserva
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

    function abrirModalActualizar(booking) {
        // Configurar los campos comunes en el modal de actualización, verificando que cada campo existe
        if (document.getElementById('updateIdBookingInput')) {
            document.getElementById('updateIdBookingInput').value = booking.id_reserva || '';
        }
        if (document.getElementById('updateLocatorInput')) {
            document.getElementById('updateLocatorInput').value = booking.localizador || '';
        }
        if (document.getElementById('updateIdTypeBookingInput')) {
            document.getElementById('updateIdTypeBookingInput').value = booking.id_tipo_reserva || '';
        }
        if (document.getElementById('updateEmailClientInput')) {
            document.getElementById('updateEmailClientInput').value = booking.email_cliente || '';
        }
        if (document.getElementById('updateNumTravelersInput')) {
            document.getElementById('updateNumTravelersInput').value = booking.num_viajeros || '';
        }
        if (document.getElementById('updateIdVehicleInput')) {
            document.getElementById('updateIdVehicleInput').value = booking.id_vehiculo || '';
        }
        if (document.getElementById('updateIdDestinationInput')) {
            document.getElementById('updateIdDestinationInput').value = booking.id_destino || '';
        }

        // Mostrar los campos específicos según el tipo de reserva
        mostrarCampos("update");

        // Campos específicos para Aeropuerto - Hotel
        if (booking.id_tipo_reserva == 1 || booking.id_tipo_reserva == 'idayvuelta') {
            if (document.getElementById('updateDateInInput')) {
                document.getElementById('updateDateInInput').value = booking.fecha_entrada || '';
            }
            if (document.getElementById('updateHourInInput')) {
                document.getElementById('updateHourInInput').value = booking.hora_entrada || '';
            }
            if (document.getElementById('updateNumFlightInInput')) {
                document.getElementById('updateNumFlightInInput').value = booking.numero_vuelo_entrada || '';
            }
            if (document.getElementById('updateOriginFlightInInput')) {
                document.getElementById('updateOriginFlightInInput').value = booking.origen_vuelo_entrada || '';
            }
        }

        // Campos específicos para Hotel - Aeropuerto
        if (booking.id_tipo_reserva == 2 || booking.id_tipo_reserva == 'idayvuelta') {
            if (document.getElementById('updateDateFlightOutInput')) {
                document.getElementById('updateDateFlightOutInput').value = booking.fecha_vuelo_salida || '';
            }
            if (document.getElementById('updateHourFlightOutInput')) {
                document.getElementById('updateHourFlightOutInput').value = booking.hora_vuelo_salida || '';
            }
        }

        // Mostrar el modal de actualización
        var modal = new bootstrap.Modal(document.getElementById('updateBookingModal'));
        modal.show();
    }


    // Función para la confirmación de la eliminación en el modal de Delete
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

</script>
<!-- Archivos para accionar los modales -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</body>
</html>