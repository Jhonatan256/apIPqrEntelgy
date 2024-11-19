<?php
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

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
            $footer .= "<h3>Sistema de registro de PQR - Entelgy Colombia.</h3>";
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
function imprimir($datos)
{
    echo "<pre>";
    print_r($datos);
    echo "</pre>";
    die();
}
function calcularPorcentaje($ticket, $valor =false)
{
    date_default_timezone_set('America/Bogota');
    $validDate = false;
    $fechaInicial = date('Y-m-d');
    //Fechas a calcular
    $fechasCalcular[] = $fechaInicial;
    $fecha = explode(" ", $ticket['fechaCreacion']);
    $fechaCreacion = $fecha[0];
    $horaCreacion = $fecha[1];
    if ($fechaInicial > $fechaCreacion) {
        while ($validDate === false) {
            $fechaInicial = date("Y-m-d", strtotime($fechaInicial . "- 1 days"));
            $fechasCalcular[] = $fechaInicial;
            if ($fechaInicial == $fechaCreacion) {
                $validDate = true;
            }
        }
    }
    foreach ($fechasCalcular as $key => $value) {
        $diasemana = saberDia($value);
        if ($diasemana == 'Sabado' || $diasemana == 'Domingo') {
            unset($fechasCalcular[$key]);
        }
    }
    $horas = 0;
    $horaActual = date('H:i:s');
    $fechasCalcular = array_reverse($fechasCalcular);
    foreach ($fechasCalcular as $key => $value) {
        if ($value == $fechaCreacion) {
            $calculo = true;
            if ($horaCreacion >= '08:00:00' && $horaCreacion <= '17:00:00') {
                if (date('Y-m-d') == $value) {
                    if ($horaActual >= '08:00:00' && $horaActual <= '17:00:00') {
                        $fechaUno = date_create($value . " " . $horaCreacion);
                        $fechaDos = date_create("$value $horaActual");
                    } else {
                        $calculo = false;
                    }
                } else {
                    $fechaUno = date_create($value . " " . $horaCreacion);
                    $fechaDos = date_create("$value 17:00:00");
                }
            } else {
                if (date('Y-m-d') == $value && ($horaActual >= '08:00:00' && $horaActual <= '17:00:00')) {
                    $fechaUno = date_create("$value 08:00:00");
                    $fechaDos = date_create("$value $horaActual");
                } else {
                    $calculo = false;
                }
            }
            if ($calculo) {
                $diferencia = date_diff($fechaUno, $fechaDos);
                $minutos = $diferencia->days * 24 * 60;
                $minutos += $diferencia->h * 60;
                $minutos += $diferencia->i;
                $horas = $horas + ($minutos / 60);
            }
        } else {
            $calculo = true;
            if (date('Y-m-d') == $value) {
                if ($horaActual > '17:00:00' && $horaActual < '24:00:00') {
                    $horaActual = '17:00:00';
                }
                if ($horaActual > '24:00:00' && $horaActual < '08:00:00') {
                    $horaActual = '08:00:00';
                }
                if ($horaActual >= '08:00:00' && $horaActual <= '17:00:00') {
                    $fechaUno = date_create("$value 08:00:00");
                    $fechaDos = date_create("$value $horaActual");
                } else {
                    $calculo = false;
                }
            } else {
                $fechaUno = date_create("$value 08:00:00");
                $fechaDos = date_create("$value 17:00:00");
            }
            if ($calculo) {
                $diferencia = date_diff($fechaUno, $fechaDos);
                $minutos = $diferencia->days * 24 * 60;
                $minutos += $diferencia->h * 60;
                $minutos += $diferencia->i;
                $horas = $horas + ($minutos / 60);
            }
        }
    }
    $ans = 40;
    try {
        $salida['progreso'] = calcularBarraProgreso($horas, $ans, 100);
    } catch (Exception $e) {
        $salida['progreso'] = $horas;
    }
    if($valor){
        return $salida['progreso'];
    }
    $calculo = $salida['progreso'] = str_replace(',', '', $salida['progreso']);
    if ($calculo < 40) {
        $salida['color'] = '#007bff';
        $salida['alerta'] = 'BAJA';
    }
    if ($calculo > 40 && $salida['progreso'] < 85) {
        $salida['color'] = '#f6fd12';
        $salida['alerta'] = 'MEDIA';
    }
    if ($calculo > 90) {
        $salida['color'] = '#e51831';
        $salida['alerta'] = ($salida['progreso'] > 100) ? 'MAXIMA' : 'ALTA';
    }
    return $salida;
}
function saberDia($nombredia)
{
    $dias[0] = 'Domingo';
    $dias[1] = 'Lunes';
    $dias[2] = 'Martes';
    $dias[3] = 'Miercoles';
    $dias[4] = 'Jueves';
    $dias[5] = 'Viernes';
    $dias[6] = 'Sabado';
    $dias[7] = 'Domingo';
    return $dias[date('N', strtotime($nombredia))];
}
function calcularBarraProgreso($valor, $ans, $porcentaje)
{
    if ($valor > $ans) {
        $valor = $valor / $ans;
        $valor = $valor * 100;
    } else {
        $ans = empty($ans) ? 8 : $ans;
        $valor = ($valor / $porcentaje) * $ans;
    }
    return number_format($valor, 2);
}
