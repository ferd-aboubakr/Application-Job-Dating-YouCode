<?php

namespace App\core;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class View
{
    private $viewPath;

    
    public function renders(string $view, array $data = [])
    {
        $view = str_replace(['../', '..\\', '//', '\\\\'], '', $view);
        $view = trim($view, '/\\');
        
        if (!preg_match('/^[a-zA-Z0-9_\/\\-]+$/', $view)) {
            throw new \Exception('Invalid view name');
        }
        
        $viewsDir = realpath(__DIR__ . '/../views');
        $this->viewPath = $viewsDir . DIRECTORY_SEPARATOR . $view . '.php';
        
        $realPath = realpath($this->viewPath);
        if (!$realPath || strpos($realPath, $viewsDir) !== 0 || !file_exists($this->viewPath)) {
            throw new \Exception('View not found: ' . $view);
        }

        foreach ($data as $key => $value) {
            $$key = $value;
        }

        ob_start();
        require $this->viewPath;
        $content = ob_get_clean();
        
        return $content;
    }

     private static ?Environment $twig = null;

    public static function twigView(string $template, array $data = []): void
    {
        if (self::$twig === null) {
            $view = __DIR__ . '/../views';
            $loader = new FilesystemLoader($view);

            self::$twig = new Environment($loader, [
                'cache' => false, 
                'debug' => true,
            ]);
        }

        echo self::$twig->render($template . '.twig', $data);
    }

    
}
