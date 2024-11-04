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
    1 => 'Hotel Norte',
    2 => 'Hotel Sur',
    3 => 'Hotel Este',
    4 => 'Hotel Oeste',
    5 => 'Hotel Norte2',
    6 => 'Hotel Sur2',
    7 => 'Hotel Este2',
    8 => 'Hotel Oeste2',
    9 => 'Hotel Centro'
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
<div class="container-fluid mt-4">
    <nav class="navbar navbar-expand-xl bg-transparent">
        <a href="dashboard-admin.php" class=" text-secondary text-decoration-none fw-bold fs-3"><i class="bi bi-arrow-return-left"></i></a>
    </nav>
    <div class="container-fluid">
        <h1 class="text-center">Gestión de Reservas</h1>
    </div>
    <!-- Botón para abrir el modal creación de reservas -->
    <div class="row">
        <div class="col text-start  py-1">
            <button type="button" class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#addBookingModal">
                Nueva reserva
            </button>
        </div>
    </div>
    <!-- Filtro por tipo de reserva -->
    <div class="text-end">
        <form method="GET" action="">
            <label for="id_tipo_reserva">Filtrar por Tipo de Reserva:</label>
            <select name="id_tipo_reserva" id="id_tipo_reserva">
                <option value="">Todos</option>
                <option value="1" <?php if ($Id_tipo_reserva == 1) echo 'selected'; ?>>Aeropuerto - Hotel</option>
                <option value="2" <?php if ($Id_tipo_reserva == 2) echo 'selected'; ?>>Hotel - Aeropuerto</option>
            </select>
            <button type="submit">Filtrar</button>
        </form>
    </div>
    <!-- Tabla -->
    <div class="row">
        <div class="col">
            <div class="table-responsive">
                <table class="table table-light table-striped table-hover w-100 h-100">
                    <thead>
                    <tr>
                        <th>Id reserva</th>
                        <th>Localizador</th>
                        <th>Hotel</th>
                        <th>Tipo de Reserva</th>
                        <th>Email Cliente</th>
                        <th>Fecha de Reserva</th>
                        <th>Número de Viajeros</th>
                        <?php if ($Id_tipo_reserva == 1 || !$Id_tipo_reserva): ?>
                            <th>Fecha Llegada</th>
                            <th>Hora Llegada</th>
                            <th>Número Vuelo Llegada</th>
                            <th>Origen Vuelo</th>
                        <?php endif; ?>
                        <?php if ($Id_tipo_reserva == 2 || !$Id_tipo_reserva): ?>
                            <th>Hora Vuelo Salida</th>
                            <th>Fecha Vuelo Salida</th>
                        <?php endif; ?>
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

    <!--Modal creacion Reservas-->
<div class="modal fade" id="addBookingModal" tabindex="-1" aria-labelledby="addBookingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h2 class="modal-title text-center">Nueva Reserva</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Se elige entre las 3 opciones para abrir unos campos u otros -->
                <form action="../controllers/bookings/create.php" method="POST">
                    <label for="tipo_reserva"></label>
                    <select name="id_tipo_reserva" id="tipo_reserva" onchange="mostrarCampos()">
                        <option value="1">Aeropuerto-Hotel</option>
                        <option value="2">Hotel-Aeropuerto</option>
                        <option value="idayvuelta">Ida/Vuelta</option>
                    </select>

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
                        <!-- Id Hotel
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" name="id_hotel" id="idHotelInput" aria-describedby="helpIdHotel" placeholder="ID hotel" required>
                            <label for="idHotelInput">Id de hotel</label>
                        </div>-->
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
                        <!-- Id Vehículo -->
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" name="id_vehiculo" id="idVehicleInput" aria-describedby="helpIdVehicle"  placeholder="Id vehiculo">
                            <label for="idVehicleInput">Vehículo</label>
                        </div>
                    </div>
                    <!-- <div>
                          Sección donde se mostrarán los datos del viajero
                        <div id="travelerData" style="display: none;">
                            <p><strong>Nombre:</strong> <span id="travelerName"></span></p>
                            <p><strong>Apellido:</strong> <span id="travelerSurname1"></span></p>
                            <p><strong>Pais:</strong> <span id="travelerCountry"></span></p>
                             Agrega más campos si es necesario
                        </div>
                    </div>-->

                <!-- Botones de envio y cierre -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-info" name="addBooking">Crear</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

    <!--Modal modificación Reservas-->
<div class="modal fade" id="updateBookingModal" tabindex="-1" aria-labelledby="updateBookingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Modificar Reserva</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="../controllers/bookings/update.php" method="POST">
                    <div class="container mt-4">
                        <!-- Id Reserva (oculto) -->
                        <input type="hidden" id="updateIdBookingInput" name="id_reserva">

                        <!-- Id Tipo Reserva -->
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" name="id_tipo_reserva" id="updateIdTypeBookingInput" placeholder="Tipo de reserva" readonly>
                            <label for="updateIdTypeBookingInput">Tipo de reserva (1: Aeropuerto-Hotel, 2: Hotel-Aeropuerto)</label>
                        </div>

                        <!-- Localizador -->
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="localizador" id="updateLocatorInput" placeholder="Localizador" readonly>
                            <label for="updateLocatorInput">Localizador</label>
                        </div>

                        <!-- Email Cliente -->
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="email_cliente" id="updateEmailClientInput" placeholder="Email" readonly>
                            <label for="updateEmailClientInput">Email del Cliente</label>
                        </div>

                        <!-- Id Hotel
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" name="id_hotel" id="updateIdHotelInput" placeholder="ID del hotel">
                            <label for="updateIdHotelInput">Hotel</label>
                        </div>-->

                        <!-- Número de Viajeros -->
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" name="num_viajeros" id="updateNumTravelersInput" placeholder="Número de viajeros" required>
                            <label for="updateNumTravelersInput">Número de Viajeros</label>
                        </div>

                        <!-- Id Destino -->
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" name="id_destino" id="updateIdDestinationInput" placeholder="ID del destino">
                            <label for="updateIdDestinationInput">Destino</label>
                        </div>

                        <!-- Id Vehículo -->
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" name="id_vehiculo" id="updateIdVehicleInput" placeholder="ID del vehículo">
                            <label for="updateIdVehicleInput">Vehículo</label>
                        </div>

                        <!-- Campos específicos para Aeropuerto - Hotel (id_tipo_reserva = 1) -->
                        <div id="aeropuerto-hotel-fields" style="display: none;">
                            <!-- Fecha Entrada -->
                            <div class="form-floating mb-3">
                                <input type="date" class="form-control" name="fecha_entrada" id="updateDateInInput" placeholder="Fecha de entrada">
                                <label for="updateDateInInput">Fecha Llegada</label>
                            </div>

                            <!-- Hora Entrada -->
                            <div class="form-floating mb-3">
                                <input type="time" class="form-control" name="hora_entrada" id="updateHourInInput" placeholder="Hora de entrada">
                                <label for="updateHourInInput">Hora Llegada</label>
                            </div>

                            <!-- Número de Vuelo Entrada -->
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="numero_vuelo_entrada" id="updateNumFlightInInput" placeholder="Número de vuelo de entrada">
                                <label for="updateNumFlightInInput">Número Vuelo Llegada</label>
                            </div>

                            <!-- Origen Vuelo Entrada -->
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="origen_vuelo_entrada" id="updateOriginFlightInInput" placeholder="Origen del vuelo de entrada">
                                <label for="updateOriginFlightInInput">Origen Vuelo</label>
                            </div>
                        </div>

                        <!-- Campos específicos para Hotel - Aeropuerto (id_tipo_reserva = 2) -->
                        <div id="hotel-aeropuerto-fields" style="display: none;">
                            <!-- Fecha Vuelo Salida -->
                            <div class="form-floating mb-3">
                                <input type="date" class="form-control" name="fecha_vuelo_salida" id="updateDateFlightOutInput" placeholder="Fecha del vuelo de salida">
                                <label for="updateDateFlightOutInput">Fecha Vuelo Salida</label>
                            </div>

                            <!-- Hora Vuelo Salida -->
                            <div class="form-floating mb-3">
                                <input type="time" class="form-control" name="hora_vuelo_salida" id="updateHourFlightOutInput" placeholder="Hora del vuelo de salida">
                                <label for="updateHourFlightOutInput">Hora Vuelo Salida</label>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de envío y cierre -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" name="updateBooking">Modificar</button>
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
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmarEliminacionLabel">Confirmar Eliminación</h5>
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
<script>
    <!-- Función que crea las reservas según el tipo de reserva -->
    function mostrarCampos() {
        var tipoReserva = document.getElementById("tipo_reserva").value;
        document.getElementById("aeropuerto-hotel-fields").style.display = (tipoReserva == "1" || tipoReserva == "idayvuelta") ? "block" : "none";
        document.getElementById("hotel-aeropuerto-fields").style.display = (tipoReserva == "2" || tipoReserva == "idayvuelta") ? "block" : "none";
    }

    <!-- Función para el modal Updater -->
    function abrirModalActualizar(booking) {
        document.querySelector('#updateIdBookingInput').value = booking.id_reserva || '';
        document.querySelector('#updateLocatorInput').value = booking.localizador || '';
        //document.querySelector('#updateIdHotelInput').value = booking.id_hotel || '';
        document.querySelector('#updateIdTypeBookingInput').value = booking.id_tipo_reserva || '';
        document.querySelector('#updateEmailClientInput').value = booking.email_cliente || '';
        document.querySelector('#updateIdDestinationInput').value = booking.id_destino || '';
        document.querySelector('#updateNumTravelersInput').value = booking.num_viajeros || '';
        document.querySelector('#updateIdVehicleInput').value = booking.id_vehiculo || '';

        // Solo mostrar los campos correspondientes al tipo de reserva
        if (booking.id_tipo_reserva == 1) { // Aeropuerto - Hotel
            document.querySelector('#updateDateInInput').value = booking.fecha_entrada || '';
            document.querySelector('#updateHourInInput').value = booking.hora_entrada || '';
            document.querySelector('#updateNumFlightInInput').value = booking.numero_vuelo_entrada || '';
            document.querySelector('#updateOriginFlightInInput').value = booking.origen_vuelo_entrada || '';

            // Mostrar campos de Aeropuerto-Hotel y ocultar los otros
            document.getElementById("aeropuerto-hotel-fields").style.display = "block";
            document.getElementById("hotel-aeropuerto-fields").style.display = "none";
        } else if (booking.id_tipo_reserva == 2) { // Hotel - Aeropuerto
            document.querySelector('#updateDateFlightOutInput').value = booking.fecha_vuelo_salida || '';
            document.querySelector('#updateHourFlightOutInput').value = booking.hora_vuelo_salida || '';

            // Mostrar campos de Hotel-Aeropuerto y ocultar los otros
            document.getElementById("aeropuerto-hotel-fields").style.display = "none";
            document.getElementById("hotel-aeropuerto-fields").style.display = "block";
        }

        var modal = new bootstrap.Modal(document.getElementById('updateBookingModal'));
        modal.show();
    }

    <!-- Función para la confirmación de la eliminación como modal en Delete -->
    function confirmarEliminacion(url) {
        // Asigna la URL al atributo data-url del botón de confirmación de eliminación
        const btnEliminar = document.getElementById('btnEliminar');
        btnEliminar.setAttribute('data-url', url);

        // Configura el evento onclick para redirigir a la URL cuando se confirme la eliminación
        btnEliminar.onclick = function() {
            const urlToDelete = btnEliminar.getAttribute('data-url');
            if (urlToDelete) {
                window.location.href = urlToDelete;
            }
        };

        // Mostrar el modal de confirmación
        const modal = new bootstrap.Modal(document.getElementById('confirmarEliminacionModal'));
        modal.show();
    }
</script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</body>
</html>