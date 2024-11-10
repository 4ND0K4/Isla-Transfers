<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$tipo_creador_reserva = isset($_SESSION['admin']) ? 1 : 2; // 1 para admin, 2 para traveler

// Declaraciones de inclusión
require_once __DIR__ . '/../../models/db.php';
require_once __DIR__ . '/../../models/booking.php';
require_once __DIR__ . '/../../models/traveler.php';

// Función para enviar el correo con el localizador
function enviarEmailConLocalizador($email, $localizador, $datosReserva) {
    $mail = new PHPMailer(true);
    try {
        // Configuración de servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';  // Host de Mailtrap
        $mail->SMTPAuth = true;
        $mail->Port = 2525;  // Puerto proporcionado por Mailtrap
        $mail->Username = 'fbe99269247407';  // Usuario de Mailtrap
        $mail->Password = 'fea08499e7cfa6';  // Contraseña de Mailtrap

        $mail->setFrom('reservas@islatransfer.com', 'Isla Transfer');
        $mail->addAddress($email);

        $mail->CharSet = 'UTF-8';

        $mail->isHTML(true);
        $mail->Subject = 'Confirmación de su reserva';

        // Incrusta el logo como imagen embebida
        $mail->addEmbeddedImage(__DIR__ . '/../../assets/img/logo-email.png', 'logo_img');

        // Selecciona la fecha y hora según el tipo de reserva
        if ($datosReserva['id_tipo_reserva'] == 1) {
            $fecha = $datosReserva['fecha_entrada'];
            $hora = $datosReserva['hora_entrada'];
            $tipoReservaTexto = "Aeropuerto-Hotel";
        } elseif ($datosReserva['id_tipo_reserva'] == 2) {
            $fecha = $datosReserva['fecha_vuelo_salida'];
            $hora = $datosReserva['hora_vuelo_salida'];
            $tipoReservaTexto = "Hotel-Aeropuerto";
        } else {
            // Opcionalmente, maneja otros tipos de reserva si existen
            $fecha = "No especificada";
            $hora = "No especificada";
            $tipoReservaTexto = "Tipo de reserva desconocido";
        }

        $mail->Body = '
        <div style="font-family: Arial, sans-serif; color: #333; line-height: 1.6; max-width: 600px; margin: auto; padding: 20px; border: 1px solid #eaeaea; border-radius: 8px;">
            <table width="100%" style="border-collapse: collapse;">
                <tr>
                    <td style="text-align: center; padding-bottom: 5px;">
                        <img src="cid:logo_img" alt="Isla Transfer Logo" style="max-width: 120px; border-radius: 8px;">                       
                    </td>
                </tr>
                <tr>
                    <td>
                        <h1 style="color: #333; font-size: 22px; margin-bottom: 20px; text-align: center;">Detalles de su Reserva</h1>
                        <p style="font-size: 16px; color: #555; margin-bottom: 10px; text-align: center;">¡Gracias por reservar con Isla Transfer! A continuación le presentamos los detalles de su reserva:</p>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 20px; background-color: #f9f9f9; border: 1px solid #eaeaea; border-radius: 8px;">
                        <p><strong style="color: #333;">Localizador:</strong> <span style="color: #555;">' . $localizador . '</span></p>
                        <p><strong style="color: #333;">Trayecto:</strong> <span style="color: #555;">' . $tipoReservaTexto . '</span></p>
                        <p><strong style="color: #333;">Hotel:</strong> <span style="color: #555;">' . $datosReserva['id_hotel'] . '</span></p>
                        <p><strong style="color: #333;">Fecha:</strong> <span style="color: #555;">' . $fecha . '</span></p>
                        <p><strong style="color: #333;">Hora:</strong> <span style="color: #555;">' . $hora . '</span></p>
                        <p><strong style="color: #333;">Origen de vuelo:</strong> <span style="color: #555;">' . $datosReserva['origen_vuelo_entrada'] . '</span></p>
                        <p><strong style="color: #333;">Número de vuelo:</strong> <span style="color: #555;">' . $datosReserva['numero_vuelo_entrada'] . '</span></p>
                        <p><strong style="color: #333;">Número de viajeros:</strong> <span style="color: #555;">' . $datosReserva['num_viajeros'] . '</span></p>
                    </td>
                </tr>
                <tr>
                    <td style="padding-top: 20px; text-align: center;">
                        <p style="font-size: 14px; color: #555;">Gracias por confiar en Isla Transfer. Si tiene alguna pregunta o necesita ayuda, no dude en contactarnos.</p>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center; padding-top: 20px; font-size: 12px; color: #999;">
                        © 2024 Isla Transfer. Todos los derechos reservados.
                    </td>
                </tr>
            </table>
        </div>';

        $mail->send();
    } catch (Exception $e) {
        error_log('Error al enviar el correo: ' . $mail->ErrorInfo);
    }
}


// Conectar con la base de datos
$db = db_connect();
if (!$db) {
    $_SESSION['create_error'] = "Error al conectar con la base de datos.";
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

// Crear instancia de Traveler (para buscar datos del usuario)
$travelerModel = new Traveler($db);

// Función que crea un código alfanumérico aleatorio de 10 dígitos (empleada en el localizador)
function generateLocator($length = 10) {
    return substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
}

// Variable que regula que el usuario traveler no pueda modificar fechas con menos de 2 días
$fechaMinima = (new DateTime())->modify('+2 days')->format('Y-m-d H:i:s');

// Verificar si la solicitud es de tipo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $locator = generateLocator();
    $id_destino = $_POST['id_destino'] ?? null;
    $email_cliente = $_POST['email_cliente'];
    $horaVueloSalida = $_POST['hora_vuelo_salida'] ?? null;
    $fechaEntrada = $_POST['fecha_entrada'] ?? null;
    $fechaVueloSalida = $_POST['fecha_vuelo_salida'] ?? null;

    // Validar si el email del cliente de la reserva existe en la base de datos
    $traveler = $travelerModel->findByEmail($email_cliente);
    if (!$traveler) {
        $_SESSION['create_email_error'] = "El email ingresado no corresponde a ningún viajero en la base de datos.";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    // Validación para asegurar que id_destino sea un id_hotel válido
    $validHotelStmt = $db->prepare("SELECT COUNT(*) FROM tranfer_hotel WHERE id_hotel = :id_destino");
    $validHotelStmt->execute([':id_destino' => $id_destino]);
    $isValidHotel = $validHotelStmt->fetchColumn() > 0;

    if (!$isValidHotel) {
        $_SESSION['create_hotel_error'] = "El hotel ingresado no corresponde a ningún hotel en la base de datos.";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    // Si id_hotel no está definido, usa el valor de id_destino
    $id_hotel = !empty($_POST['id_hotel']) ? $_POST['id_hotel'] : $id_destino;

    // Resta tres horas a la hora de vuelo de salida, si está definida
    if ($horaVueloSalida) {
        $horaVueloSalidaObj = new DateTime($horaVueloSalida);
        $horaVueloSalidaObj->modify('-3 hours');
        $horaVueloSalida = $horaVueloSalidaObj->format('H:i:s');
    }

    // Validación para los usuarios tipo traveler no puedan crear reservas con menos de 48 horas
    if (isset($_SESSION['travelerUser'])) {
        $fechaMinima = (new DateTime())->modify('+2 days')->format('Y-m-d');
        if ($fechaEntrada && $fechaEntrada < $fechaMinima) {
            $_SESSION['create_48_error'] = "No puede realizar reservas con menos de 48 horas de antelación.";
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit;
        }
        if ($fechaVueloSalida && $fechaVueloSalida < $fechaMinima) {
            $_SESSION['create_48_error'] = "No puede realizar reservas con menos de 48 horas de antelación.";
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit;
        }
    }

    // Preparar los datos para la creación
    $data = [
        'localizador' => $locator,
        'id_hotel' => $id_hotel,
        'id_tipo_reserva' => $_POST['id_tipo_reserva'],
        'email_cliente' => $email_cliente,
        'fecha_reserva' => date('Y-m-d H:i:s'),
        'fecha_modificacion' => date('Y-m-d H:i:s'),
        'id_destino' => $id_destino,
        'fecha_entrada' => $fechaEntrada,
        'hora_entrada' => $_POST['hora_entrada'] ?? null,
        'numero_vuelo_entrada' => $_POST['numero_vuelo_entrada'] ?? null,
        'origen_vuelo_entrada' => $_POST['origen_vuelo_entrada'] ?? null,
        'hora_vuelo_salida' => $horaVueloSalida ?? null,
        'fecha_vuelo_salida' => $fechaVueloSalida,
        'num_viajeros' => $_POST['num_viajeros'],
        'id_vehiculo' => $_POST['id_vehiculo'] ?? 1,
        'tipo_creador_reserva' => $tipo_creador_reserva ?? null
    ];

    // Crear la reserva
    $booking = new Booking($db);
    $createResult = false;

    if ($data['id_tipo_reserva'] === 'idayvuelta') {
        $data['id_tipo_reserva'] = 1;
        $data['localizador'] = generateLocator();
        $createResult = $booking->addBooking($data);

        // Enviar correo con el localizador
        enviarEmailConLocalizador($data['email_cliente'], $data['localizador'], $data);

        $data['id_tipo_reserva'] = 2;
        $data['localizador'] = generateLocator();
        $createResult = $createResult && $booking->addBooking($data);

        // Enviar correo con el localizador
        enviarEmailConLocalizador($data['email_cliente'], $data['localizador'], $data);
    } else {
        $data['localizador'] = generateLocator();
        $createResult = $booking->addBooking($data);

        // Enviar correo con el localizador
        enviarEmailConLocalizador($data['email_cliente'], $data['localizador'], $data);
    }

    if ($createResult) {
        $_SESSION['create_booking_success'] = "Reserva creada correctamente.";
    } else {
        $_SESSION['create_booking_error'] = "Error al crear la reserva.";
    }

    // Redirigir a la página de origen para mostrar el mensaje
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}
?>

