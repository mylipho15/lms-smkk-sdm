<?php
/**
 * Course Model - app/Models/Course.php
 * 
 * Model untuk menangani operasi database terkait mata pelajaran/kursus
 */

namespace App\Models;

use App\Core\Model;

class Course extends Model {
    protected $table = 'mata_pelajaran';
    protected $primaryKey = 'id';
    
    /**
     * Get all courses with jurusan info
     */
    public function getAll() {
        $sql = "SELECT mp.*, j.nama as jurusan_nama 
                FROM {$this->table} mp 
                LEFT JOIN jurusan j ON mp.jurusan_id = j.id 
                ORDER BY mp.nama ASC";
        return $this->query($sql)->fetchAll();
    }
    
    /**
     * Get course by ID
     */
    public function findById($id) {
        $sql = "SELECT mp.*, j.nama as jurusan_nama 
                FROM {$this->table} mp 
                LEFT JOIN jurusan j ON mp.jurusan_id = j.id 
                WHERE mp.id = :id";
        return $this->query($sql, ['id' => $id])->fetch();
    }
    
    /**
     * Get courses by jurusan
     */
    public function getByJurusan($jurusanId) {
        $sql = "SELECT * FROM {$this->table} WHERE jurusan_id = :jurusan_id OR jurusan_id IS NULL ORDER BY nama ASC";
        return $this->query($sql, ['jurusan_id' => $jurusanId])->fetchAll();
    }
    
    /**
     * Get courses by group (Normatif, Adaptif, Produktif)
     */
    public function getByKelompok($kelompok) {
        $sql = "SELECT * FROM {$this->table} WHERE kelompok = :kelompok ORDER BY nama ASC";
        return $this->query($sql, ['kelompok' => $kelompok])->fetchAll();
    }
    
    /**
     * Create new course
     */
    public function create($data) {
        $sql = "INSERT INTO {$this->table} (kode, nama, kelompok, kkm, jurusan_id) 
                VALUES (:kode, :nama, :kelompok, :kkm, :jurusan_id)";
        return $this->execute($sql, $data);
    }
    
    /**
     * Update course
     */
    public function update($id, $data) {
        $sql = "UPDATE {$this->table} 
                SET kode = :kode, nama = :nama, kelompok = :kelompok, kkm = :kkm, jurusan_id = :jurusan_id 
                WHERE id = :id";
        $data['id'] = $id;
        return $this->execute($sql, $data);
    }
    
    /**
     * Delete course
     */
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        return $this->execute($sql, ['id' => $id]);
    }
    
    /**
     * Get courses taught by teacher
     */
    public function getByTeacher($guruId, $tahunAjaranId = null) {
        if ($tahunAjaranId) {
            $sql = "SELECT mp.*, gm.kelas_id, k.nama as kelas_nama 
                    FROM {$this->table} mp 
                    JOIN guru_mapel gm ON mp.id = gm.mapel_id 
                    LEFT JOIN kelas k ON gm.kelas_id = k.id 
                    WHERE gm.guru_id = :guru_id AND gm.tahun_ajaran_id = :tahun_ajaran_id";
            return $this->query($sql, ['guru_id' => $guruId, 'tahun_ajaran_id' => $tahunAjaranId])->fetchAll();
        }
        
        $sql = "SELECT mp.*, gm.kelas_id, k.nama as kelas_nama 
                FROM {$this->table} mp 
                JOIN guru_mapel gm ON mp.id = gm.mapel_id 
                LEFT JOIN kelas k ON gm.kelas_id = k.id 
                WHERE gm.guru_id = :guru_id";
        return $this->query($sql, ['guru_id' => $guruId])->fetchAll();
    }
    
    /**
     * Get courses for student's class
     */
    public function getByKelas($kelasId, $tahunAjaranId = null) {
        if ($tahunAjaranId) {
            $sql = "SELECT DISTINCT mp.*, u.full_name as guru_nama 
                    FROM {$this->table} mp 
                    JOIN guru_mapel gm ON mp.id = gm.mapel_id 
                    JOIN users u ON gm.guru_id = u.id 
                    WHERE gm.kelas_id = :kelas_id AND gm.tahun_ajaran_id = :tahun_ajaran_id 
                    ORDER BY mp.nama ASC";
            return $this->query($sql, ['kelas_id' => $kelasId, 'tahun_ajaran_id' => $tahunAjaranId])->fetchAll();
        }
        
        $sql = "SELECT DISTINCT mp.*, u.full_name as guru_nama 
                FROM {$this->table} mp 
                JOIN guru_mapel gm ON mp.id = gm.mapel_id 
                JOIN users u ON gm.guru_id = u.id 
                WHERE gm.kelas_id = :kelas_id 
                ORDER BY mp.nama ASC";
        return $this->query($sql, ['kelas_id' => $kelasId])->fetchAll();
    }
    
    /**
     * Search courses
     */
    public function search($keyword) {
        $sql = "SELECT mp.*, j.nama as jurusan_nama 
                FROM {$this->table} mp 
                LEFT JOIN jurusan j ON mp.jurusan_id = j.id 
                WHERE mp.nama LIKE :keyword OR mp.kode LIKE :keyword 
                ORDER BY mp.nama ASC";
        return $this->query($sql, ['keyword' => "%{$keyword}%"])->fetchAll();
    }
}
