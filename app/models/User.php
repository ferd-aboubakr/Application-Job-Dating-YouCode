<?php
namespace App\Models;

use App\Core\Model;

class User extends Model
{
    protected $table = 'users';
    
    public function findByEmail($email)
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";
        return $this->db->query("SELECT * FROM {$this->table} WHERE email = :email LIMIT 1", ['email' => $email])->fetch();
    }
    
    public function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }
    
    public function login($email, $password)
    {
        $user = $this->findByEmail($email);
        
        if ($user && $this->verifyPassword($password, $user['password_hash']) && $user['ROLE'] === 'admin') {
            return $user;
        }
        
        return false;
    }
}

class Announcement extends Model
{
    // Modèle des annonces
}

class Company extends Model
{
    // Modèle des entreprises
}

class Student extends Model
{
    protected $table = 'users';
    
    public function findByEmail($email)
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";
        return $this->db->query("SELECT * FROM {$this->table} WHERE email = :email LIMIT 1", ['email' => $email])->fetch();
    }
    
    public function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }
    
    public function login($email, $password)
    {
        $user = $this->findByEmail($email);
        
        if ($user && $this->verifyPassword($password, $user['password_hash']) && $user['ROLE'] === 'apprenant') {
            return $user;
        }
        
        return false;
    }
    
    public function getAll()
    {
        return $this->db->query("SELECT id, name, email, promotion, specialisation, created_at 
                FROM {$this->table} 
                WHERE ROLE = 'apprenant' 
                ORDER BY created_at DESC")->fetchAll();
    }
    
    public function create($data)
    {
        $stmt = $this->db->query("INSERT INTO {$this->table} (name, email, password_hash, promotion, specialisation, ROLE) 
                VALUES (:name, :email, :password_hash, :promotion, :specialisation, :role)", [
            'name' => $data['name'],
            'email' => $data['email'],
            'password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
            'promotion' => $data['promotion'] ?? null,
            'specialisation' => $data['specialisation'] ?? null,
            'role' => 'apprenant'
        ]);
    }
}