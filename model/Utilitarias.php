<?php
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require 'vendor/autoload.php';
class Utilitarias
{
    public static function enviarEmail($destino, $asunto, $mensaje, $adjunto = [], $error = false)
    {
        if (empty($destino)) {
            $msj = 'Correo vacio.';
            if ($error) {
                return Flight::json(respuesta('99', $msj));
            } else {
                return false;
            }
        }
        $mail = new PHPMailer(true);
        $mail->SetLanguage("es", 'phpmailer/language/');
        try {
            $mail->SMTPDebug = false;
            $mail->isSMTP();
            $mail->Host = SERVER_EMAIL_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = SERVER_EMAIL_USERNAME;
            $mail->Password = SERVER_EMAIL_PASSWORD;
            $mail->Port = SERVER_EMAIL_PORT;
            if (SERVER_EMAIL_SECURE != '' && defined('SERVER_EMAIL_SECURE')) {
                $mail->SMTPSecure = SERVER_EMAIL_SECURE;
            }
            $mail->From = EMAIL_ADM_PLATAFORMA;
            $mail->FromName = NOMBRE_SISTEMA;
            //
            if (TIPO_AMBIENTE == 'pruebas') {
                $mail->AddAddress('jhonatan.soporte98@gmail.com');
            } else {
                if (is_array($destino)) {

                    foreach (array_unique($destino) as $dest) {
                        if (!empty($dest)) {
                            $dest = trim($dest);
                            $dest = str_replace(" ", "", $dest);
                            $mail->AddAddress($dest);
                        }
                    }
                } else {
                    if (!empty($destino)) {
                        $destino = trim($destino);
                        $destino = str_replace(" ", "", $destino);
                        $mail->AddAddress($destino);
                    }
                }
            }
            $mail->WordWrap = 50;
            if (!empty($adjunto)) {
                foreach ($adjunto as $attach) {
                    $mail->AddAttachment($attach);
                }
            }
            $mail->IsHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = "=?UTF-8?B?" . base64_encode($asunto) . "=?=";
            $footer = "<hr></hr><h4>Por favor, no responda a este correo, correo generado automáticamente.</h4>";
            $footer .= "<h3>Sistema de registro de PQR.</h3>";
            $mail->Body = $mensaje . $footer;
            $mail->Send();
            return true;
        } catch (Exception $e) {
            $msj = '1-Error enviando el recibo al email. Detalle del error => ' . $mail->ErrorInfo . ' \n';
            if ($error) {
                return Flight::json(respuesta('99', $msj));
            } else {
                return false;
            }
        }
    }
    public static function getEmail($db, $id)
    {
        return $db->consultarRegistro("SELECT email FROM usuario WHERE id=$id", [], 'email');
    }
    public static function datosUsuario($db, $id)
    {
        return $db->consultarRegistro("SELECT id, CONCAT(nombres, ' ', apellidos) as nombre, email, genero FROM usuario WHERE id=$id", []);
    }
}
function generoCorreo($genero)
{
    switch ($genero) {
        case 'F':
            $text = "Apreciada ";
            break;
        case 'M':
            $text = "Apreciado ";
            break;

        default:
            $text = "Apreciad@ ";
            break;
    }
    return $text;
}
function generarAleatorioClave($longitud = 6)
{
    $caracteres = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
    $claveValida = 'NO';
    while ($claveValida === 'NO') {
        $index = 0;
        $clave = $tmp = '';
        for ($i = 0; $i < $longitud; $i++) {
            $tmp = $caracteres[random_int(0, strlen($caracteres) - 1)];
            if (is_numeric($tmp)) {
                $index++;
            }
            $clave .= $tmp;
        }
        if ($index == 3) {
            $claveValida = 'SI';
        }
        if (strpos($clave, "0") == 0) {
            $claveValida = 'NO';
        }
    }
    return $clave;
}