<?php
require_once __DIR__ . "/db.php";

class Booking
{
    private $conn;
    private $table = 'transfer_reservas';

    public $Id_reserva;
    public $Localizador;
    public $Id_hotel;
    public $Id_tipo_reserva;
    public $Email_cliente;
    public $Fecha_reserva;
    public $Fecha_modificacion;
    public $Id_destino;
    public $Fecha_entrada;
    public $Hora_entrada;
    public $Numero_vuelo_entrada;
    public $Origen_vuelo_entrada;
    public $Hora_vuelo_salida;
    public $Fecha_vuelo_salida;
    public $Num_viajeros;
    public $Id_vehiculo;

    // Constructor que recibe la conexión a la base de datos
    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllBookings()
    {
        return db_query_fetchall('SELECT * FROM ' . $this->table);
    }

    public function getBookingsByEmail($email)
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE email_cliente = :email';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBookingsByType($Id_tipo_reserva = null) { //Diferencia en la tabla las reservas según id_tipo_reserva
        $query = "SELECT * FROM transfer_reservas";

        if ($Id_tipo_reserva) {
            $query .= " WHERE Id_tipo_reserva = :id_tipo_reserva";
        }

        $stmt = $this->conn->prepare($query);

        if ($Id_tipo_reserva) {
            $stmt->bindParam(':id_tipo_reserva', $Id_tipo_reserva, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBookingById($id_reserva)
    {
        try {
            $query = 'SELECT * FROM ' . $this->table . ' WHERE Id_reserva = :id_reserva';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_reserva', $id_reserva, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error en getBookingById: ' . $e->getMessage());
            return false;
        }
    }


    public function addBooking($data)
    {
        try {
            $query = 'INSERT INTO ' . $this->table . ' (Localizador, Id_hotel, Id_tipo_reserva, Email_cliente, Fecha_reserva, Fecha_modificacion, Id_destino, Fecha_entrada, Hora_entrada, Numero_vuelo_entrada, Origen_vuelo_entrada, Hora_vuelo_salida, Fecha_vuelo_salida, Num_viajeros, Id_vehiculo, tipo_creador_reserva) 
          VALUES (:localizador, :id_hotel, :id_tipo_reserva, :email_cliente, :fecha_reserva, :fecha_modificacion, :id_destino, :fecha_entrada, :hora_entrada, :numero_vuelo_entrada, :origen_vuelo_entrada, :hora_vuelo_salida, :fecha_vuelo_salida, :num_viajeros, :id_vehiculo, :tipo_creador_reserva)';

            $stmt = $this->conn->prepare($query);

            // Asocia los valores de $data a los parámetros en la consulta
            $stmt->bindParam(":localizador", $data['localizador']);
            $stmt->bindParam(":id_hotel", $data['id_hotel']);
            $stmt->bindParam(":id_tipo_reserva", $data['id_tipo_reserva']);
            $stmt->bindParam(":email_cliente", $data['email_cliente']);
            $stmt->bindParam(":fecha_reserva", $data['fecha_reserva']);
            $stmt->bindParam(":fecha_modificacion", $data['fecha_modificacion']);
            $stmt->bindParam(":id_destino", $data['id_destino']);
            $stmt->bindParam(":fecha_entrada", $data['fecha_entrada']);
            $stmt->bindParam(":hora_entrada", $data['hora_entrada']);
            $stmt->bindParam(":numero_vuelo_entrada", $data['numero_vuelo_entrada']);
            $stmt->bindParam(":origen_vuelo_entrada", $data['origen_vuelo_entrada']);
            $stmt->bindParam(":hora_vuelo_salida", $data['hora_vuelo_salida']);
            $stmt->bindParam(":fecha_vuelo_salida", $data['fecha_vuelo_salida']);
            $stmt->bindParam(":num_viajeros", $data['num_viajeros']);
            $stmt->bindParam(":id_vehiculo", $data['id_vehiculo']);
            $stmt->bindParam(":tipo_creador_reserva", $data['tipo_creador_reserva']); // Nuevo campo

            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            error_log('Error en addBooking: ' . $e->getMessage());
            return false;
        }
    }


    public function updateBooking($data)
    {
        try {
            $query = 'UPDATE ' . $this->table . ' 
                  SET Localizador = :localizador, Id_hotel = :id_hotel, Id_tipo_reserva = :id_tipo_reserva, 
                      Email_cliente = :email_cliente, Fecha_modificacion = :fecha_modificacion, 
                      Id_destino = :id_destino, Fecha_entrada = :fecha_entrada, Hora_entrada = :hora_entrada, 
                      Numero_vuelo_entrada = :numero_vuelo_entrada, Origen_vuelo_entrada = :origen_vuelo_entrada, 
                      Hora_vuelo_salida = :hora_vuelo_salida, Fecha_vuelo_salida = :fecha_vuelo_salida, 
                      Num_viajeros = :num_viajeros, Id_vehiculo = :id_vehiculo, tipo_creador_reserva = :tipo_creador_reserva
                  WHERE Id_reserva = :id_reserva';

            $stmt = $this->conn->prepare($query);

            // Asocia los valores de $data a los parámetros en la consulta
            $stmt->bindParam(":id_reserva", $data['id_reserva']);
            $stmt->bindParam(":localizador", $data['localizador']);
            $stmt->bindParam(":id_hotel", $data['id_hotel']);
            $stmt->bindParam(":id_tipo_reserva", $data['id_tipo_reserva']);
            $stmt->bindParam(":email_cliente", $data['email_cliente']);
            $stmt->bindParam(":fecha_modificacion", $data['fecha_modificacion']);
            $stmt->bindParam(":id_destino", $data['id_destino']);
            $stmt->bindParam(":fecha_entrada", $data['fecha_entrada']);
            $stmt->bindParam(":hora_entrada", $data['hora_entrada']);
            $stmt->bindParam(":numero_vuelo_entrada", $data['numero_vuelo_entrada']);
            $stmt->bindParam(":origen_vuelo_entrada", $data['origen_vuelo_entrada']);
            $stmt->bindParam(":hora_vuelo_salida", $data['hora_vuelo_salida']);
            $stmt->bindParam(":fecha_vuelo_salida", $data['fecha_vuelo_salida']);
            $stmt->bindParam(":num_viajeros", $data['num_viajeros']);
            $stmt->bindParam(":id_vehiculo", $data['id_vehiculo']);
            $stmt->bindParam(":tipo_creador_reserva", $data['tipo_creador_reserva']); // Nuevo campo

            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            error_log('Error en updateBooking: ' . $e->getMessage());
            return false;
        }
    }

    public function deleteBooking($id_booking)
    {
        $query = 'DELETE FROM transfer_reservas WHERE id_reserva = :id_reserva';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_reserva', $id_booking, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>