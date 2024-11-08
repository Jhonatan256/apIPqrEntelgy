<?php
class PqrClass
{
    protected $db;
    public function __construct()
    {
        $this->db = new Database();
    }
    public function buscarPqr()
    {
        imprimir(getToken());
        $id = Flight::request()->data->id;
        $query = "SELECT c.id, c.asunto, c.descripcion, c.fechaCreacion, c.porcentaje, CONCAT(u.nombres, ' ', u.apellidos) AS informador, CONCAT(r.nombres, ' ', r.apellidos) AS responsable, a.nombre AS area, g.nombre AS gravedad, p.nombre AS prioridad, e.nombre AS estado, tc.nombre AS tipoCaso ";
        $query .= "FROM caso c JOIN usuario u ON u.id = c.idInformador JOIN usuario r ON r.id = c.idResponsable JOIN area a ON a.id = c.idArea JOIN gravedad g ON g.id = c.idGravedad JOIN prioridad p ON p.id = c.idPrioridad JOIN estado e ON e.id = c.idEstado JOIN tipocaso tc ON tc.id = c.tipoSolicitud";
        $query .= " WHERE c.id=$id";
        $pqr['caso'] = $this->db->consultarRegistro($query);

        if ($pqr['caso']) {
            $salida['codigo'] = "00";
            $salida['mensaje'] = "";
            $pqr['historial'] = $this->db->consultarRegistros2("SELECT *  FROM historial WHERE idCaso = $id");
            $pqr['areas'] = $this->db->consultarRegistros2("SELECT id, nombre  FROM area WHERE eliminado ='N'");
            $pqr['tipoCaso'] = $this->db->consultarRegistros2("SELECT id, nombre  FROM tipocaso WHERE eliminado ='N'");
            $pqr['usuarios'] = $this->db->consultarRegistros2("SELECT CONCAT(u.nombres, ' ', u.apellidos) AS nombre, u.email, a.nombre AS area FROM usuario u JOIN area a ON a.id = u.area WHERE u.eliminado ='N'");
            $pqr['estados'] = $this->db->consultarRegistros2("SELECT id, nombre  FROM estado WHERE eliminado ='N'");
            $pqr['gravedad'] = $this->db->consultarRegistros2("SELECT id, nombre  FROM gravedad WHERE eliminado ='N'");
            $pqr['prioridad'] = $this->db->consultarRegistros2("SELECT id, nombre  FROM prioridad WHERE eliminado ='N'");
            $salida['datos'] = $pqr;
        } else {
            $salida['codigo'] = "99";
            $salida['mensaje'] = "No se encontro información relacionada.";
            $salida['datos'] = [];
        }
        Flight::json($salida);
    }
    public function registrarPqr()
    {
        $datos['asunto'] = mb_strtoupper(Flight::request()->data->asunto, 'UTF-8');
        $datos['descripcion'] = Flight::request()->data->descripcion;
        $datos['idInformador'] = Flight::request()->data->idInformador;
        $datos['porcentaje'] = 0;
        $datos['idResponsable'] = 13;
        $datos['idPrioridad'] = 1;
        $datos['idGravedad'] = 1;
        $datos['idEstado'] = 1;
        $datos['fechaCreacion'] = date('Y-m-d H:i:s');
        $this->db->insertarRegistro("caso", $datos);
        //
        $historico['idCaso'] = $this->db->lastInsertId();
        $historico['idResponsable'] = $datos['idInformador'];
        $historico['idEncargado'] = 13;
        $historico['cambioEstado'] = $datos['idEstado'];
        $historico['descripcion'] = 'Asignación automática del sistema.';
        $historico['accionesRealizadas'] = 'Asignación';
        $historico['porcentaje'] = $datos['porcentaje'];
        $historico['fecha'] = $datos['fechaCreacion'];
        $this->crearHistorico($historico);
        Flight::json(respuesta('00', 'success'));
    }
    public function crearHistorico($datos)
    {
        $datos['fecha'] = date('Y-m-d H:i:s');
        return $this->db->insertarRegistro("historial", $datos);
    }
}
