<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: /views/login-admin.php");
    exit();
}
include '../controllers/tours/read.php';
include '../controllers/tours/delete.php';
include '../controllers/tours/update.php';
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
    <title>Panel Admin - Excursiones</title>
    <meta name="author" content="PHPOWER" />
    <meta name="description" content="La página de excursiones del panel de administración de Isla Transfer
    permite al administrador gestionar las excursiones en la aplicación web." />
    <!--Fonts-->
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

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid my-4">
        <a href="dashboard-admin.php" class="text-secondary text-decoration-none fw-bold fs-3"><i class="bi bi-arrow-return-left"></i></a>
    </div>
    <div class="container-fluid">
        <ul class="navbar-nav ms-auto">
            <li>
                <div class="d-flex justify-content-end">
                    <div class="col text-center">
                        <?php if (isset($_SESSION['create_tour_success'])): ?>
                            <div id="createTourSuccess" class="alert alert-success fs-6" role="alert">
                                <?php echo $_SESSION['create_tour_success']; ?>
                            </div>
                            <?php unset($_SESSION['create_tour_success']); ?>
                        <?php elseif (isset($_SESSION['create_tour_error'])): ?>
                            <div id="createTourError" class="alert alert-danger fs-6" role="alert">
                                <?php echo $_SESSION['create_tour_error']; ?>
                            </div>
                            <?php unset($_SESSION['create_tour_error']); ?>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- Mensajes de modificación -->
                <div class="d-flex justify-content-end">
                    <div class="col text-center">
                        <?php if (isset($_SESSION['update_tour_success'])): ?>
                            <div id="updateTourSuccess" class="alert alert-success fs-6" role="alert">
                                <?php echo $_SESSION['update_tour_success']; ?>
                            </div>
                            <?php unset($_SESSION['update_tour_success']); ?>
                        <?php elseif (isset($_SESSION['update_tour_error'])): ?>
                            <div id="updateTourError" class="alert alert-danger fs-6" role="alert">
                                <?php echo $_SESSION['update_tour_error']; ?>
                            </div>
                            <?php unset($_SESSION['update_tour_error']); ?>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- Mensajes de eliminación -->
                <div class="d-flex justify-content-center">
                    <div class="col text-center">
                        <?php if (isset($_SESSION['delete_tour_success'])): ?>
                            <div id="deleteTourSuccess" class="alert alert-success fs-6" role="alert">
                                <?php echo $_SESSION['delete_tour_success']; ?>
                            </div>
                            <?php unset($_SESSION['delete_tour_success']); ?>
                        <?php elseif (isset($_SESSION['delete_tour_error'])): ?>
                            <div id="deleteTourError" class="alert alert-danger fs-6" role="alert">
                                <?php echo $_SESSION['delete_tour_error']; ?>
                            </div>
                            <?php unset($_SESSION['delete_tour_error']); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</nav>

<div class="container-fluid pt-4">
    <div class="container-fluid">
        <h1 class="text-center fw-bold text-secondary">Gestión de Excursiones</h1>
    </div>
    <div class="row">
        <div class="col text-start pt-4 pb-2">
            <button type="button" class="btn btn-outline-dark fw-bold" data-bs-toggle="modal" data-bs-target="#addTourModal">
                Nueva Excursión
            </button>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="table-responsive">
                <table class="table table-dark table-striped table-hover w-100 h-100">
                    <thead>
                    <tr>
                        <th scope="col">ID Excursión</th>
                        <th scope="col">Fecha</th>
                        <th scope="col">Hora Entrada</th>
                        <th scope="col">Hora Salida</th>
                        <th scope="col">Descripción</th>
                        <th scope="col">Número Excursionistas</th>
                        <th scope="col">Email Cliente</th>
                        <th scope="col">ID Hotel</th>
                        <th scope="col">ID Vehículo</th>
                        <th scope="col"><!--Botones--></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($tours as $tour) : ?>
                        <tr id="tour-<?php echo $tour['id_excursion']; ?>">
                            <td><?php echo $tour['id_excursion']; ?></td>
                            <td><?php echo $tour['fecha_excursion']; ?></td>
                            <td><?php echo $tour['hora_entrada_excursion']; ?></td>
                            <td><?php echo $tour['hora_salida_excursion']; ?></td>
                            <td><?php echo $tour['descripcion']; ?></td>
                            <td><?php echo $tour['num_excursionistas']; ?></td>
                            <td><?php echo $tour['email_cliente']; ?></td>
                            <td><?php echo $tour['id_hotel']; ?></td>
                            <td><?php echo $tour['id_vehiculo']; ?></td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button onclick="abrirModalActualizar(<?php echo htmlspecialchars(json_encode($tour)); ?>)" class="btn btn-sm btn-outline-secondary">Modificar</button>
                                </div>
                                <div class="btn-group" role="group">
                                    <a role="button" class="btn btn-sm btn-outline-danger" href="#" onclick="confirmarEliminacion('../controllers/tours/delete.php?id_excursion=<?php echo $tour['id_excursion']; ?>')">Borrar</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal de creación de Excursión -->
<div class="modal fade" id="addTourModal" tabindex="-1" aria-labelledby="addTourModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h2 class="modal-title">Añade una nueva excursión</h2>
                <button type="button" class="btn-close bg-secondary" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="../controllers/tours/create.php" method="POST">
                    <div class="container mt-4">
                        <!-- Campos de la excursión -->
                        <div class="form-floating mb-3">
                            <input type="date" class="form-control" id="fechaExcursionInput" name="fecha_excursion" required>
                            <label for="fechaExcursionInput">Fecha Excursión</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="time" class="form-control" id="horaEntradaExcursionInput" name="hora_entrada_excursion" required>
                            <label for="horaEntradaExcursionInput">Hora Entrada Excursión</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="time" class="form-control" id="horaSalidaExcursionInput" name="hora_salida_excursion" required>
                            <label for="horaSalidaExcursionInput">Hora Salida Excursión</label>
                        </div>
                        <div class="form-floating mb-3">
                            <select class="form-select" id="descripcionInput" name="descripcion" required>
                                <option value="" disabled selected>Selecciona una excursión</option>
                                <option value="Excursión a la Playa">Excursión a la Playa</option>
                                <option value="Visita al Volcán">Visita al Volcán</option>
                                <option value="Recorrido por la Ciudad">Recorrido por la Ciudad</option>
                                <option value="Aventura en la Selva">Aventura en la Selva</option>
                                <option value="Tour Cultural">Tour Cultural</option>
                                <option value="Paseo en Barco">Paseo en Barco</option>
                            </select>
                            <label for="descripcionInput">Descripción</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" id="numExcursionistasInput" name="num_excursionistas" placeholder="Número de excursionistas" required>
                            <label for="numExcursionistasInput">Número de excursionistas</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="emailClienteInput" name="email_cliente" placeholder="Email Cliente" required>
                            <label for="emailClienteInput">Email Cliente</label>
                        </div>
                        
                         <!-- Id Hotel-->
                         <div class="form-floating mb-3">
                            <!--<input type="number" class="form-control" name="id_destino" id="idDestinationInput"  aria-describedby="helpIdDestination" placeholder="Id de destino" required>-->
                            <select name="id_hotel" id="idHotelInput" class="form-select" required>
                                <option value="">Selecciona tu hotel</option>
                                <?php foreach ($hotels as $hotelId): ?>
                                    <option value="<?php echo $hotelId; ?>">
                                        <?php echo isset($hotelNames[$hotelId]) ? $hotelNames[$hotelId] : "Hotel Desconocido ($hotelId)"; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <label for="idDestinationInput">Hotel</label>
                        </div>
                        
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-dark border-dark-subtle fw-bold text-white" name="addTour">Crear</button>
                    </div>
                    <div class="modal-footer"></div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Actualización de Excursión -->
<div class="modal fade" id="updateTourModal" tabindex="-1" aria-labelledby="updateTourModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h2 class="modal-title">Actualizar Excursión</h2>
                <button type="button" class="btn-close bg-secondary" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="../controllers/tours/update.php" method="POST">
                    <div class="container mt-4">
                        <!-- Id Excursion (oculto) -->
                        <input type="hidden" id="updateIdExcursionInput" name="id_excursion">
                        
                        <!-- Fecha Excursión -->
                        <div class="form-floating mb-3">
                            <input type="date" class="form-control" id="updateFechaExcursionInput" name="fecha_excursion" required>
                            <label for="updateFechaExcursionInput">Fecha Excursión</label>
                        </div>
                        
                        <!-- Hora Entrada Excursión -->
                        <div class="form-floating mb-3">
                            <input type="time" class="form-control" id="updateHoraEntradaExcursionInput" name="hora_entrada_excursion" required>
                            <label for="updateHoraEntradaExcursionInput">Hora Entrada Excursión</label>
                        </div>
                        
                        <!-- Hora Salida Excursión -->
                        <div class="form-floating mb-3">
                            <input type="time" class="form-control" id="updateHoraSalidaExcursionInput" name="hora_salida_excursion" required>
                            <label for="updateHoraSalidaExcursionInput">Hora Salida Excursión</label>
                        </div>
                        
                        <!-- Descripción -->
                        <div class="form-floating mb-3">
                            <select class="form-select" id="updateDescripcionInput" name="descripcion" required>
                                <option value="" disabled selected>Selecciona una excursión</option>
                                <option value="Excursión a la Playa">Excursión a la Playa</option>
                                <option value="Visita al Volcán">Visita al Volcán</option>
                                <option value="Recorrido por la Ciudad">Recorrido por la Ciudad</option>
                                <option value="Aventura en la Selva">Aventura en la Selva</option>
                                <option value="Tour Cultural">Tour Cultural</option>
                                <option value="Paseo en Barco">Paseo en Barco</option>
                            </select>
                            <label for="updateDescripcionInput">Descripción</label>
                        </div>
                        
                        <!-- Número de Excursionistas -->
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" id="updateNumExcursionistasInput" name="num_excursionistas" placeholder="Número de Excursionistas" required>
                            <label for="updateNumExcursionistasInput">Número de Excursionistas</label>
                        </div>
                        
                        <!-- Email Cliente -->
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="updateEmailClienteInput" name="email_cliente" placeholder="Email Cliente" required>
                            <label for="updateEmailClienteInput">Email Cliente</label>
                        </div>
                        
                        <!-- Id Hotel -->
                        <div class="form-floating mb-3">
                            <select name="id_hotel" id="updateIdHotelInput" class="form-select" required>
                                <option value="">Selecciona un Hotel</option>
                                <?php foreach ($hotels as $hotelId): ?>
                                    <option value="<?php echo $hotelId; ?>">
                                        <?php echo isset($hotelNames[$hotelId]) ? $hotelNames[$hotelId] : "Hotel Desconocido ($hotelId)"; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <label for="updateIdHotelInput">Hotel</label>
                        </div>
                    </div>
                    
                    <!-- Botones de envío y cierre -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-dark border-dark-subtle fw-bold text-white" name="updateTour">Modificar</button>
                    </div>
                    <div class="modal-footer"></div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmación de Eliminación de Excursión -->
<div class="modal fade" id="confirmarEliminacionModal" tabindex="-1" aria-labelledby="confirmarEliminacionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h2 class="modal-title" id="confirmarEliminacionModalLabel">Confirmar Eliminación</h2>
                <button type="button" class="btn-close bg-secondary" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar esta excursión?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btnEliminar">Eliminar</button>
            </div>
        </div>
    </div>
</div>


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

    hideMessage("createTourSuccess", "createTourError");
    hideMessage("updateTourSuccess", "updateTourError");
    hideMessage("deleteTourSuccess", "deleteTourError");

    function abrirModalActualizar(tour) {
        document.querySelector('#updateIdExcursionInput').value = tour.id_excursion || '';
        document.querySelector('#updateFechaExcursionInput').value = tour.fecha_excursion || '';
        document.querySelector('#updateHoraEntradaExcursionInput').value = tour.hora_entrada_excursion || '';
        document.querySelector('#updateHoraSalidaExcursionInput').value = tour.hora_salida_excursion || '';
        document.querySelector('#updateDescripcionInput').value = tour.descripcion || '';
        document.querySelector('#updateNumExcursionistasInput').value = tour.num_excursionistas || '';
        document.querySelector('#updateEmailClienteInput').value = tour.email_cliente || '';
        document.querySelector('#updateIdHotelInput').value = tour.id_hotel || '';

        var modal = new bootstrap.Modal(document.getElementById('updateTourModal'));
        modal.show();
    }

    function confirmarEliminacion(url) {
        document.getElementById('btnEliminar').onclick = function() {
            window.location.href = url;
        };
        var modal = new bootstrap.Modal(document.getElementById('confirmarEliminacionModal'));
        modal.show();
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
