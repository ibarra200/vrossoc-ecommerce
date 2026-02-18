<?php
// app/models/Producto.php

class Producto extends Model {
    protected $table = 'productos';
    
    /**
     * Obtener productos destacados para la página de inicio
     */
    public function getDestacados($limite = 4) {
        $query = "SELECT p.*, c.nombre as categoria_nombre 
                  FROM {$this->table} p
                  LEFT JOIN categorias c ON p.categoria_id = c.id
                  WHERE p.destacado = TRUE AND p.estado = 'activo'
                  LIMIT :limite";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener todos los productos activos
     */
    public function getTodos() {
        $query = "SELECT p.*, c.nombre as categoria_nombre 
                  FROM {$this->table} p
                  LEFT JOIN categorias c ON p.categoria_id = c.id
                  WHERE p.estado = 'activo'
                  ORDER BY p.created_at DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener un producto con sus variantes e imágenes
     */
    public function getConDetalles($id) {
        $query = "SELECT p.*, c.nombre as categoria_nombre 
                  FROM {$this->table} p
                  LEFT JOIN categorias c ON p.categoria_id = c.id
                  WHERE p.id = :id AND p.estado = 'activo'";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);
        $producto = $stmt->fetch();
        
        if ($producto) {
            // Obtener variantes (talles y colores con stock)
            $query = "SELECT * FROM producto_variantes 
                      WHERE producto_id = :id AND stock > 0
                      ORDER BY color, talle";
            $stmt = $this->db->prepare($query);
            $stmt->execute(['id' => $id]);
            $producto['variantes'] = $stmt->fetchAll();
            
            // Obtener imágenes
            $query = "SELECT * FROM producto_imagenes 
                      WHERE producto_id = :id 
                      ORDER BY orden ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute(['id' => $id]);
            $producto['imagenes'] = $stmt->fetchAll();
        }
        
        return $producto;
    }
    
    /**
     * Obtener productos por categoría
     */
    public function getPorCategoria($categoriaId) {
        $query = "SELECT p.*, c.nombre as categoria_nombre 
                  FROM {$this->table} p
                  LEFT JOIN categorias c ON p.categoria_id = c.id
                  WHERE p.categoria_id = :categoria_id AND p.estado = 'activo'
                  ORDER BY p.created_at DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute(['categoria_id' => $categoriaId]);
        
        return $stmt->fetchAll();
    }
}