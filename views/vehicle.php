<?php
include '../controllers/vehicles/read.php';
include '../controllers/vehicles/delete.php';
include '../controllers/vehicles/update.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Panel Admin - Vehículos</title>
    <meta name="author" content="PHPOWER" />
    <meta name="description" content="La página de vehículos del panel de administración de Isla Transfer
    sirve para que el administrador pueda gestionar los vehículos en la aplicación web." />
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
<div class="container-fluid mt-4">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a href="dashboard-admin.php" class=" text-secondary text-decoration-none fw-bold fs-3"><i class="bi bi-arrow-return-left"></i></a>
    </nav>
    <div class="container-fluid">
        <h1 class="text-center">Gestión de Vehículos</h1>
    </div>
    <div class="row">
        <div class="col text-start">
            <button type="button" class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#addVehicleModal">
                Nuevo Vehiculo
            </button>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="table-responsive">
                <table class="table table-light table-striped table-hover w-100 h-100">
                    <thead>
                    <tr>
                        <th scope="col">ID Vehiculo</th>
                        <th scope="col">Descripción</th>
                        <th scope="col">Email conductor</th>
                        <th scope="col">Password</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($vehicles as $vehicle) : ?>
                        <tr id="vehicle-<?php echo $vehicle['id_vehicle']; ?>">
                            <td><?php echo $vehicle['id_vehicle']; ?></td>
                            <td><?php echo $vehicle['description']; ?></td>
                            <td><?php echo $vehicle['email_rider']; ?></td>
                            <td><?php echo $vehicle['pass']; ?></td>

                            <td>
                                <div class="btn-group" role="group">
                                    <button onclick="abrirModalActualizar(<?php echo htmlspecialchars(json_encode($vehicle)); ?>)" class="btn btn-sm btn-outline-secondary">Modificar</button>
                                </div>
                                <div class="btn-group" role="group">
                                    <a role="button" class="btn btn-sm btn-outline-danger" href="#" onclick="confirmarEliminacion('../controllers/vehicles/delete.php?id_vehicle=<?php echo $vehicle['id_vehicle']; ?>')">Borrar</a>
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

<!-- Modal de creacion Vehiculo -->
<div class="modal fade" id="addVehicleModal" tabindex="-1" aria-labelledby="addVehicleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Nuevo vehiculo</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="../controllers/vehicles/create.php" method="POST">
                    <div class="container mt-4">
                        <!-- Id Vehiculo -->
                        <div class="form-floating mb-3">
                            <input type="hidden" id="idVehicleInput" name="id_vehicle">
                        </div>
                        <!-- Descripcion -->
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="descriptionInput" name="description" placeholder="Descripcion" required>
                            <label for="descriptionInput">Descripción</label>
                        </div>
                        <!-- Email conductor -->
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="emailRiderInput" name="email_rider" placeholder="Emailconductor">
                            <label for="emailRiderInput">Email conductor</label>
                        </div>
                        <!-- Password -->
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="passwordInput" name="pass" placeholder="Password">
                            <label for="passwordInput">Password</label>
                        </div>
                    </div>
                    <!-- Botones de envio y cierre -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" name="addVehicle">Crear</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de actualizar Vehiculo -->
<div class="modal fade" id="updateVehicleModal" tabindex="-1" aria-labelledby="updateVehicleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Modificar Reserva</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="../controllers/vehicles/update.php" method="POST">
                    <div class="container mt-4">
                        <!-- Id Vehiculo -->
                        <div class="form-floating mb-3">
                            <input type="hidden" id="updateIdVehicleInput" name="id_vehicle">
                        </div>
                        <!-- Descripcion -->
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="updateDescriptionInput" name="description" placeholder="Descripcion" required>
                            <label for="updateDescriptionInput">Descripción</label>
                        </div>
                        <!-- Email conductor -->
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="updateEmailRiderInput" name="email_rider" placeholder="Emailconductor">
                            <label for="updateEmailRiderInput">Email conductor</label>
                        </div>
                        <!-- Password -->
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="updatePasswordInput" name="pass" placeholder="Password">
                            <label for="updatePasswordInput">Password</label>
                        </div>
                    </div>
                    <!-- Botones de envio y cierre -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" name="updateVehicle">Modificar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmación de Eliminación -->
<div class="modal fade" id="confirmarEliminacionModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar este vehículo?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btnEliminar">Eliminar</button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
<script>
    function abrirModalActualizar(vehicle) {
        document.querySelector('#updateIdVehicleInput').value = vehicle.id_vehicle || '';
        document.querySelector('#updateDescriptionInput').value = vehicle.description || '';
        document.querySelector('#updateEmailRiderInput').value = vehicle.email_rider || '';
        document.querySelector('#updatePasswordInput').value = vehicle.pass || '';

        var modal = new bootstrap.Modal(document.getElementById('updateVehicleModal'));
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

</body>
</html>