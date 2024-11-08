<?php
require_once 'vendor/autoload.php';
require_once __DIR__ . "/includes/boots.php";
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$key = KEY_TOKEN;
$now = strtotime("now");
$payload = [
    'exp' => $now + 3600,
    'data' => 1,
];
$jwt = JWT::encode($payload, $key, 'HS256');
$decoded = JWT::decode($jwt, new Key($key, 'HS256'));
print_r($jwt);
die();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);
// print_r($uri);
if ((isset($uri[3]) && $uri[3] != 'user') || !isset($uri[4])) {
    header("HTTP/1.1 404 Not Found");
    echo "HTTP/1.1 404 Not Found";
    exit();
}
require PROJECT_ROOT_PATH . "/Controller/Api/UserController.php";
$objFeedController = new UserController();
$strMethodName = $uri[4] . 'Action';
$objFeedController->{$strMethodName}();
