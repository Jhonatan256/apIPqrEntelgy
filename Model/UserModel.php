<?php
require_once PROJECT_ROOT_PATH . "/Model/Database.php";
class UserModel extends Database
{
    public function getUsers($limit = 100)
    {
        return $this->select("SELECT * FROM usuario ORDER BY id ASC LIMIT ?", params: ["i", $limit]);
    }

    public function createUser($datos)
    {
        $datos['nombres'] = mb_strtoupper($datos['nombres'], 'UTF-8');
        $datos['apellidos'] = mb_strtoupper($datos['apellidos'], 'UTF-8');
        $datos['cargo'] = mb_strtoupper($datos['cargo'], 'UTF-8');
        $datos['genero'] = mb_strtoupper($datos['genero'], 'UTF-8');
        $datos['password'] = password_hash($datos['password'], PASSWORD_DEFAULT);
        $datos['fechaCreacion'] = date('Y-m-d H:i:s');
        $this->insertarRegistro("usuario", $datos);
        $this->lastInsertId();
    }
    public function loginUser($email, $password)
    {
        $usuario = $this->consultarRegistro("SELECT * FROM usuario WHERE email = :email ", ["email" => $email]);
        $salida['cod'] = '99';
        $salida['msj'] = '';
        $salida['data'] = [];
        if ($usuario) {
            if (password_verify($password, $usuario['password'])) {
                $salida['cod'] = '00';
                $salida['msj'] = '';
                $salida['data'] = $usuario;

            } else {
                $salida['cod'] = '99';
                $salida['msj'] = 'Constraseña incorrecta.';
            }
        } else {
            $salida['cod'] = '99';
            $salida['msj'] = 'El usuario no esta registrado.';
        }
        return $salida;
    }
}
