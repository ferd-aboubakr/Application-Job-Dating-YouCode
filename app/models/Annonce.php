<?php
namespace App\Models;

use App\Core\Model;

class Annonce extends Model
{
    protected $table = 'annonces';
    
    public function getAll()
    {
        $sql = "SELECT a.*, e.NAME as entreprise_name FROM {$this->table} a 
                LEFT JOIN entreprises e ON a.entreprise_id = e.id 
                ORDER BY a.created_at DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    public function getActive()
    {
        $sql = "SELECT a.*, e.NAME as entreprise_name FROM {$this->table} a 
                LEFT JOIN entreprises e ON a.entreprise_id = e.id 
                WHERE a.deleted = FALSE 
                ORDER BY a.created_at DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    public function getRecent($limit = 3)
    {
        return $this->db->query("SELECT a.*, e.NAME as entreprise_name FROM {$this->table} a 
                LEFT JOIN entreprises e ON a.entreprise_id = e.id 
                WHERE a.deleted = FALSE 
                ORDER BY a.created_at DESC 
                LIMIT :limit", ['limit' => $limit])->fetchAll();
    }
    
    public function getArchived()
    {
        $sql = "SELECT a.*, e.NAME as entreprise_name FROM {$this->table} a 
                LEFT JOIN entreprises e ON a.entreprise_id = e.id 
                WHERE a.deleted = TRUE 
                ORDER BY a.created_at DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    public function search($query = '', $entreprise = '', $contrat = '')
    {
        $sql = "SELECT a.*, e.NAME as entreprise_name FROM {$this->table} a 
                LEFT JOIN entreprises e ON a.entreprise_id = e.id 
                WHERE a.deleted = FALSE";
        
        $params = [];
        
        if (!empty($query)) {
            $sql .= " AND (a.titre LIKE :query OR a.description LIKE :query OR a.competences LIKE :query)";
            $params['query'] = "%{$query}%";
        }
        
        if (!empty($entreprise)) {
            $sql .= " AND a.entreprise_id = :entreprise";
            $params['entreprise'] = $entreprise;
        }
        
        if (!empty($contrat)) {
            $sql .= " AND a.type_contrat = :contrat";
            $params['contrat'] = $contrat;
        }
        
        $sql .= " ORDER BY a.created_at DESC";
        
        return $this->db->query($sql, $params)->fetchAll();
    }
    
    public function findById($id)
    {
        $sql = "SELECT a.*, e.NAME as entreprise_name FROM {$this->table} a 
                LEFT JOIN entreprises e ON a.entreprise_id = e.id 
                WHERE a.id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (titre, entreprise, type_contrat, localisation, image, description, competences, entreprise_id) 
                VALUES (:titre, :entreprise, :type_contrat, :localisation, :image, :description, :competences, :entreprise_id)";
        
        $params = [
            'titre' => $data['titre'],
            'entreprise' => $data['entreprise'] ?? null,
            'type_contrat' => $data['type_contrat'],
            'localisation' => $data['localisation'],
            'image' => $data['image'] ?? null,
            'description' => $data['description'],
            'competences' => $data['competences'] ?? null,
            'entreprise_id' => $data['entreprise_id']
        ];
        
        return $this->db->query($sql, $params);
    }
    
    public function update($id, $data)
    {
        $sql = "UPDATE {$this->table} SET 
                titre = :titre, 
                entreprise = :entreprise, 
                type_contrat = :type_contrat, 
                localisation = :localisation, 
                image = :image, 
                description = :description, 
                competences = :competences,
                entreprise_id = :entreprise_id,
                updated_at = CURRENT_TIMESTAMP
                WHERE id = :id";
        
        $params = [
            'id' => $id,
            'titre' => $data['titre'],
            'entreprise' => $data['entreprise'],
            'type_contrat' => $data['type_contrat'],
            'localisation' => $data['localisation'],
            'image' => $data['image'] ?? null,
            'description' => $data['description'],
            'competences' => $data['competences'],
            'entreprise_id' => $data['entreprise_id']
        ];
        
        return $this->db->query($sql, $params);
    }
    
    public function archive($id)
    {
        $sql = "UPDATE {$this->table} SET deleted = TRUE WHERE id = :id";
        return $this->db->query($sql, ['id' => $id]);
    }
    
    public function restore($id)
    {
        $sql = "UPDATE {$this->table} SET deleted = FALSE WHERE id = :id";
        return $this->db->query($sql, ['id' => $id]);
    }
    
    public function getStatistics()
    {
        $stats = [];
        
        // Active announcements
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE deleted = FALSE";
        $stmt = $this->db->query($sql);
        $stats['active'] = $stmt->fetch()['count'];
        
        // Archived announcements
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE deleted = TRUE";
        $stmt = $this->db->query($sql);
        $stats['archived'] = $stmt->fetch()['count'];
        
        return $stats;
    }
}
