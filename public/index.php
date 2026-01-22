<?php
// Point d'entrée de l'application
require_once '../vendor/autoload.php';
require_once '../config/config.php';

// Initialisation de l'application
$router = App\core\Router::getRouter();
require_once '../config/routes.php';

$router->dispatch();