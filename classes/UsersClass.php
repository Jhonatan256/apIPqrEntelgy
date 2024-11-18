<?php
use Firebase\JWT\JWT;

class UsersClass
{
    protected $db;
    public function __construct()
    {
        $this->db = new Database();
    }
    public function loginUser()
    {
        $usuario = $this->db->consultarRegistro("SELECT id, nombres, apellidos, identificacion, email, celular, tipoUsuario, cargo, area, genero, fechaUltimoAcceso, eliminado, password FROM usuario WHERE email = :email ", ["email" => Flight::request()->data->email]);
        if ($usuario) {
            $password = Flight::request()->data->password;
            if (password_verify($password, $usuario['password'])) {
                if ($usuario['eliminado'] == 'S') {
                    return Flight::json(respuesta('99', 'Usuario eliminado'));
                } else {
                    unset($usuario['password']);
                }
                if ($usuario['tipoUsuario'] != '1' && $usuario['tipoUsuario'] != '3') {
                    return Flight::json(respuesta('99', 'El tipo de usuario no puede acceder al sistema.'));
                }
                $usuario['fechaUltimoAcceso'] = date('Y-m-d H:i:s');
                $this->db->actualizarRegistro('usuario', ['fechaUltimoAcceso' => $usuario['fechaUltimoAcceso']], ['id' => $usuario['id']]);
                $key = $_ENV['KEY_TOKEN'];
                $now = strtotime("now");
                $payload = [
                    'exp' => $now + 3600,
                    'data' => ['id' => $usuario['id'], 'tipoUsuario' => $usuario['tipoUsuario']],
                ];
                $usuario['token'] = JWT::encode($payload, $key, 'HS256');
                return Flight::json(respuesta('00', 'Success', $usuario));

            } else {
                return Flight::json(respuesta('99', 'Constraseña incorrecta.'));
            }
        } else {
            return Flight::json(respuesta('88', 'El usuario no esta registrado.'));
        }
        Flight::json($salida);
    }
    public function createUser()
    {
        $email = Flight::request()->data->email;
        $usuario = $this->db->consultarRegistro('SELECT * FROM usuario WHERE email= :email', ['email' => $email]);
        if ($usuario) {
            return Flight::json(respuesta('99', 'El usuario ya esta registrado.'));
        }

        if (strpos($email, '@entelgy.com') !== false) {
            $datos['nombres'] = mb_strtoupper(Flight::request()->data->nombres, 'UTF-8');
            $datos['apellidos'] = mb_strtoupper(Flight::request()->data->apellidos, 'UTF-8');
            $datos['identificacion'] = Flight::request()->data->identificacion;
            $datos['email'] = Flight::request()->data->email;
            $datos['celular'] = Flight::request()->data->celular;
            $datos['tipoUsuario'] = Flight::request()->data->tipoUsuario;
            $datos['cargo'] = mb_strtoupper(Flight::request()->data->cargo, 'UTF-8');
            $datos['area'] = Flight::request()->data->area;
            $datos['genero'] = mb_strtoupper(Flight::request()->data->genero, 'UTF-8');
            $datos['password'] = password_hash(Flight::request()->data->password, PASSWORD_DEFAULT);
            $datos['fechaCreacion'] = date('Y-m-d H:i:s');
            $this->db->insertarRegistro("usuario", $datos);
            return Flight::json(respuesta('00', 'success', $this->db->lastInsertId()));
        } else {
            return Flight::json(respuesta('99', 'Por favor, digite el correo corporativo.'));
        }
    }
    public function olvidoClave()
    {
        $email = Flight::request()->data->email;
        $datosUsuario = $this->db->consultarRegistro("SELECT id, CONCAT(nombres, ' ', apellidos) as nombre, email, genero FROM usuario WHERE email= :email", ['email' => $email]);
        if ($datosUsuario) {
            $clave = generarAleatorioClave();
            $this->db->actualizarRegistro('usuario', ['password' => password_hash($clave, PASSWORD_DEFAULT)], ['id' => $datosUsuario['id']]);
            $asunto = rand(5, 15) . ' Olvido de clave - ' . NOMBRE_SISTEMA;
            $mensaje = "<h3>" . generoCorreo($datosUsuario['genero']) . $datosUsuario['nombre'] . "</h3>";
            $mensaje .= "<p>Se ha generado la siguiente clave dinámica <b>$clave</b></p>";
            $url = URL_SISTEMA;
            $mensaje .= "<p>Ingresa a <a href='$url' target='_blank'>" . URL_SISTEMA . "</a> e ingrese la contraseña asignada.</p>";
            if (\Utilitarias::enviarEmail($datosUsuario['email'], $asunto, $mensaje)) {
                return Flight::json(respuesta('00', 'A su correo electrónico se le envió una nueva clave dinámica.'));
            } else {
                return Flight::json(respuesta('99', 'Correo no enviado.'));
            }
        } else {
            return Flight::json(respuesta('99', 'El usuario no esta registrado.'));

        }
    }
    public function listarUsuarios()
    {
        validateToken();
        $info = getToken();
        if ($info->data->tipoUsuario != 3) {
            return Flight::json(respuesta('99', 'El usuario no tiene los permisos para consultar enta acción.'));
        }
        $usuarios = $this->db->consultarRegistros("SELECT id, nombres, apellidos, identificacion, email, celular, tipoUsuario, cargo, area, genero, fechaCreacion, fechaUltimoAcceso, eliminado FROM usuario WHERE eliminado = 'N' ORDER BY id DESC");
        return Flight::json(respuesta('00', '', $usuarios));
    }
}
