<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
//
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    // Indica los métodos permitidos.
    header('Access-Control-Allow-Methods: GET, POST, DELETE');
    // Indica los encabezados permitidos.
    header('Access-Control-Allow-Headers: Authorization');
    http_response_code(204);
    exit();
}
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
//
//
require_once 'vendor/autoload.php';
require_once 'includes/config.php';
//
require_once 'classes/PqrClass.php';
require_once 'classes/UsersClass.php';
require_once 'model/Database.php';
//
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
//Rutas
Flight::route('GET|POST /', function () {
    Flight::json(respuesta('99', 'Acceso no permitido'));
});
//
Flight::route('POST /login', ['UsersClass', 'loginUser']);
Flight::route('POST /createUser', ['UsersClass', 'createUser']);
//
Flight::route('POST /searchPqr', ['PqrClass', 'buscarPqr']);
Flight::route('POST /createPqr', ['PqrClass', 'registrarPqr']);
Flight::route('POST /getViewPqr', ['PqrClass', 'formularioPqr']);
Flight::route('POST /getAreas', ['PqrClass', 'areas']);
Flight::route('POST /listPqr', ['PqrClass', 'listarPqr']);
//
Flight::start();

function getToken()
{
    $headers = apache_request_headers();
    if (!isset($headers['Authorization'])) {
        Flight::jsonHalt(respuesta('99', msj: 'Sin token de acceso.'), 401);
    }
    try {
        return JWT::decode(str_replace('Bearer ', '', $headers['Authorization']), new Key($_ENV['KEY_TOKEN'], 'HS256'));
    } catch (\Throwable $th) {
        Flight::jsonHalt(respuesta('99', 'Token fail: ' . $th), 401);
    }
}
function validateToken()
{
    $info = getToken();
    $db = new Database();
    $datos = $db->consultarRegistro('SELECT id FROM usuario WHERE id = :id', ['id' => $info->data]);
    if (!$datos) {
        Flight::jsonHalt(respuesta('99', 'Token inválido.'), 403);
    }
}
function respuesta($cod, $msj, $datos = [])
{
    if ($datos) {
        return ['codigo' => $cod, 'mensaje' => $msj, 'datos' => $datos];
    } else {
        return ['codigo' => $cod, 'mensaje' => $msj];
    }
}
function imprimir($datos)
{
    echo "<pre>";
    print_r($datos);
    echo "</pre>";
    die();
}
