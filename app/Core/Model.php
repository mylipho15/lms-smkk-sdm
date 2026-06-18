<?php
/**
 * Model.php - Base Model
 * 
 * Base Model dengan koneksi database untuk LMS SMK Kesehatan SDM Sumedang
 */

namespace App\Core;

class Model {
    protected $db;
    protected $table;
    
    public function __construct() {
        require_once BASE_PATH . '/config/database.php';
        $this->db = getDB();
    }
    
    /**
     * Get all records from table
     * 
     * @param array $where WHERE clause conditions
     * @param string $orderBy ORDER BY clause
     * @param int $limit LIMIT clause
     * @return array
     */
    public function all($where = [], $orderBy = '', $limit = '') {
        $sql = "SELECT * FROM {$this->table}";
        
        $conditions = [];
        $params = [];
        
        if (!empty($where)) {
            foreach ($where as $key => $value) {
                $conditions[] = "$key = ?";
                $params[] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }
        
        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }
        
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll();
    }
    
    /**
     * Find single record by ID
     * 
     * @param int $id Primary key value
     * @return array|null
     */
    public function find($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        
        $result = $stmt->fetch();
        return $result ?: null;
    }
    
    /**
     * Find single record by column value
     * 
     * @param string $column Column name
     * @param mixed $value Column value
     * @return array|null
     */
    public function findBy($column, $value) {
        $sql = "SELECT * FROM {$this->table} WHERE {$column} = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$value]);
        
        $result = $stmt->fetch();
        return $result ?: null;
    }
    
    /**
     * Insert new record
     * 
     * @param array $data Associative array of column => value
     * @return int Last insert ID
     */
    public function insert($data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        $stmt = $this->db->prepare($sql);
        
        $stmt->execute($data);
        
        return $this->db->lastInsertId();
    }
    
    /**
     * Update record by ID
     * 
     * @param int $id Primary key value
     * @param array $data Associative array of column => value
     * @return int Number of affected rows
     */
    public function update($id, $data) {
        $set = [];
        foreach (array_keys($data) as $column) {
            $set[] = "{$column} = :{$column}";
        }
        $setString = implode(', ', $set);
        
        $sql = "UPDATE {$this->table} SET {$setString} WHERE id = :id";
        $data['id'] = $id;
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
        
        return $stmt->rowCount();
    }
    
    /**
     * Delete record by ID
     * 
     * @param int $id Primary key value
     * @return int Number of affected rows
     */
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        
        return $stmt->rowCount();
    }
    
    /**
     * Count records
     * 
     * @param array $where WHERE clause conditions
     * @return int
     */
    public function count($where = []) {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        
        $conditions = [];
        $params = [];
        
        if (!empty($where)) {
            foreach ($where as $key => $value) {
                $conditions[] = "$key = ?";
                $params[] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        $result = $stmt->fetch();
        return (int)$result['total'];
    }
    
    /**
     * Execute custom query
     * 
     * @param string $sql SQL query
     * @param array $params Query parameters
     * @return array|bool
     */
    public function query($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        // Check if it's a SELECT query
        if (stripos(trim($sql), 'SELECT') === 0) {
            return $stmt->fetchAll();
        }
        
        return $stmt->rowCount();
    }
    
    /**
     * Begin transaction
     * 
     * @return bool
     */
    public function beginTransaction() {
        return $this->db->beginTransaction();
    }
    
    /**
     * Commit transaction
     * 
     * @return bool
     */
    public function commit() {
        return $this->db->commit();
    }
    
    /**
     * Rollback transaction
     * 
     * @return bool
     */
    public function rollback() {
        return $this->db->rollBack();
    }
}
