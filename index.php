<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
//
require_once 'vendor/autoload.php';
require_once 'includes/config.php';
//
require_once 'classes/PqrClass.php';
require_once 'classes/UsersClass.php';
require_once 'model/Database.php';
//
//Rutas
Flight::route('GET|POST /', function () {
    Flight::json(respuesta('99', 'Acceso no permitido'));
});
//
Flight::route('POST /login', ['UsersClass', 'loginUser']);
//
Flight::route('POST /searchPqr', ['PqrClass', 'buscarPqr']);
//
Flight::route('POST /createPqr', ['PqrClass', 'registrarPqr']);
//
Flight::start();
function getToken()
{
    $headers = apache_request_headers();
    if (!isset($headers['Authorization'])) {
        Flight::halt(403, respuesta('99', 'Unauthenticated'));
    }
    try {
        return JWT::decode(str_replace('Bearer ', '', $headers['Authorization']), new Key(KEY_TOKEN, 'HS256'));
    } catch (\Throwable $th) {
        Flight::halt(403, respuesta('99', $th));
    }
}
function validateToken()
{
    $info = getToken();
    $db = new Database();
    $datos = $db->consultarRegistro('SELECT id FROM usuario WHERE id = :id', ['id' => $info->data]);
    if (!$datos) {
        Flight::halt(403, respuesta('99', 'Token inválido.'));
    }
}
function respuesta($cod, $msj, $datos = [])
{
    return ['codigo' => $cod, 'mensaje' => $msj, 'datos' => $datos];
}
function imprimir($datos)
{
    echo "<pre>";
    print_r($datos);
    echo "</pre>";
    die();
}
