<?php
require_once 'vendor/autoload.php';
require_once 'includes/config.php';
//
require_once 'classes/PqrClass.php';
require_once 'classes/UsersClass.php';
require_once 'model/Database.php';
//

//Rutas
Flight::route('GET|POST /', function () {
    Flight::json(["codigo" => "99", "mensaje" => "Acceso no permitido"]);
});
//
Flight::route('POST /login', ['UsersClass', 'loginUser']);
//
Flight::route('POST /searchPqr', ['PqrClass', 'buscarPqr']);
//
Flight::route('POST /createPqr', ['PqrClass', 'registrarPqr']);
//
Flight::start();
function respuesta($cod, $msj, $datos = [])
{
    return ['codigo' => $cod, 'mensaje' => $msj, 'datos' => $datos];
}
