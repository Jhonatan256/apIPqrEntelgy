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
}
