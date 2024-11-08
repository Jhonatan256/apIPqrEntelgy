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
                    $salida = respuesta('99', 'Usuario eliminado.');
                } else {
                    unset($usuario['password']);
                    $usuario['fechaUltimoAcceso'] = date('Y-m-d H:i:s');
                    $this->db->actualizarRegistro('usuario', ['fechaUltimoAcceso' => $usuario['fechaUltimoAcceso']], ['id' => $usuario['id']]);
                    $key = KEY_TOKEN;
                    $now = strtotime("now");
                    $payload = [
                        'exp' => $now + 3600,
                        'data' => $usuario['id'],
                    ];
                    $usuario['token'] = JWT::encode($payload, $key, 'HS256');
                    $salida = respuesta('00', 'Success', $usuario);
                }

            } else {
                $salida = respuesta('99', 'Constraseña incorrecta.');
            }
        } else {
            $salida = respuesta('88', 'El usuario no esta registrado.');
        }
        Flight::json($salida);
    }
    public function createUser()
    {
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
        Flight::json(respuesta('00', 'success', $this->db->lastInsertId()));
    }

}
