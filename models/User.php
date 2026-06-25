<?php
/**
 * User Model
 * Handles user authentication and management
 */

require_once __DIR__ . '/Database.php';

class User {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Authenticate user by username/email and password
     */
    public function authenticate($username, $password) {
        $sql = "SELECT * FROM users WHERE (username = :username OR email = :username) AND is_active = 1 LIMIT 1";
        $user = $this->db->fetchOne($sql, ['username' => $username]);
        
        if ($user && password_verify($password, $user['password_hash'])) {
            // Update last login
            $this->db->update('users', ['last_login' => date('Y-m-d H:i:s')], 'id = :id', ['id' => $user['id']]);
            return $user;
        }
        
        return false;
    }
    
    /**
     * Get user by ID
     */
    public function getById($id) {
        $sql = "SELECT * FROM users WHERE id = :id LIMIT 1";
        return $this->db->fetchOne($sql, ['id' => $id]);
    }
    
    /**
     * Get user with role-specific data
     */
    public function getWithProfile($id) {
        $user = $this->getById($id);
        if (!$user) return null;
        
        // Get role-specific data based on user role
        switch ($user['role']) {
            case 'siswa':
                $sql = "SELECT s.*, k.nama_kelas, j.nama_jurusan 
                        FROM siswa s 
                        JOIN kelas k ON s.kelas_id = k.id 
                        JOIN jurusan j ON k.jurusan_id = j.id 
                        WHERE s.user_id = :user_id";
                $user['profile'] = $this->db->fetchOne($sql, ['user_id' => $id]);
                break;
            case 'guru':
            case 'wali_kelas':
                $sql = "SELECT COUNT(*) as total_kelas, COUNT(DISTINCT gm.kelas_id) as assigned_classes 
                        FROM guru_mapel gm 
                        WHERE gm.guru_id = :user_id";
                $user['stats'] = $this->db->fetchOne($sql, ['user_id' => $id]);
                break;
            case 'orang_tua':
                $sql = "SELECT ot.*, s.nis, s.nama_ortu, u.full_name as student_name, k.nama_kelas 
                        FROM orang_tua ot 
                        JOIN siswa s ON ot.siswa_id = s.id 
                        JOIN users u ON s.user_id = u.id 
                        JOIN kelas k ON s.kelas_id = k.id 
                        WHERE ot.user_id = :user_id";
                $user['children'] = $this->db->fetchAll($sql, ['user_id' => $id]);
                break;
        }
        
        return $user;
    }
    
    /**
     * Create new user
     */
    public function create($data) {
        $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
        unset($data['password']);
        
        return $this->db->insert('users', $data);
    }
    
    /**
     * Update user
     */
    public function update($id, $data) {
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
            unset($data['password']);
        }
        
        return $this->db->update('users', $data, 'id = :id', ['id' => $id]);
    }
    
    /**
     * Get users by role
     */
    public function getByRole($role, $active = true) {
        $sql = "SELECT * FROM users WHERE role = :role";
        if ($active) {
            $sql .= " AND is_active = 1";
        }
        $sql .= " ORDER BY full_name ASC";
        
        return $this->db->fetchAll($sql, ['role' => $role]);
    }
    
    /**
     * Get all users with pagination
     */
    public function getAll($page = 1, $perPage = 10, $filters = []) {
        $where = ['1=1'];
        $params = [];
        
        if (isset($filters['role'])) {
            $where[] = "role = :role";
            $params['role'] = $filters['role'];
        }
        
        if (isset($filters['search'])) {
            $where[] = "(full_name LIKE :search OR username LIKE :search OR email LIKE :search)";
            $params['search'] = "%{$filters['search']}%";
        }
        
        $whereClause = implode(' AND ', $where);
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT * FROM users WHERE {$whereClause} ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        $params['limit'] = $perPage;
        $params['offset'] = $offset;
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Count users
     */
    public function count($filters = []) {
        $where = ['1=1'];
        $params = [];
        
        if (isset($filters['role'])) {
            $where[] = "role = :role";
            $params['role'] = $filters['role'];
        }
        
        if (isset($filters['search'])) {
            $where[] = "(full_name LIKE :search OR username LIKE :search OR email LIKE :search)";
            $params['search'] = "%{$filters['search']}%";
        }
        
        $whereClause = implode(' AND ', $where);
        $sql = "SELECT COUNT(*) as total FROM users WHERE {$whereClause}";
        
        return $this->db->fetchOne($sql, $params)['total'];
    }
    
    /**
     * Check if username or email exists
     */
    public function exists($username = null, $email = null, $excludeId = null) {
        $where = [];
        $params = [];
        
        if ($username) {
            $where[] = "username = :username";
            $params['username'] = $username;
        }
        
        if ($email) {
            $where[] = "email = :email";
            $params['email'] = $email;
        }
        
        if ($excludeId) {
            $where[] = "id != :id";
            $params['id'] = $excludeId;
        }
        
        $whereClause = implode(' OR ', $where);
        $sql = "SELECT COUNT(*) as count FROM users WHERE {$whereClause}";
        
        return $this->db->fetchOne($sql, $params)['count'] > 0;
    }
    
    /**
     * Reset password
     */
    public function resetPassword($userId, $newPassword) {
        return $this->db->update('users', 
            ['password_hash' => password_hash($newPassword, PASSWORD_DEFAULT)], 
            'id = :id', 
            ['id' => $userId]
        );
    }
    
    /**
     * Update last login timestamp
     */
    public function updateLastLogin($userId) {
        return $this->db->update('users', 
            ['last_login' => date('Y-m-d H:i:s')], 
            'id = :id', 
            ['id' => $userId]
        );
    }
}
