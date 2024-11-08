<?php
class UtilitariasController
{
    public static function enviarEmail($destino, $asunto, $mensaje, $adjunto = array())
    {
        include_once dirname(__DIR__) . '/includes/PHPMailer/src/Exception.php';
        include_once dirname(__DIR__) . '/includes/PHPMailer/src/PHPMailer.php';
        include_once dirname(__DIR__) . '/includes/PHPMailer/src/SMTP.php';
        $_SESSION['erroremail'] = '';
        if (empty($destino)) {
            $_SESSION['erroremail'] = 'Correo vacio.' . $_SESSION['modulo'];
            error_log($_SESSION['erroremail']);
            return $_SESSION['erroremail'];
        }

        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->PluginDir = 'phpmailer/';
        $mail->SetLanguage("es", 'phpmailer/language/');
        if (!defined('SERVER_EMAIL_HOST') || !defined('SERVER_EMAIL_PORT') || !defined('SERVER_EMAIL_USERNAME') || !defined('SERVER_EMAIL_PASSWORD') || !defined('EMAIL_ADM_PLATAFORMA')) {
            $_SESSION['erroremail'] = 'No se ha configurado el servicio SMTP';
            error_log($_SESSION['erroremail']);
            return $_SESSION['erroremail'];
        }
        try {
            $mail->IsSMTP();
            $mail->SMTPAuth = true;

            $mail->Username = SERVER_EMAIL_USERNAME;
            $mail->Password = SERVER_EMAIL_PASSWORD;
            $mail->Host = SERVER_EMAIL_HOST;
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
            $asunto = "=?UTF-8?B?" . base64_encode($asunto) . "=?=";
            $mail->Subject = $asunto;
            $footer = "<h4>Por favor no responder este correo, correo generado automaticamente.</h4>";
            $footer .= "<h2>Sistema de registro de PQR.</h2>";
            $mail->Body = $mensaje . $footer;

            if (!$mail->Send()) {
                $_SESSION['erroremail'] = '1-Error enviando el recibo al email. Detalle del error => ' . $mail->ErrorInfo . ' \n';
                error_log($_SESSION['erroremail']);
                return $_SESSION['erroremail'];
            } else {
                return true;
            }
        } catch (Exception $e) {
            $_SESSION['erroremail'] = '1-Error enviando el recibo al email. Detalle del error => ' . $mail->ErrorInfo . ' \n';
            error_log($_SESSION['erroremail']);
            return $_SESSION['erroremail'];

        }
    }
}
