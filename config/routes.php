
<?php
// config/routes.php

// Ensure $router is available from the scope where this is included
$router->get('', [App\Controllers\AdminController::class, 'login']);
$router->get('annonces', [App\Controllers\StudentController::class, 'annonces']);

// Admin routes
$router->get('admin/login', [App\Controllers\AdminController::class, 'login']);
$router->post('admin/login', [App\Controllers\AdminController::class, 'login']);
$router->get('admin/dashboard', [App\Controllers\AdminController::class, 'dashboard']);
$router->get('admin/annonces', [App\Controllers\AdminController::class, 'annonces']);
$router->get('admin/archived-annonces', [App\Controllers\AdminController::class, 'archivedAnnonces']);
$router->post('admin/archive-annonce/(\d+)', [App\Controllers\AdminController::class, 'archiveAnnonce']);
$router->post('admin/restore-annonce/(\d+)', [App\Controllers\AdminController::class, 'restoreAnnonce']);
$router->get('admin/edit-annonce/(\d+)', [App\Controllers\AdminController::class, 'editAnnonce']);
$router->post('admin/edit-annonce/(\d+)', [App\Controllers\AdminController::class, 'editAnnonce']);
$router->get('admin/create-entreprise', [App\Controllers\AdminController::class, 'createEntreprise']);
$router->post('admin/create-entreprise', [App\Controllers\AdminController::class, 'createEntreprise']);
$router->get('admin/create-annonce', [App\Controllers\AdminController::class, 'createAnnonce']);
$router->post('admin/create-annonce', [App\Controllers\AdminController::class, 'createAnnonce']);
$router->get('admin/students', [App\Controllers\AdminController::class, 'students']);
$router->get('admin/logout', [App\Controllers\AdminController::class, 'logout']);

// Student routes
$router->get('student/login', [App\Controllers\StudentController::class, 'login']);
$router->post('student/login', [App\Controllers\StudentController::class, 'login']);
$router->get('student/register', [App\Controllers\StudentController::class, 'register']);
$router->post('student/register', [App\Controllers\StudentController::class, 'register']);
$router->get('student/annonces', [App\Controllers\StudentController::class, 'annonces']);
$router->get('student/search', [App\Controllers\StudentController::class, 'search']);
$router->get('student/logout', [App\Controllers\StudentController::class, 'logout']);
?>