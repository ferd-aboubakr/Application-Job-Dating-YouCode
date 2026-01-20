<?php
namespace App\Core;

class Security
{
    /**
    * Generates a CSRF token 
    */
    public function generateCsrfToken(): string
    {
        if (!Session::getInstance()->has('_csrf_token')) {
            $token = bin2hex(random_bytes(32));
            Session::getInstance()->set('_csrf_token', $token);
        }
        return Session::getInstance()->get('_csrf_token');
    }

    /**
     * Verify the CSRF token
     */

    public function verifyCsrfToken($token)
    {
        $sessionToken = Session::getInstance()->get('_csrf_token');
        if (!$sessionToken || !$token) {
            return false;
        }
        return hash_equals($sessionToken, $token);
    }

    /**
     * Cleans a chain against XSS attacks
     */

    public static function clean($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        return $data;
    }

    /**
     *Cleans data against XSS
     */
    public function sanitize($data)
    {
        if (is_array($data)) {
            return array_map([$this, 'sanitize'], $data);
        }

        return htmlspecialchars(strip_tags($data), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Validates and cleans an email
     */

    public function sanitizeEmail(string $email): string
    {
        return filter_var($email, FILTER_SANITIZE_EMAIL);
    }

    /**
     * Hash a password securely
     */

    public function hashPassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * Verify a password
     */

    public function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }

    /**
     * Checks if the user is logged in
     */

    public function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }

    public function sanitizeUrl(string $url): string
    {
        return filter_var($url, FILTER_SANITIZE_URL);
    }

    /**
     * Generates a secure random token
     */

    public function generateToken(int $length = 32): string
    {
        return bin2hex(random_bytes($length));
    }

    /**
     * Protects against SQL injections (to be used with PDO)
     */
    public function escapeString(string $string): string
    {
        return addslashes($string);
    }
}