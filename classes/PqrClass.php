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
        // validateToken();
        $id = Flight::request()->data->id;

        $query = "SELECT c.id, c.asunto, c.descripcion, c.fechaCreacion, c.porcentaje, CONCAT(u.nombres, ' ', u.apellidos) AS informador, CONCAT(r.nombres, ' ', r.apellidos) AS responsable, a.nombre AS area, g.nombre AS gravedad, p.nombre AS prioridad, e.nombre AS estado, tc.nombre AS tipoCaso ";
        $query .= "FROM caso c LEFT JOIN usuario u ON u.id = c.idInformador LEFT JOIN usuario r ON r.id = c.idResponsable LEFT JOIN area a ON a.id = c.idArea LEFT JOIN gravedad g ON g.id = c.idGravedad LEFT JOIN prioridad p ON p.id = c.idPrioridad LEFT JOIN estado e ON e.id = c.idEstado LEFT JOIN tipocaso tc ON tc.id = c.tipoSolicitud";
        $query .= " WHERE c.id=$id";
        $pqr['caso'] = $this->db->consultarRegistro($query);
        if ($pqr['caso']) {
            $pqr['caso']['porcentaje'] = calcularPorcentaje($pqr['caso'], false);
            $salida['codigo'] = "00";
            $salida['mensaje'] = "";
            $pqr['historial'] = $this->db->consultarRegistros2("SELECT *  FROM historial WHERE idCaso = $id");
            $pqr['areas'] = $this->db->consultarRegistros2("SELECT id, nombre  FROM area WHERE eliminado ='N'");
            $pqr['tipoCaso'] = $this->db->consultarRegistros2("SELECT id, nombre  FROM tipocaso WHERE eliminado ='N'");
            $pqr['usuarios'] = $this->db->consultarRegistros2("SELECT u.id, CONCAT(u.nombres, ' ', u.apellidos) AS nombre, u.email, a.nombre AS area FROM usuario u JOIN area a ON a.id = u.area WHERE u.eliminado ='N'");
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
        // validateToken();
        $datos['asunto'] = mb_strtoupper(Flight::request()->data->asunto, 'UTF-8');
        $datos['descripcion'] = Flight::request()->data->descripcion;
        if (empty(Flight::request()->data->idInformador)) {
            $datosUsuario = $this->crearUsuarioExterno($this->db, Flight::request()->data->nombres, Flight::request()->data->apellidos, Flight::request()->data->email, Flight::request()->data->cargo, Flight::request()->data->area, Flight::request()->data->genero, Flight::request()->data->tipoUsuario);
        } else {
            $datosUsuario = \Utilitarias::datosUsuario($this->db, Flight::request()->data->idInformador);
        }
        $datos['idInformador'] = $datosUsuario['id'];
        $datos['idArea'] = Flight::request()->data->idArea;
        $datos['tipoSolicitud'] = Flight::request()->data->tipoSolicitud;
        $datos['porcentaje'] = 0;
        $datos['idResponsable'] = 1;
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
        $msj = 'Se creó la solicitud con éxito.';

        $asunto = $historico['idCaso'] . ' - Notificación de Registro de Requerimiento';
        $mensaje = "<h3>" . generoCorreo($datosUsuario['genero']) . $datosUsuario['nombre'] . "</h3>";
        $mensaje .= "<p>Se registro el siguiente requerimiento <b>#" . $historico['idCaso'] . "</b></p>";
        $url = URL_SISTEMA . "pqr?idCaso=" . $historico['idCaso'];
        $mensaje .= "<p>Puedes ver el estado del requerimiento en <a href='$url' target='_blank'>" . URL_SISTEMA . "</p>";
        if (!\Utilitarias::enviarEmail($datosUsuario['email'], $asunto, $mensaje)) {
            $msj = 'Correo no enviado.';
        }
        Flight::json(respuesta('00', $msj, $historico['idCaso']));
    }
    public function crearHistorico($datos)
    {
        $datos['fecha'] = date('Y-m-d H:i:s');
        return $this->db->insertarRegistro("historial", $datos);
    }
    public function formularioPqr()
    {
        // validateToken();
        $usuario = $this->db->consultarRegistro("SELECT id, email, nombres  FROM usuario WHERE email = :email ", ["email" => Flight::request()->data->email]);
        if ($usuario) {
            $datos['usuario'] = $usuario;
        } else {
            $datos['usuario'] = [];
        }
        $datos['areas'] = $this->db->consultarRegistros2("SELECT id, nombre  FROM area WHERE eliminado ='N'");
        $datos['tipoCaso'] = $this->db->consultarRegistros2("SELECT id, nombre  FROM tipocaso WHERE eliminado ='N'");
        Flight::json(respuesta('00', '', $datos));
    }
    public function areas()
    {
        // validateToken();
        $areas = $this->db->consultarRegistros2("SELECT id, nombre  FROM area WHERE eliminado='N'");
        Flight::json(respuesta('00', 'success', $areas));
    }
    public function listarPqr()
    {
        // validateToken();
        $query = "SELECT c.id, c.asunto, c.descripcion, c.fechaCreacion, c.porcentaje, CONCAT(u.nombres, ' ', u.apellidos) AS informador, CONCAT(r.nombres, ' ', r.apellidos) AS responsable, a.nombre AS area, g.nombre AS gravedad, p.nombre AS prioridad, e.nombre AS estado, tc.nombre AS tipoCaso ";
        $query .= "FROM caso c JOIN usuario u ON u.id = c.idInformador JOIN usuario r ON r.id = c.idResponsable JOIN area a ON a.id = c.idArea JOIN gravedad g ON g.id = c.idGravedad JOIN prioridad p ON p.id = c.idPrioridad JOIN estado e ON e.id = c.idEstado JOIN tipocaso tc ON tc.id = c.tipoSolicitud";
        $query .= " WHERE c.idInformador= :id";
        $casos = $this->db->consultarRegistros2($query, ['id' => Flight::request()->data->id]);
        if ($casos) {
            Flight::json(respuesta('00', '', $casos));
        } else {

            Flight::json(respuesta('99', 'Sin registros.'));
        }
    }
    public function crearUsuarioExterno($db, $nombres, $apellidos, $email, $cargo, $area, $genero, $tipoUsuario)
    {
        $datos['nombres'] = mb_strtoupper($nombres, 'UTF-8');
        $datos['apellidos'] = mb_strtoupper($apellidos, 'UTF-8');
        $datos['identificacion'] = '';
        $datos['email'] = $email;
        $datos['celular'] = '';
        $datos['tipoUsuario'] = $tipoUsuario;
        $datos['cargo'] = mb_strtoupper($cargo, 'UTF-8');
        $datos['area'] = $area ?? 0;
        $datos['genero'] = $genero;
        $datos['password'] = password_hash(rand(5, 15), PASSWORD_DEFAULT);
        $datos['fechaCreacion'] = date('Y-m-d H:i:s');
        $db->insertarRegistro("usuario", $datos);
        $datos['nombre'] = $datos['nombres'] . " " . $datos['apellidos'];
        $datos['id'] = $db->lastInsertId();
        return $datos;
    }
    public function cambiarEstado()
    {
        validateToken();
        $pqr = $this->buscarCaso(Flight::request()->data->idCaso);
        if ($pqr) {
            $datos['idCaso'] = $pqr['id'];
            $datos['idResponsable'] = Flight::request()->data->idResponsable;
            $datos['idEncargado'] = Flight::request()->data->idEncargado;
            $datos['cambioEstado'] = Flight::request()->data->estado;
            $datos['descripcion'] = Flight::request()->data->descripcion;
            $datos['accionesRealizadas'] = '';
            $email[] = $this->buscarEmail($pqr['idResponsable']);
            if ($datos['cambioEstado'] != $pqr['idEstado']) {
                $datos['accionesRealizadas'] .= 'Cambio de estado: ' . $this->buscarEstado($pqr['idEstado']) . ' a ' . $this->buscarEstado($datos['cambioEstado']);
            }
            if ($datos['idEncargado'] != $pqr['idResponsable']) {
                $datos['accionesRealizadas'] .= '<br>Cambio de usuario: ' . $this->buscarNombre($pqr['idResponsable']) . ' a ' . $this->buscarNombre($datos['idEncargado']);
                $email[] = $this->buscarEmail($datos['idEncargado']);
            }
            $datos['porcentaje'] = calcularPorcentaje($pqr, true);
            $datos['fecha'] = date('Y-m-d H:i:s');
            $this->db->insertarRegistro("historial", $datos);
            //
            $asunto = $pqr['id'] . ' - Notificación actualización de Requerimiento';
            $mensaje = "<h3>Se ha realizado el siguiente cambio para el caso #" . $pqr['id'] . "</h3>";
            $mensaje .= "<p>A continuación se presentará el resumen:</p>";
            $mensaje .= $datos['descripcion'];
            $url = URL_SISTEMA . "pqr?idCaso=" . $pqr['id'];
            $mensaje .= "<p>Puedes ver el estado del requerimiento en <a href='$url' target='_blank'>" . URL_SISTEMA . "</p>";
            \Utilitarias::enviarEmail($email, $asunto, $mensaje);
            Flight::json(respuesta('00', '', $pqr['id']));
        } else {
            Flight::json(respuesta('99', 'El caso no existe.'));
        }
    }
    public function buscarCaso($id)
    {
        $query = "SELECT c.*, CONCAT(u.nombres, ' ', u.apellidos) AS informador, CONCAT(r.nombres, ' ', r.apellidos) AS responsable, a.nombre AS area, g.nombre AS gravedad, p.nombre AS prioridad, e.nombre AS estado, tc.nombre AS tipoCaso ";
        $query .= "FROM caso c JOIN usuario u ON u.id = c.idInformador JOIN usuario r ON r.id = c.idResponsable JOIN area a ON a.id = c.idArea JOIN gravedad g ON g.id = c.idGravedad JOIN prioridad p ON p.id = c.idPrioridad JOIN estado e ON e.id = c.idEstado JOIN tipocaso tc ON tc.id = c.tipoSolicitud";
        $query .= " WHERE c.id= :id";
        return $this->db->consultarRegistro($query, ['id' => $id]);
    }
    public function buscarEstado($id)
    {
        $query = "SELECT nombre FROM estado WHERE id = :id";
        return $this->db->consultarRegistro($query, ['id' => $id], 'nombre');
    }
    public function buscarNombre($id)
    {
        $query = "SELECT nombres FROM usuario WHERE id = :id";
        return $this->db->consultarRegistro($query, ['id' => $id], 'nombres');
    }
    public function buscarEmail($id)
    {
        $query = "SELECT email FROM usuario WHERE id = :id";
        return $this->db->consultarRegistro($query, ['id' => $id], 'email');
    }
}
