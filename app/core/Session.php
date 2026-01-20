<?php

namespace App\Core;

class Session
{
    private static $instance = null;

    private function __construct()
    {
        //Session start
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.gc_maxlifetime', 1800);

            // cookies
            session_set_cookie_params([
                'lifetime' => 1800,
                'path' => '/',
                'domain' => '',
                'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
                'httponly' => true,
                'samesite' => 'Strict'
            ]);

            session_start();
        }

        if (!isset($_SESSION['initiated'])) {
            session_regenerate_id(true);
            $_SESSION['initiated'] = true;
        }
    }

    /**
     * Retrieves the single instance (Singleton)
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    // method for get a session value by key 
    public function get($key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    // method for set a session value by key
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }
    /**
     * Check if a key exists
     */
    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    // method for remove a session value by key
    public function delete($key)
    {
        unset($_SESSION[$key]);
    }
    /* 
    * method for logout
    */
    public function destroy()
    {
        session_destroy();
        $_SESSION = [];
    }

    /**
     * 
     */
    public function flash(string $key, $value = null)
    {
        if ($value === null) {
            $message = $this->get("flash_{$key}");
            $this->delete("flash_{$key}");
            return $message;
        }

        $this->set("flash_{$key}", $value);
    }

    /**
     * Regenerates the session ID (security)
     */
    public function regenerate(): void
    {
        session_regenerate_id(true);
    }

    /**
     * Retrieves all session data
     */
    public function all(): array
    {
        return $_SESSION;
    }
}
