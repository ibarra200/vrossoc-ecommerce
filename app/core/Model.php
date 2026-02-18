<?php
// app/core/Model.php
// Usar la constante definida
require_once ROOT_PATH . '/config/database.php';

class Model {
    protected $db;
    protected $table;
    
    public function __construct() {
        $database = Database::getInstance();
        $this->db = $database->getConnection();
    }
    // Obtener todos los registros
    public function getAll() {
        $query = "SELECT * FROM {$this->table}";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Obtener por ID
    public function getById($id) {
        $query = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    // Crear registro (recibe array asociativo)
    public function create($data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $query = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        $stmt = $this->db->prepare($query);
        
        return $stmt->execute($data);
    }
    
    // Actualizar registro
    public function update($id, $data) {
        $fields = '';
        foreach (array_keys($data) as $key) {
            $fields .= "{$key} = :{$key}, ";
        }
        $fields = rtrim($fields, ', ');
        
        $query = "UPDATE {$this->table} SET {$fields} WHERE id = :id";
        $data['id'] = $id;
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute($data);
    }
    
    // Eliminar registro
    public function delete($id) {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(['id' => $id]);
    }
}