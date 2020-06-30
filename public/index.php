<?php
session_start();
ob_start();

spl_autoload_register(function ($class) {
    $root = dirname(__DIR__);
    $path = explode('\\', $class);
    $file = $root . '/'  . str_replace('\\', '/', $class);
    $subDir =  $file . '/' . end($path) . '.php';
    $file .=  '.php';
    if (is_readable($file)) {
        require $file;
    } elseif (is_readable($subDir)) {
        require $subDir;
    }
});

$router = new Core\Router();

$url = $_SERVER['REQUEST_URI'];
$router->dispatch($url);

ob_end_flush();