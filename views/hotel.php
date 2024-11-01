<?php
include '../controllers/hotels/read.php';
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
<div class="container-fluid mt-4">
    <!-- NAV -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a href="dashboard-admin.php" class=" text-secondary text-decoration-none fw-bold fs-3"><i class="bi bi-arrow-return-left"></i></a>
    </nav>
    <div class="container-fluid">
        <!-- TÍTULO -->
        <h1 class="text-center">Gestión de Clientes corporativos (Hoteles)</h1>
    </div>
    <div class="row">
        <div class="col text-start">
            <!-- BOTÓN CREACIÓN CLIENTE CORPORATIVO -->
            <button type="button" class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#addHotelModal">
                Nuevo Cliente Corporativo
            </button>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="table-responsive">
                <table class="table table-light table-striped table-hover w-100 h-100">
                    <thead>
                    <tr>
                        <th scope="col">Hotel</th>
                        <th scope="col">Zona</th>
                        <th scope="col">Comisión</th>
                        <th scope="col">Usuario</th>
                        <th scope="col">Password</th>
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

<!-- Modal de creacion Hotel -->
<div class="modal fade" id="addHotelModal" tabindex="-1" aria-labelledby="addHotelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title text-center">Nuevo Cliente Corporativo</h2>
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
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" name="idZone" id="idZoneInput" placeholder="Zona">
                            <label for="idZoneInput">Zona</label>
                        </div>
                        <!-- Comisión -->
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" name="commission" id="commissionInput" placeholder="Comisión">
                            <label for="commissionInput">Comisión</label>
                        </div>
                        <!-- Usuario -->
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" name="user" id="userInput" placeholder="Usuario">
                            <label for="hotelUserInput">Usuario</label>
                        </div>
                        <!-- Password -->
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="pass" id="passInput" placeholder="Password">
                            <label for="hotelPassInput">Password</label>
                        </div>
                    </div>
                    <!-- Botones de envio y cierre -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" name="addHotel">Crear</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de actualizar Hotel -->
<div class="modal fade" id="updateHotelModal" tabindex="-1" aria-labelledby="updateHotelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Modificar Cliente Corporativo</h2>
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
                            <input type="number" class="form-control" name="idZone" id="updateIdZoneInput"  placeholder="Id zona">
                            <label for="updateIdZoneInput">Zona</label>
                        </div>
                        <!-- Comision -->
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" name="commission" id="updateCommissionInput"  placeholder="Comision">
                            <label for="updateCommissionInput">Comision</label>
                        </div>
                        <!-- Usuario -->
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" name="user" id="updateUserInput"  placeholder="Usuario">
                            <label for="updateUserInput">Usuario</label>
                        </div>
                        <!-- Password -->
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="pass" id="updatePassInput" placeholder="Password">
                            <label for="updatePassInput">Password</label>
                        </div>
                    </div>
                    <!-- Botones de envio y cierre -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" name="updateHotel">Modificar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmación de Eliminación -->
<div class="modal fade" id="confirmarEliminacionModal" tabindex="-1" aria-labelledby="confirmarEliminacionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmarEliminacionModalLabel">Confirmar eliminación</h5>
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




<script>
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
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
