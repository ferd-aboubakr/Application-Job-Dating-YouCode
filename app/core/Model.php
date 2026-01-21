<?php
namespace App\Core;

use App\Core\Database;


abstract class Model {
    protected $db;
    protected $table;
    protected $primaryKey = 'id';

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * CRUD operations
     */

    public function find($id) {
       return $this->db->query("SELECT * FROM {$this->getTableName()} WHERE {$this->getPrimaryKey()} = :id LIMIT 1", ['id' => $id])->fetch();
    }

    public function all() {
       return $this->db->query("SELECT * FROM {$this->getTableName()}")->fetchAll();
    }

    /**
     * Insert a new record @param array $data Associative array of column => value
     */

    public function create(array $data) {
        $allowedColumns = $this->getAllowedColumns();
        $data = array_intersect_key($data, array_flip($allowedColumns));
        
        if (empty($data)) {
            throw new \Exception('No valid data provided');
        }
        
        $columns = implode(", ", array_map([$this, 'escapeColumn'], array_keys($data)));
        $placeholders = ":" . implode(", :", array_keys($data));

        $sql = "INSERT INTO {$this->getTableName()} ({$columns}) VALUES ({$placeholders})";
        
        $this->db->query($sql, $data);
        return $this->db->lastInsertId();
    }

    public function update($id, array $data) {
        $allowedColumns = $this->getAllowedColumns();
        $data = array_intersect_key($data, array_flip($allowedColumns));
        
        if (empty($data)) {
            throw new \Exception('No valid data provided');
        }
        
        $fields = "";
        foreach ($data as $key => $value) {
            $escapedKey = $this->escapeColumn($key);
            $fields .= "{$escapedKey} = :{$key}, ";
        }
        $fields = rtrim($fields, ", ");

        $sql = "UPDATE {$this->getTableName()} SET {$fields} WHERE {$this->getPrimaryKey()} = :id";
        
        $data['id'] = $id;
        
       return $this->db->query($sql, $data);
    }

    public function delete($id) {
       return $this->db->query("DELETE FROM {$this->getTableName()} WHERE {$this->getPrimaryKey()} = :id", ['id' => $id]);
    }

    /**
    *Custom Query Helper 
    */    
    public function where($column, $value) {
        $escapedColumn = $this->escapeColumn($column);
        return $this->db->query("SELECT * FROM {$this->getTableName()} WHERE {$escapedColumn} = :val", ['val' => $value])->fetchAll();        
    }
    
    /**
    * Helper methods 
    */ 
    protected function getTableName(): string {
        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $this->table)) {
            throw new \Exception('Invalid table name');
        }
        return $this->table;
    }
    
    protected function getPrimaryKey(): string {
        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $this->primaryKey)) {
            throw new \Exception('Invalid primary key');
        }
        return $this->primaryKey;
    }
    
    protected function escapeColumn(string $column): string {
        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $column)) {
            throw new \Exception('Invalid column name: ' . $column);
        }
        return $column;
    }
    
    protected function getAllowedColumns(): array {
        return [];
    }
}