<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Security;
use App\Models\User;
use App\Models\Student;
use App\Models\Entreprise;
use App\Models\Annonce;

class AdminController extends Controller
{
    private $userModel;
    private $studentModel;
    private $entrepriseModel;
    private $annonceModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
        $this->studentModel = new Student();
        $this->entrepriseModel = new Entreprise();
        $this->annonceModel = new Annonce();
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
            
            $user = $this->userModel->login($email, $password);
            
            if ($user) {
                $this->session->set('admin_user', $user);
                header('Location: /admin/dashboard');
                exit;
            } else {
                $this->view('admin/login', [
                    'error' => 'Invalid credentials',
                    'csrfToken' => $this->security->generateCsrfToken()
                ]);
            }
        } else {
            $this->view('admin/login', [
                'csrfToken' => $this->security->generateCsrfToken()
            ]);
        }
    }
    
    public function dashboard()
    {
        if (!$this->session->get('admin_user')) {
            header('Location: /admin/login');
            exit;
        }
        
        $entreprises = $this->entrepriseModel->getAll();
        $annonces = $this->annonceModel->getAll();
        $students = $this->studentModel->getAll();
        $recentAnnonces = $this->annonceModel->getRecent(3);
        $annonceStats = $this->annonceModel->getStatistics();
        
        $this->view('admin/dashboard', [
            'entreprises' => $entreprises,
            'annonces' => $annonces,
            'students' => $students,
            'recentAnnonces' => $recentAnnonces,
            'annonceStats' => $annonceStats,
            'csrfToken' => $this->security->generateCsrfToken()
        ]);
    }
    
    public function annonces()
    {
        if (!$this->session->get('admin_user')) {
            header('Location: /admin/login');
            exit;
        }
        
        $annonces = $this->annonceModel->getAll();
        
        $this->view('admin/annonces', [
            'annonces' => $annonces,
            'csrfToken' => $this->security->generateCsrfToken()
        ]);
    }
    
    public function archivedAnnonces()
    {
        if (!$this->session->get('admin_user')) {
            header('Location: /admin/login');
            exit;
        }
        
        $annonces = $this->annonceModel->getArchived();
        
        $this->view('admin/archived-annonces', [
            'annonces' => $annonces,
            'csrfToken' => $this->security->generateCsrfToken()
        ]);
    }
    
    public function createEntreprise()
    {
        if (!$this->session->get('admin_user')) {
            header('Location: /admin/login');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $csrfToken = $_POST['_csrf'] ?? '';
            if (!$this->security->verifyCsrfToken($csrfToken)) {
                die('CSRF token validation failed');
            }
            
            $data = [
                'NAME' => $this->security->sanitize($_POST['name'] ?? ''),
                'secteur' => $this->security->sanitize($_POST['secteur'] ?? ''),
                'localisation' => $this->security->sanitize($_POST['localisation'] ?? ''),
                'email' => $this->security->sanitizeEmail($_POST['email'] ?? ''),
                'telephone' => $this->security->sanitize($_POST['telephone'] ?? '')
            ];
            
            if ($this->entrepriseModel->create($data)) {
                header('Location: /admin/dashboard?success=entreprise_created');
                exit;
            } else {
                $this->view('admin/create-entreprise', [
                    'error' => 'Failed to create entreprise',
                    'csrfToken' => $this->security->generateCsrfToken()
                ]);
            }
        } else {
            $this->view('admin/create-entreprise', [
                'csrfToken' => $this->security->generateCsrfToken()
            ]);
        }
    }
    
    public function createAnnonce()
    {
        if (!$this->session->get('admin_user')) {
            header('Location: /admin/login');
            exit;
        }
        
        $entreprises = $this->entrepriseModel->getAll();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $csrfToken = $_POST['_csrf'] ?? '';
            if (!$this->security->verifyCsrfToken($csrfToken)) {
                die('CSRF token validation failed');
            }
            
            $data = [
                'titre' => $this->security->sanitize($_POST['titre'] ?? ''),
                'entreprise' => $this->security->sanitize($_POST['entreprise'] ?? ''),
                'type_contrat' => $this->security->sanitize($_POST['type_contrat'] ?? ''),
                'localisation' => $this->security->sanitize($_POST['localisation'] ?? ''),
                'description' => $this->security->sanitize($_POST['description'] ?? ''),
                'competences' => $this->security->sanitize($_POST['competences'] ?? ''),
                'entreprise_id' => $_POST['entreprise_id'] ?? ''
            ];
            
            if ($this->annonceModel->create($data)) {
                header('Location: /admin/dashboard?success=annonce_created');
                exit;
            } else {
                $this->view('admin/create-annonce', [
                    'error' => 'Failed to create annonce',
                    'entreprises' => $entreprises,
                    'csrfToken' => $this->security->generateCsrfToken()
                ]);
            }
        } else {
            $this->view('admin/create-annonce', [
                'entreprises' => $entreprises,
                'csrfToken' => $this->security->generateCsrfToken()
            ]);
        }
    }
    
    public function editAnnonce($id)
    {
        if (!$this->session->get('admin_user')) {
            header('Location: /admin/login');
            exit;
        }
        
        $annonce = $this->annonceModel->findById($id);
        $entreprises = $this->entrepriseModel->getAll();
        
        if (!$annonce) {
            header('Location: /admin/annonces');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $csrfToken = $_POST['_csrf'] ?? '';
            if (!$this->security->verifyCsrfToken($csrfToken)) {
                die('CSRF token validation failed');
            }
            
            $data = [
                'titre' => $this->security->sanitize($_POST['titre'] ?? ''),
                'entreprise' => $this->security->sanitize($_POST['entreprise'] ?? ''),
                'type_contrat' => $this->security->sanitize($_POST['type_contrat'] ?? ''),
                'localisation' => $this->security->sanitize($_POST['localisation'] ?? ''),
                'description' => $this->security->sanitize($_POST['description'] ?? ''),
                'competences' => $this->security->sanitize($_POST['competences'] ?? '')
            ];
            
            if ($this->annonceModel->update($id, $data)) {
                header('Location: /admin/annonces?success=annonce_updated');
                exit;
            } else {
                $this->view('admin/edit-annonce', [
                    'error' => 'Failed to update annonce',
                    'annonce' => $annonce,
                    'entreprises' => $entreprises,
                    'csrfToken' => $this->security->generateCsrfToken()
                ]);
            }
        } else {
            $this->view('admin/edit-annonce', [
                'annonce' => $annonce,
                'entreprises' => $entreprises,
                'csrfToken' => $this->security->generateCsrfToken()
            ]);
        }
    }
    
    public function archiveAnnonce($id)
    {
        if (!$this->session->get('admin_user')) {
            header('Location: /admin/login');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $csrfToken = $_POST['_csrf'] ?? '';
            if (!$this->security->verifyCsrfToken($csrfToken)) {
                die('CSRF token validation failed');
            }
            
            if ($this->annonceModel->archive($id)) {
                header('Location: /admin/annonces?success=annonce_archived');
                exit;
            }
        }
        
        header('Location: /admin/annonces');
    }
    
    public function restoreAnnonce($id)
    {
        if (!$this->session->get('admin_user')) {
            header('Location: /admin/login');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $csrfToken = $_POST['_csrf'] ?? '';
            if (!$this->security->verifyCsrfToken($csrfToken)) {
                die('CSRF token validation failed');
            }
            
            if ($this->annonceModel->restore($id)) {
                header('Location: /admin/archived-annonces?success=annonce_restored');
                exit;
            }
        }
        
        header('Location: /admin/archived-annonces');
    }
    
    public function students()
    {
        if (!$this->session->get('admin_user')) {
            header('Location: /admin/login');
            exit;
        }
        
        $students = $this->studentModel->getAll();
        
        $this->view('admin/students', [
            'students' => $students,
            'csrfToken' => $this->security->generateCsrfToken()
        ]);
    }
    
    public function logout()
    {
        $this->session->destroy();
        header('Location: /admin/login');
        exit;
    }
}
