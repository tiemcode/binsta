<?php 

session_start();

require_once   '../vendor/autoload.php';
R::setup(
    'mysql:host=localhost;dbname=binsta',
    'bit_academy',
    'bit_academy'
);
if (isset($_POST["logoutPost"])) {
    session_destroy();
    header('location:../user');
    exit;
}

$path = getPath();
$controller = $path[0] ?: "post";
$method = $path[1] ?? "index";
if (!isset($_SESSION['id'])) {
    $controller = $path[0] ?: "user";
    $method = $path[1] ?? "login";
}
$method .= ucfirst(strtolower($_SERVER['REQUEST_METHOD']));
$class = ucfirst($controller) . "Controller";
if (class_exists($class)) {
    $activeClass = new $class();
    if (!method_exists($activeClass, $method)) {
        error(404, "No known method");
    }
    $activeMethod = $activeClass->$method();
} else {
    error(404, "Controller '$controller' not found");
}