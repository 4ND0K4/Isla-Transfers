<?php
session_start();
include '../controllers/hotels/read.php';
require_once __DIR__ . '/../models/db.php';
//Cambia los id_zona por descriptores
$db = db_connect();
$zonesStmt = $db->prepare("SELECT Id_zona FROM transfer_zona");
$zonesStmt->execute();
$zones= $zonesStmt->fetchAll(PDO::FETCH_COLUMN);
// Array de nombres de hoteles asignados manualmente
$zoneNames = [
    1 => 'Norte',
    2 => 'Sur',
    3 => 'Metropolitana',
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Panel Admin - Hoteles</title>
    <meta name="author" content="PHPOWER" />
    <meta name="description" content="La página de clientes corporativos (hoteles) del panel de administración de Isla Transfer
    sirve para que el administrador pueda gestionar su creación y mantenimiento en la aplicación web." />
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+Paaji+2:wght@400..800&family=Caveat&family=Roboto+Flex:opsz@8..144&display=swap" rel="stylesheet">
    <!-- Enlaces CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Enlaces Hojas Estilo-->
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
                    <!-- Mensajes creación de hotel --> 
                    <div class="d-flex justify-content-end">
                        <div class="col text-center">
                            <?php if (isset($_SESSION['create_hotel_success'])): ?>
                                <div id="createHotelSuccess" class="alert alert-success fs-6" role="alert">
                                    <?php echo $_SESSION['create_hotel_success']; ?>
                                </div>
                                <?php unset($_SESSION['create_hotel_success']); ?>
                            <?php elseif (isset($_SESSION['create_hotel_error'])): ?>
                                <div id="createHotelError" class="alert alert-danger fs-6" role="alert">
                                    <?php echo $_SESSION['create_hotel_error']; ?>
                                </div>
                                <?php unset($_SESSION['create_hotel_error']); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                
                    <!-- Mensajes modificación de hotel -->    
                    <div class="d-flex justify-content-end">
                        <div class="col text-center">
                            <?php if (isset($_SESSION['update_hotel_success'])): ?>
                                <div id="updateHotelSuccess" class="alert alert-success fs-6" role="alert">
                                    <?php echo $_SESSION['update_hotel_success']; ?>
                                </div>
                                <?php unset($_SESSION['update_hotel_success']); ?>
                            <?php elseif (isset($_SESSION['update_hotel_error'])): ?>
                                <div id="updateHotelError" class="alert alert-danger fs-6" role="alert">
                                    <?php echo $_SESSION['update_hotel_error']; ?>
                                </div>
                                <?php unset($_SESSION['update_hotel_error']); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                

                    <!-- Mensajes borrado de hotel -->   
                    <div class="d-flex justify-content-end">
                        <div class="col text-center">
                            <?php if (isset($_SESSION['delete_hotel_success'])): ?>
                                <div id="deleteHotelSuccess" class="alert alert-success fs-6" role="alert">
                                    <?php echo $_SESSION['delete_hotel_success']; ?>
                                </div>
                                <?php unset($_SESSION['delete_hotel_success']); ?>
                            <?php elseif (isset($_SESSION['delete_hotel_error'])): ?>
                                <div id="deleteHotelError" class="alert alert-danger fs-6" role="alert">
                                    <?php echo $_SESSION['delete_hotel_error']; ?>
                                </div>
                                <?php unset($_SESSION['delete_hotel_error']); ?>
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
            <h1 class="text-center fw-bold text-secondary">Gestión de Hoteles</h1>
        </div>
        <!-- Botón creación --> 
        <div class="row">
            <div class="col text-start pt-4 pb-2">
                <button type="button" class="btn btn-outline-success fw-bold" data-bs-toggle="modal" data-bs-target="#addHotelModal">Nuevo Hotel</button>
            </div>  
        </div>          
        <!-- Tabla -->
        <div class="row">
            <div class="col">
                <div class="table-responsive">
                    <table class="table table-success table-striped table-hover w-100 h-100">
                        <thead>
                        <tr>
                            <th scope="col">Hotel</th>
                            <th scope="col">Zona</th>
                            <th scope="col">Comisión</th>
                            <th scope="col">Usuario</th>
                            <th scope="col">Password</th>
                            <th scope="col"><!--Botones--></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($hotels as $hotel) : ?>
                            <tr id="hotel-<?php echo $hotel['idHotel']; ?>">
                                <td><?php echo $hotel['idHotel']; ?></td>
                                <td><?php echo $hotel['idZone']; ?></td>
                                <td><?php echo $hotel['commission']; ?></td>
                                <td><?php echo $hotel['user']; ?></td>
                                <td><?php echo $hotel['pass']; ?></td>

                                <td>
                                    <!-- BOTÓN ACTUALIZAR -->
                                    <div class="btn-group" role="group">
                                        <button onclick="abrirModalActualizar(<?php echo htmlspecialchars(json_encode($hotel)); ?>)" class="btn btn-sm btn-outline-secondary">Modificar</button>
                                    </div>
                                    <!-- Botón eliminar -->
                                    <div class="btn-group" role="group">
                                        <a role="button" class="btn btn-sm btn-outline-danger" href="#" onclick="confirmarEliminacion('../controllers/hotels/delete.php?idHotel=<?php echo $hotel['idHotel']; ?>')">Borrar</a>
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

<!-- ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////// MODALS //////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->

<!-- Modal de creacion Hotel -->
<div class="modal fade" id="addHotelModal" tabindex="-1" aria-labelledby="addHotelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success-subtle">
                <h2 class="modal-title text-center">Añade un nuevo hotel</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="../controllers/hotels/register.php" method="POST">
                    <div class="container mt-4">
                        <!-- Id Hotel -->
                        <div class="form-floating mb-3">
                            <input type="hidden" name="idHotel" id="idHotelInput">
                        </div>
                        <!-- ID ZONA -->
                        <select name="idZone" id="idZoneInput" class="form-select" required>
                            <option value="">Selecciona la zona del hotel</option>
                            <?php foreach ($zones as $zoneId): ?>
                                <option value="<?php echo $zoneId; ?>">
                                    <?php echo isset($zoneNames[$zoneId]) ? $zoneNames[$zoneId] : "Zona Desconocida ($zoneId)"; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <!-- Comisión -->
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" name="commission" id="commissionInput" placeholder="Comisión" required>
                            <label for="commissionInput">Comisión</label>
                        </div>

                        <!-- Usuario -->
                        <div class="form-floating mb-3">
                            <input type="hidden" name="user" id="userInput">
                        </div>

                        <!-- Password -->
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="pass" id="passInput" placeholder="Password" required>
                            <label for="hotelPassInput">Password</label>
                        </div>
                    </div>
                    <!-- Botones de envio y cierre -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success border-info-success fw-bold text-white" name="addHotel">Crear</button>
                    </div>
                    <div class="modal-footer"></div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de actualizar Hotel -->
<div class="modal fade" id="updateHotelModal" tabindex="-1" aria-labelledby="updateHotelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success-subtle">
                <h2 class="modal-title">Actualiza el hotel</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="../controllers/hotels/update.php" method="POST">
                    <div class="container mt-4">
                        <!-- Id Hotel -->
                        <div class="form-floating mb-3">
                            <input type="hidden" name="idHotel" id="updateIdHotelInput">
                        </div>
                        <!-- ID Zona -->
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" name="idZone" id="updateIdZoneInput"  placeholder="Id zona" readonly>
                            <label for="updateIdZoneInput">Zona</label>
                        </div>
                        <!-- Comision -->
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" name="commission" id="updateCommissionInput"  placeholder="Comision">
                            <label for="updateCommissionInput">Comision</label>
                        </div>
                        <!-- Usuario -->
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" name="user" id="updateUserInput"  placeholder="Usuario" readonly>
                            <label for="updateUserInput">Usuario</label>
                        </div>
                        <!-- Password -->
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="pass" id="updatePassInput" placeholder="Password">
                            <label for="updatePassInput">Password</label>
                        </div>
                    </div>
                    <!-- Botones de envio y cierre -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success border-info-success fw-bold text-white" name="updateHotel">Modificar</button>
                    </div>
                    <div class="modal-footer"></div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmación de Eliminación -->
<div class="modal fade" id="confirmarEliminacionModal" tabindex="-1" aria-labelledby="confirmarEliminacionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success-subtle">
                <h2 class="modal-title" id="confirmarEliminacionModalLabel">Confirmar eliminación</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar este hotel?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btnEliminar" data-url="">Eliminar</button>
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
hideMessage("createHotelSuccess", "createHotelError");
hideMessage("updateHotelSuccess", "updateHotelError");
hideMessage("deleteHotelSuccess", "deleteHotelError");



    function abrirModalActualizar(hotel) {
        document.querySelector('#updateIdHotelInput').value = hotel.idHotel || '';
        document.querySelector('#updateIdZoneInput').value = hotel.idZone || '';
        document.querySelector('#updateCommissionInput').value = hotel.commission || '';
        document.querySelector('#updateUserInput').value = hotel.user || '';
        document.querySelector('#updatePassInput').value = '';

        var modal = new bootstrap.Modal(document.getElementById('updateHotelModal'));
        modal.show();
    }


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
<!-- Archivos para accionar los modales -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
