<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Security;
use App\Models\Student;
use App\Models\Annonce;
use App\Models\Entreprise;

class StudentController extends Controller
{
    private $studentModel;
    private $annonceModel;
    private $entrepriseModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->studentModel = new Student();
        $this->annonceModel = new Annonce();
        $this->entrepriseModel = new Entreprise();
    }
    
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $csrfToken = $_POST['_csrf'] ?? '';
            if (!$this->security->verifyCsrfToken($csrfToken)) {
                die('CSRF token validation failed');
            }
            
            $email = $this->security->sanitizeEmail($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            
            $student = $this->studentModel->login($email, $password);
            
            if ($student) {
                $this->session->set('student_user', $student);
                header('Location: /student/annonces');
                exit;
            } else {
                $this->view('student/login', [
                    'error' => 'Invalid credentials',
                    'csrfToken' => $this->security->generateCsrfToken()
                ]);
            }
        } else {
            $this->view('student/login', [
                'csrfToken' => $this->security->generateCsrfToken()
            ]);
        }
    }
    
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $csrfToken = $_POST['_csrf'] ?? '';
            if (!$this->security->verifyCsrfToken($csrfToken)) {
                die('CSRF token validation failed');
            }
            
            $data = [
                'name' => $this->security->sanitize($_POST['name'] ?? ''),
                'email' => $this->security->sanitizeEmail($_POST['email'] ?? ''),
                'password' => $_POST['password'] ?? '',
                'promotion' => $this->security->sanitize($_POST['promotion'] ?? ''),
                'specialisation' => $this->security->sanitize($_POST['specialisation'] ?? '')
            ];
            
            if ($this->studentModel->create($data)) {
                header('Location: /student/login?success=registered');
                exit;
            } else {
                $this->view('student/register', [
                    'error' => 'Registration failed',
                    'csrfToken' => $this->security->generateCsrfToken()
                ]);
            }
        } else {
            $this->view('student/register', [
                'csrfToken' => $this->security->generateCsrfToken()
            ]);
        }
    }
    
    public function annonces()
    {
        if (!$this->session->get('student_user')) {
            header('Location: /student/login');
            exit;
        }
        
        $annonces = $this->annonceModel->getActive();
        $entreprises = $this->entrepriseModel->getAll();
        
        $this->view('student/annonces', [
            'annonces' => $annonces,
            'entreprises' => $entreprises,
            'csrfToken' => $this->security->generateCsrfToken()
        ]);
    }
    
    public function search()
    {
        if (!$this->session->get('student_user')) {
            $this->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $query = $_GET['q'] ?? '';
            $entrepriseFilter = $_GET['entreprise'] ?? '';
            $contratFilter = $_GET['contrat'] ?? '';

            $annonces = $this->annonceModel->search($query, $entrepriseFilter, $contratFilter);

            $this->json($annonces);
        } catch (\Throwable $e) {
            error_log('Student search failed: ' . $e->getMessage());
            $this->json(['error' => 'Search failed', 'message' => $e->getMessage()], 500);
        }
    }
    
    public function logout()
    {
        $this->session->destroy();
        header('Location: /student/login');
        exit;
    }
}
