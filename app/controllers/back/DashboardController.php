<?php
namespace App\Controllers\Back;

use App\Core\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        // Dashboard administrateur
    }
}

class AnnouncementController extends Controller
{
    public function index()
    {
        // Gestion des annonces
    }
    
    public function archived()
    {
        // Annonces archivées
    }
}

class CompanyController extends Controller
{
    public function index()
    {
        // Gestion des entreprises
    }
}

class StudentController extends Controller
{
    public function index()
    {
        // Gestion des étudiants
    }
}