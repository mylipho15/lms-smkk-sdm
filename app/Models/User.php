<?php
/**
 * User Model - app/Models/User.php
 * 
 * Model untuk menangani operasi database terkait pengguna
 */

namespace App\Models;

use App\Core\Model;

class User extends Model {
    protected $table = 'users';
    protected $primaryKey = 'id';
    
    /**
     * Get user by ID with role information
     */
    public function findById($id) {
        $sql = "SELECT u.*, r.name as role_name, r.display_name as role_display_name 
                FROM {$this->table} u 
                JOIN roles r ON u.role_id = r.id 
                WHERE u.id = :id";
        return $this->query($sql, ['id' => $id])->fetch();
    }
    
    /**
     * Get user by username or email
     */
    public function findByUsernameOrEmail($identifier) {
        $sql = "SELECT u.*, r.name as role_name, r.display_name as role_display_name 
                FROM {$this->table} u 
                JOIN roles r ON u.role_id = r.id 
                WHERE u.username = :identifier OR u.email = :identifier";
        return $this->query($sql, ['identifier' => $identifier])->fetch();
    }
    
    /**
     * Get user by email
     */
    public function findByEmail($email) {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email";
        return $this->query($sql, ['email' => $email])->fetch();
    }
    
    /**
     * Get user by username
     */
    public function findByUsername($username) {
        $sql = "SELECT * FROM {$this->table} WHERE username = :username";
        return $this->query($sql, ['username' => $username])->fetch();
    }
    
    /**
     * Get all users with role filter
     */
    public function getAllByRole($roleId = null) {
        if ($roleId) {
            $sql = "SELECT u.*, r.name as role_name 
                    FROM {$this->table} u 
                    JOIN roles r ON u.role_id = r.id 
                    WHERE u.role_id = :role_id 
                    ORDER BY u.full_name ASC";
            return $this->query($sql, ['role_id' => $roleId])->fetchAll();
        }
        
        $sql = "SELECT u.*, r.name as role_name 
                FROM {$this->table} u 
                JOIN roles r ON u.role_id = r.id 
                ORDER BY u.full_name ASC";
        return $this->query($sql)->fetchAll();
    }
    
    /**
     * Create new user
     */
    public function create($data) {
        $sql = "INSERT INTO {$this->table} 
                (role_id, nip_nis, username, email, password, full_name, gender, phone, avatar, birth_date, address, is_active) 
                VALUES 
                (:role_id, :nip_nis, :username, :email, :password, :full_name, :gender, :phone, :avatar, :birth_date, :address, :is_active)";
        
        return $this->execute($sql, $data);
    }
    
    /**
     * Update user
     */
    public function update($id, $data) {
        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
        }
        $fieldStr = implode(', ', $fields);
        
        $sql = "UPDATE {$this->table} SET $fieldStr WHERE id = :id";
        $data['id'] = $id;
        
        return $this->execute($sql, $data);
    }
    
    /**
     * Update last login
     */
    public function updateLastLogin($id) {
        $sql = "UPDATE {$this->table} SET last_login = NOW() WHERE id = :id";
        return $this->execute($sql, ['id' => $id]);
    }
    
    /**
     * Set remember token
     */
    public function setRememberToken($id, $token) {
        $sql = "UPDATE {$this->table} SET remember_token = :token WHERE id = :id";
        return $this->execute($sql, ['id' => $id, 'token' => $token]);
    }
    
    /**
     * Delete user
     */
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        return $this->execute($sql, ['id' => $id]);
    }
    
    /**
     * Check if username exists
     */
    public function usernameExists($username, $excludeId = null) {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE username = :username";
        if ($excludeId) {
            $sql .= " AND id != :exclude_id";
        }
        $result = $this->query($sql, ['username' => $username, 'exclude_id' => $excludeId])->fetch();
        return $result['count'] > 0;
    }
    
    /**
     * Check if email exists
     */
    public function emailExists($email, $excludeId = null) {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE email = :email";
        if ($excludeId) {
            $sql .= " AND id != :exclude_id";
        }
        $result = $this->query($sql, ['email' => $email, 'exclude_id' => $excludeId])->fetch();
        return $result['count'] > 0;
    }
    
    /**
     * Get students by class
     */
    public function getStudentsByClass($kelasId) {
        $sql = "SELECT u.*, s.nis, s.nisn, k.nama as kelas_nama 
                FROM {$this->table} u 
                JOIN siswa s ON u.id = s.user_id 
                JOIN kelas k ON s.kelas_id = k.id 
                WHERE s.kelas_id = :kelas_id 
                ORDER BY u.full_name ASC";
        return $this->query($sql, ['kelas_id' => $kelasId])->fetchAll();
    }
    
    /**
     * Get teachers
     */
    public function getTeachers() {
        $sql = "SELECT u.* 
                FROM {$this->table} u 
                JOIN roles r ON u.role_id = r.id 
                WHERE r.name IN ('guru', 'admin') 
                ORDER BY u.full_name ASC";
        return $this->query($sql)->fetchAll();
    }
    
    /**
     * Search users
     */
    public function search($keyword) {
        $sql = "SELECT u.*, r.name as role_name 
                FROM {$this->table} u 
                JOIN roles r ON u.role_id = r.id 
                WHERE u.full_name LIKE :keyword OR u.username LIKE :keyword OR u.email LIKE :keyword 
                ORDER BY u.full_name ASC";
        return $this->query($sql, ['keyword' => "%{$keyword}%"])->fetchAll();
    }
}
