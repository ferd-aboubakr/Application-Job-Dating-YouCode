<?php
namespace App\Models;

use App\Core\Model;

class Entreprise extends Model
{
    protected $table = 'entreprises';
    
    public function getAll()
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY id DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    public function findById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (NAME, secteur, localisation, email, telephone) 
                VALUES (:name, :secteur, :localisation, :email, :telephone)";
        
        $params = [
            'name' => $data['NAME'],
            'secteur' => $data['secteur'],
            'localisation' => $data['localisation'],
            'email' => $data['email'],
            'telephone' => $data['telephone'] ?? null
        ];
        
        return $this->db->query($sql, $params);
    }
    
    public function update($id, $data)
    {
        $sql = "UPDATE {$this->table} SET 
                NAME = :name, 
                secteur = :secteur, 
                localisation = :localisation, 
                email = :email, 
                telephone = :telephone
                WHERE id = :id";
        
        $params = [
            'id' => $id,
            'name' => $data['NAME'],
            'secteur' => $data['secteur'],
            'localisation' => $data['localisation'],
            'email' => $data['email'],
            'telephone' => $data['telephone']
        ];
        
        return $this->db->query($sql, $params);
    }
    
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
