<?php
// Point d'entrée de l'application
require_once '../vendor/autoload.php';
require_once '../config/config.php';

// Initialisation de l'application
$router = new App\Core\Router();
require_once '../config/routes.php';

$router->dispatch();