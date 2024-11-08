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
        return $this->lastInsertId();
    }
    public function loginUser($email, $password)
    {
        $usuario = $this->consultarRegistro("SELECT * FROM usuario WHERE email = :email ", ["email" => $email]);
        $salida['cod'] = '99';
        $salida['msj'] = '';
        $salida['data'] = [];
        if ($usuario) {
            if (password_verify($password, $usuario['password'])) {
                if ($usuario['eliminado'] == 'S') {
                    $salida['cod'] = '99';
                    $salida['msj'] = 'Usuario eliminado.';
                } else {
                    $salida['cod'] = '00';
                    $salida['msj'] = '';
                    $salida['data'] = $usuario;

                }

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
    public function createPqr($datos)
    {
        $datos['asunto'] = mb_strtoupper($datos['asunto'], 'UTF-8');
        $datos['porcentaje'] = 0;
        $datos['idResponsable'] = 13;
        $datos['idPrioridad'] = 1;
        $datos['idGravedad'] = 1;
        $datos['idEstado'] = 1;
        $datos['fechaCreacion'] = date('Y-m-d H:i:s');
        $this->insertarRegistro("caso", $datos);
        return $this->lastInsertId();
    }
    public function vistaPqr($email)
    {
        $usuario = $this->consultarRegistro("SELECT id, email, nombres  FROM usuario WHERE email = :email ", ["email" => $email]);
        if ($usuario) {
            $salida['usuario'] = $usuario;
        } else {
            $salida['usuario'] = [];
        }
        $salida['areas'] = $this->consultarRegistros2("SELECT id, nombre  FROM area WHERE eliminado ='N'");
        $salida['tipoCaso'] = $this->consultarRegistros2("SELECT id, nombre  FROM tipocaso WHERE eliminado ='N'");
        return $salida;
    }
    public function getAreas()
    {
        return $this->consultarRegistros2("SELECT id, nombre  FROM area WHERE eliminado='N'");
    }
    public function getPqr($id)
    {
        $query = "SELECT c.id, c.asunto, c.descripcion, c.fechaCreacion, c.porcentaje, CONCAT(u.nombres, ' ', u.apellidos) AS informador, CONCAT(r.nombres, ' ', r.apellidos) AS responsable, a.nombre AS area, g.nombre AS gravedad, p.nombre AS prioridad, e.nombre AS estado, tc.nombre AS tipoCaso ";
        $query .= "FROM caso c JOIN usuario u ON u.id = c.idInformador JOIN usuario r ON r.id = c.idResponsable JOIN area a ON a.id = c.idArea JOIN gravedad g ON g.id = c.idGravedad JOIN prioridad p ON p.id = c.idPrioridad JOIN estado e ON e.id = c.idEstado JOIN tipocaso tc ON tc.id = c.tipoSolicitud";
        $query .= " WHERE c.id=$id";
        $pqr['caso'] = $this->consultarRegistro($query);
        if ($pqr['caso']) {
            $salida['cod'] = "00";
            $salida['msj'] = "";
            $pqr['historial'] = $this->consultarRegistros2("SELECT *  FROM historial WHERE idCaso = $id");
            $pqr['areas'] = $this->consultarRegistros2("SELECT id, nombre  FROM area WHERE eliminado ='N'");
            $pqr['tipoCaso'] = $this->consultarRegistros2("SELECT id, nombre  FROM tipocaso WHERE eliminado ='N'");
            $pqr['usuarios'] = $this->consultarRegistros2("SELECT CONCAT(u.nombres, ' ', u.apellidos) AS nombre, u.email, a.nombre AS area FROM usuario u JOIN area a ON a.id = u.area WHERE u.eliminado ='N'");
            $pqr['estados'] = $this->consultarRegistros2("SELECT id, nombre  FROM estado WHERE eliminado ='N'");
            $pqr['gravedad'] = $this->consultarRegistros2("SELECT id, nombre  FROM gravedad WHERE eliminado ='N'");
            $pqr['prioridad'] = $this->consultarRegistros2("SELECT id, nombre  FROM prioridad WHERE eliminado ='N'");
            $salida['datos'] = $pqr;
        } else {
            $salida['cod'] = "99";
            $salida['msj'] = "No se encontro información relacionada.";
            $salida['datos'] = [];
        }
        return $salida;
    }

    public function listPqr($id)
    {
        $query = "SELECT c.id, c.asunto, c.descripcion, c.fechaCreacion, c.porcentaje, CONCAT(u.nombres, ' ', u.apellidos) AS informador, CONCAT(r.nombres, ' ', r.apellidos) AS responsable, a.nombre AS area, g.nombre AS gravedad, p.nombre AS prioridad, e.nombre AS estado, tc.nombre AS tipoCaso ";
        $query .= "FROM caso c JOIN usuario u ON u.id = c.idInformador JOIN usuario r ON r.id = c.idResponsable JOIN area a ON a.id = c.idArea JOIN gravedad g ON g.id = c.idGravedad JOIN prioridad p ON p.id = c.idPrioridad JOIN estado e ON e.id = c.idEstado JOIN tipocaso tc ON tc.id = c.tipoSolicitud";
        $query .= " WHERE c.idInformador= :id";
        return $this->consultarRegistros($query, ['id' => $id]);
    }
}
