<?php
/**
 * Material Model - app/Models/Material.php
 * 
 * Model untuk menangani operasi database terkait materi pembelajaran
 */

namespace App\Models;

use App\Core\Model;

class Material extends Model {
    protected $table = 'materi';
    protected $primaryKey = 'id';
    
    /**
     * Get all materials with relations
     */
    public function getAll() {
        $sql = "SELECT m.*, u.full_name as guru_nama, mp.nama as mapel_nama, k.nama as kelas_nama 
                FROM {$this->table} m 
                JOIN users u ON m.guru_id = u.id 
                JOIN mata_pelajaran mp ON m.mapel_id = mp.id 
                LEFT JOIN kelas k ON m.kelas_id = k.id 
                ORDER BY m.created_at DESC";
        return $this->query($sql)->fetchAll();
    }
    
    /**
     * Get material by ID
     */
    public function findById($id) {
        $sql = "SELECT m.*, u.full_name as guru_nama, mp.nama as mapel_nama, k.nama as kelas_nama 
                FROM {$this->table} m 
                JOIN users u ON m.guru_id = u.id 
                JOIN mata_pelajaran mp ON m.mapel_id = mp.id 
                LEFT JOIN kelas k ON m.kelas_id = k.id 
                WHERE m.id = :id";
        return $this->query($sql, ['id' => $id])->fetch();
    }
    
    /**
     * Get materials by teacher
     */
    public function getByTeacher($guruId) {
        $sql = "SELECT m.*, mp.nama as mapel_nama, k.nama as kelas_nama 
                FROM {$this->table} m 
                JOIN mata_pelajaran mp ON m.mapel_id = mp.id 
                LEFT JOIN kelas k ON m.kelas_id = k.id 
                WHERE m.guru_id = :guru_id 
                ORDER BY m.created_at DESC";
        return $this->query($sql, ['guru_id' => $guruId])->fetchAll();
    }
    
    /**
     * Get materials by course
     */
    public function getByCourse($mapelId) {
        $sql = "SELECT m.*, u.full_name as guru_nama, k.nama as kelas_nama 
                FROM {$this->table} m 
                JOIN users u ON m.guru_id = u.id 
                LEFT JOIN kelas k ON m.kelas_id = k.id 
                WHERE m.mapel_id = :mapel_id AND m.is_published = 1 
                ORDER BY m.created_at DESC";
        return $this->query($sql, ['mapel_id' => $mapelId])->fetchAll();
    }
    
    /**
     * Get materials by class
     */
    public function getByKelas($kelasId) {
        $sql = "SELECT m.*, u.full_name as guru_nama, mp.nama as mapel_nama 
                FROM {$this->table} m 
                JOIN users u ON m.guru_id = u.id 
                JOIN mata_pelajaran mp ON m.mapel_id = mp.id 
                WHERE m.kelas_id = :kelas_id AND m.is_published = 1 
                ORDER BY m.created_at DESC";
        return $this->query($sql, ['kelas_id' => $kelasId])->fetchAll();
    }
    
    /**
     * Get materials for student (by class and courses)
     */
    public function getForStudent($siswaId, $kelasId) {
        $sql = "SELECT m.*, u.full_name as guru_nama, mp.nama as mapel_nama 
                FROM {$this->table} m 
                JOIN users u ON m.guru_id = u.id 
                JOIN mata_pelajaran mp ON m.mapel_id = mp.id 
                WHERE (m.kelas_id = :kelas_id OR m.kelas_id IS NULL) AND m.is_published = 1 
                ORDER BY m.created_at DESC";
        return $this->query($sql, ['kelas_id' => $kelasId])->fetchAll();
    }
    
    /**
     * Create new material
     */
    public function create($data) {
        $sql = "INSERT INTO {$this->table} 
                (guru_id, mapel_id, kelas_id, judul, deskripsi, jenis, file_path, link_url, konten, is_published) 
                VALUES 
                (:guru_id, :mapel_id, :kelas_id, :judul, :deskripsi, :jenis, :file_path, :link_url, :konten, :is_published)";
        return $this->execute($sql, $data);
    }
    
    /**
     * Update material
     */
    public function update($id, $data) {
        $sql = "UPDATE {$this->table} 
                SET judul = :judul, deskripsi = :deskripsi, jenis = :jenis, file_path = :file_path, 
                    link_url = :link_url, konten = :konten, is_published = :is_published 
                WHERE id = :id";
        $data['id'] = $id;
        return $this->execute($sql, $data);
    }
    
    /**
     * Increment view count
     */
    public function incrementViews($id) {
        $sql = "UPDATE {$this->table} SET views = views + 1 WHERE id = :id";
        return $this->execute($sql, ['id' => $id]);
    }
    
    /**
     * Delete material
     */
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        return $this->execute($sql, ['id' => $id]);
    }
    
    /**
     * Search materials
     */
    public function search($keyword, $kelasId = null) {
        if ($kelasId) {
            $sql = "SELECT m.*, u.full_name as guru_nama, mp.nama as mapel_nama, k.nama as kelas_nama 
                    FROM {$this->table} m 
                    JOIN users u ON m.guru_id = u.id 
                    JOIN mata_pelajaran mp ON m.mapel_id = mp.id 
                    LEFT JOIN kelas k ON m.kelas_id = k.id 
                    WHERE (m.judul LIKE :keyword OR m.deskripsi LIKE :keyword) 
                    AND (m.kelas_id = :kelas_id OR m.kelas_id IS NULL) 
                    AND m.is_published = 1 
                    ORDER BY m.created_at DESC";
            return $this->query($sql, ['keyword' => "%{$keyword}%", 'kelas_id' => $kelasId])->fetchAll();
        }
        
        $sql = "SELECT m.*, u.full_name as guru_nama, mp.nama as mapel_nama, k.nama as kelas_nama 
                FROM {$this->table} m 
                JOIN users u ON m.guru_id = u.id 
                JOIN mata_pelajaran mp ON m.mapel_id = mp.id 
                LEFT JOIN kelas k ON m.kelas_id = k.id 
                WHERE (m.judul LIKE :keyword OR m.deskripsi LIKE :keyword) AND m.is_published = 1 
                ORDER BY m.created_at DESC";
        return $this->query($sql, ['keyword' => "%{$keyword}%"])->fetchAll();
    }
    
    /**
     * Get materials by type
     */
    public function getByType($jenis, $limit = 10) {
        $sql = "SELECT m.*, u.full_name as guru_nama, mp.nama as mapel_nama 
                FROM {$this->table} m 
                JOIN users u ON m.guru_id = u.id 
                JOIN mata_pelajaran mp ON m.mapel_id = mp.id 
                WHERE m.jenis = :jenis AND m.is_published = 1 
                ORDER BY m.created_at DESC LIMIT :limit";
        return $this->query($sql, ['jenis' => $jenis, 'limit' => $limit])->fetchAll();
    }
}
