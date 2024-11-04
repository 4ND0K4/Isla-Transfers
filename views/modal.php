<!--////////////////////////////////RESERVAS / BOOKINGS/////////////////////////////////////////////-->
<?php
$hotelsStmt = $db->prepare("SELECT Id_hotel FROM tranfer_hotel");
$hotelsStmt->execute();
$hotels = $hotelsStmt->fetchAll(PDO::FETCH_COLUMN);

// Array de nombres de hoteles asignados manualmente
$hotelNames = [
    1 => 'Hotel Norte',
    2 => 'Hotel Sur',
    3 => 'Hotel Este',
    4 => 'Hotel Oeste'
];
?>
<!--Modal creacion Reservas-->
<div class="modal fade" id="addBookingModal" tabindex="-1" aria-labelledby="addBookingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h2 class="modal-title">Nueva Reserva</h2>
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
                            <input type="email" class="form-control" name="email_cliente" id="emailClientInput" aria-describedby="helpEmailClient"
                                   placeholder="Email del cliente"
                                   value="<?php echo htmlspecialchars($emailCliente); ?>"
                                <?php echo $isTraveler ? 'readonly' : ''; ?>
                                   required>
                            <label for="emailClientInput">Email del cliente</label>
                        </div>
                        <!-- Id Vehículo -->
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" name="id_vehiculo" id="idVehicleInput" aria-describedby="helpIdVehicle"  placeholder="Id vehiculo">
                            <label for="idVehicleInput">Vehículo</label>
                        </div>
                    </div>

                    <!-- Botones de envio y cierre -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-warning" name="addBooking">Crear</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
