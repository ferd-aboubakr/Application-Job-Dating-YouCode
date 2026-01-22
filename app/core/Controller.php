<?php
namespace App\Core;

abstract class Controller
{
    protected $view;
    protected $security;
    protected $session;
    protected $validator;

    public function __construct()
    {
        $this->view = new View();
        $this->security = new Security();
        $this->session = Session::getInstance();
        $this->validator = new Validator();
    }

    protected function view($template, $data = [])
    {
        View::twigView($template, $data);
    }

    protected function redirect(string $url, int $statusCode = 302)
    {
        http_response_code($statusCode);
        header("Location: $url");
        exit();
    }

    protected function twigView($view, $data = [])
    {
        View::twigView($view, $data);
    }

    protected function json(array $data, int $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

    protected function verifyCsrf()
    {
        if (!$this->security->verifyCsrfToken($_POST['_token'] ?? '')) {
            $this->json(['error' => 'Invalid CSRF token'], 403);
            exit();
        }
    }
}
