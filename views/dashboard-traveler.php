<?php
session_start();
$isTraveler = isset($_SESSION['travelerUser']);
$emailCliente = $isTraveler ? $_SESSION['travelerUser'] : '';
//
include '../controllers/travelers/getSession.php';
include '../controllers/travelers/update.php';
//
$hotelsStmt = $db->prepare("SELECT Id_hotel FROM tranfer_hotel");
$hotelsStmt->execute();
$hotels = $hotelsStmt->fetchAll(PDO::FETCH_COLUMN);

// Array de nombres de hoteles asignados manualmente
$hotelNames = [
    1 => 'Hotel 1',
    2 => 'Hotel 2',
    3 => 'Hotel 3',
    4 => 'Hotel 4',
    5 => 'Hotel 5',
    6 => 'Hotel 6',
    7 => 'Hotel 7',
    8 => 'Hotel 8'

];

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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- FullCalendar.io -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: "es",
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: '../controllers/bookings/getCalendar.php',
                eventDidMount: function(info) {
                    console.log(info.event.extendedProps);
                        // Verifica el creador de la reserva y cambia el color del evento
                        if (info.event.extendedProps.tipo_creador_reserva === 1) {
                            info.el.style.backgroundColor = '#007bff'; // Color para reservas creadas por el admin (por ejemplo, gris oscuro)
                        } else if (info.event.extendedProps.tipo_creador_reserva === 2) {
                            info.el.style.backgroundColor = '#ffc107'; // Color para reservas creadas por el traveler (por ejemplo, gris claro)
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
                    <strong>Origen vuelo</strong> ${info.event.extendedProps.origen_vuelo_entrada} <br>
                    <strong>Nº viajeros</strong> ${info.event.extendedProps.num_viajeros} <br>
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
<nav class="navbar navbar-expand-xl bg-transparent">
    <div class="container-fluid">
        <!-- Logo -->
        <a class="navbar-brand fs-4 ps-5" href="dashboard-traveler.php" id="logo">
            <img src="../assets/img/logo.png" alt="" width="30" height="24" class="d-inline-block align-text-top">
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
<div class="container">
    <!-- Título -->
    <h1 class="text-center pt-3 text-success">¡Hola, <?php echo htmlspecialchars($_SESSION['travelerName']); ?>!</h1>
    <!-- Subtítulo -->
    <h2 class="text-center text-warning pt-5"> Gestiona tus reservas desde aquí</h2>
    <!-- Párrafo de alerta -->
    <p class="text-center text-secondary">¡Recuerda! No puedes crear, modificar ni cancelar tus reservas con menos de 48 horas de antelación.</p>
    <!-- Grupo de 3 botones -->
    <div class="container-fluid">
        <!-- Botón de crear -->
        <div class="col text-center p-1">
            <button type="button" class="btn btn-lg btn-warning text-white" data-bs-toggle="modal" data-bs-target="#addBookingModal">
                Nueva reserva
            </button>
        </div>
        <!-- Botón de editar -->
        <div class="col text-center p-1">

        </div>
        <!-- Botón de eliminar -->
        <div class="col text-center p-1">

        </div>
    </div>
    <div class="">
    <?php if (isset($_GET['success']) && $_GET['success'] === 'update_exitoso'): ?>
        <div class="alert alert-success" role="alert">
            Perfil actualizado correctamente.
        </div>
    <?php elseif (isset($_GET['error']) && $_GET['error'] === 'update_fallido'): ?>
        <div class="alert alert-danger" role="alert">
            Error al actualizar el perfil. Inténtelo de nuevo.
        </div>
    <?php endif; ?>
    </div>
</div>
<!-- CALENDA-->
<div class="container">
    <div class="col-xl">
        <div id="calendar"></div>
    </div>
</div>

<!-- ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////// MODALS //////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->

<!--Modal creacion Reservas-->
<div class="modal fade" id="addBookingModal" tabindex="-1" aria-labelledby="addBookingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning-subtle">
                <h2 class="modal-title">Nueva Reserva</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-light">
                <!-- Se elige entre las 3 opciones para abrir unos campos u otros -->
                <form action="../controllers/bookings/create.php" method="POST">
                    <select name="id_tipo_reserva" id="tipo_reserva" class="form-select form-select-sm" onchange="mostrarCampos()">

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
                            <input type="email" class="form-control" name="email_cliente" id="emailClientInput" aria-describedby="helpEmailClient"
                                   placeholder="Email del cliente"
                                   value="<?php echo htmlspecialchars($emailCliente); ?>"
                                <?php echo $isTraveler ? 'readonly' : ''; ?>
                                   required>
                            <label for="emailClientInput">Email del cliente</label>
                        </div>
                        <!-- Id Vehículo
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" name="id_vehiculo" id="idVehicleInput" aria-describedby="helpIdVehicle"  placeholder="Id vehiculo">
                            <label for="idVehicleInput">Vehículo</label>
                        </div>-->
                    </div>

                    <!-- Botones de envio y cierre -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-warning fw-bold text-white" name="addBooking">Crear</button>
                    </div>
                    <div class="modal-footer"></div>
                </form>
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
                        <!--Separador-->
                        <div class="bg-warning">
                            <hr class="text-warning">
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
    //
    <!-- Función que crea las reservas según el tipo de reserva -->
    document.addEventListener('DOMContentLoaded', function() {
        // Muestra los campos por defecto al abrir el modal
        document.getElementById("tipo_reserva").value = "idayvuelta";
        mostrarCampos();

        // Muestra u oculta los campos según el valor seleccionado
        document.getElementById("tipo_reserva").addEventListener('change', mostrarCampos);

        // Evento para mantener los campos de ida y vuelta al abrir el modal
        var addBookingModal = document.getElementById('addBookingModal');
        addBookingModal.addEventListener('shown.bs.modal', function() {
            document.getElementById("tipo_reserva").value = "idayvuelta"; // Configura el valor predeterminado
            mostrarCampos(); // Muestra los campos al abrir
        });
    });

    function mostrarCampos() {
        var tipoReserva = document.getElementById("tipo_reserva").value;
        document.getElementById("aeropuerto-hotel-fields").style.display = (tipoReserva == "1" || tipoReserva == "idayvuelta") ? "block" : "none";
        document.getElementById("hotel-aeropuerto-fields").style.display = (tipoReserva == "2" || tipoReserva == "idayvuelta") ? "block" : "none";
    }
</script>
<script>
        const travelerData = <?php echo isset($travelerData) ? json_encode($travelerData) : 'null'; ?>;
</script>
<script>
//
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
    document.querySelector('#updatePasswordInput').value = ''; // Deja la contraseña en blanco

    var modal = new bootstrap.Modal(document.getElementById('updateTravelerModal'));
    modal.show();
}
</script>
<!-- Archivos para accionar los modales -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
