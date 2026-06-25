<?php
/**
 * Assignment Model - app/Models/Assignment.php
 * 
 * Model untuk menangani operasi database terkait tugas dan jawaban siswa
 */

namespace App\Models;

use App\Core\Model;

class Assignment extends Model {
    protected $table = 'tugas';
    protected $primaryKey = 'id';
    
    /**
     * Get all assignments with relations
     */
    public function getAll() {
        $sql = "SELECT t.*, u.full_name as guru_nama, mp.nama as mapel_nama, k.nama as kelas_nama 
                FROM {$this->table} t 
                JOIN users u ON t.guru_id = u.id 
                JOIN mata_pelajaran mp ON t.mapel_id = mp.id 
                JOIN kelas k ON t.kelas_id = k.id 
                ORDER BY t.tgl_deadline ASC";
        return $this->query($sql)->fetchAll();
    }
    
    /**
     * Get assignment by ID
     */
    public function findById($id) {
        $sql = "SELECT t.*, u.full_name as guru_nama, mp.nama as mapel_nama, k.nama as kelas_nama 
                FROM {$this->table} t 
                JOIN users u ON t.guru_id = u.id 
                JOIN mata_pelajaran mp ON t.mapel_id = mp.id 
                JOIN kelas k ON t.kelas_id = k.id 
                WHERE t.id = :id";
        return $this->query($sql, ['id' => $id])->fetch();
    }
    
    /**
     * Get assignments by teacher
     */
    public function getByTeacher($guruId) {
        $sql = "SELECT t.*, mp.nama as mapel_nama, k.nama as kelas_nama 
                FROM {$this->table} t 
                JOIN mata_pelajaran mp ON t.mapel_id = mp.id 
                JOIN kelas k ON t.kelas_id = k.id 
                WHERE t.guru_id = :guru_id 
                ORDER BY t.created_at DESC";
        return $this->query($sql, ['guru_id' => $guruId])->fetchAll();
    }
    
    /**
     * Get assignments by class for student
     */
    public function getByKelas($kelasId) {
        $sql = "SELECT t.*, u.full_name as guru_nama, mp.nama as mapel_nama,
                (SELECT COUNT(*) FROM jawaban_tugas jt WHERE jt.tugas_id = t.id) as jumlah_jawaban
                FROM {$this->table} t 
                JOIN users u ON t.guru_id = u.id 
                JOIN mata_pelajaran mp ON t.mapel_id = mp.id 
                WHERE t.kelas_id = :kelas_id AND t.is_published = 1 
                ORDER BY t.tgl_deadline ASC";
        return $this->query($sql, ['kelas_id' => $kelasId])->fetchAll();
    }
    
    /**
     * Get assignment by course
     */
    public function getByCourse($mapelId) {
        $sql = "SELECT t.*, u.full_name as guru_nama, k.nama as kelas_nama 
                FROM {$this->table} t 
                JOIN users u ON t.guru_id = u.id 
                JOIN kelas k ON t.kelas_id = k.id 
                WHERE t.mapel_id = :mapel_id AND t.is_published = 1 
                ORDER BY t.tgl_deadline DESC";
        return $this->query($sql, ['mapel_id' => $mapelId])->fetchAll();
    }
    
    /**
     * Create new assignment
     */
    public function create($data) {
        $sql = "INSERT INTO {$this->table} 
                (guru_id, mapel_id, kelas_id, judul, deskripsi, instruksi, file_path, tgl_diberikan, tgl_deadline, poin_maksimal, tipe, is_published) 
                VALUES 
                (:guru_id, :mapel_id, :kelas_id, :judul, :deskripsi, :instruksi, :file_path, :tgl_diberikan, :tgl_deadline, :poin_maksimal, :tipe, :is_published)";
        return $this->execute($sql, $data);
    }
    
    /**
     * Update assignment
     */
    public function update($id, $data) {
        $sql = "UPDATE {$this->table} 
                SET judul = :judul, deskripsi = :deskripsi, instruksi = :instruksi, file_path = :file_path, 
                    tgl_deadline = :tgl_deadline, poin_maksimal = :poin_maksimal, tipe = :tipe, is_published = :is_published 
                WHERE id = :id";
        $data['id'] = $id;
        return $this->execute($sql, $data);
    }
    
    /**
     * Delete assignment
     */
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        return $this->execute($sql, ['id' => $id]);
    }
    
    /**
     * Get pending assignments (not yet submitted by student)
     */
    public function getPendingForStudent($siswaId, $kelasId) {
        $sql = "SELECT t.*, u.full_name as guru_nama, mp.nama as mapel_nama,
                jt.status as submission_status, jt.nilai
                FROM {$this->table} t 
                JOIN users u ON t.guru_id = u.id 
                JOIN mata_pelajaran mp ON t.mapel_id = mp.id 
                LEFT JOIN jawaban_tugas jt ON t.id = jt.tugas_id AND jt.siswa_id = :siswa_id 
                WHERE t.kelas_id = :kelas_id AND t.is_published = 1 
                ORDER BY t.tgl_deadline ASC";
        return $this->query($sql, ['siswa_id' => $siswaId, 'kelas_id' => $kelasId])->fetchAll();
    }
    
    /**
     * Get overdue assignments
     */
    public function getOverdue($kelasId) {
        $sql = "SELECT t.*, u.full_name as guru_nama, mp.nama as mapel_nama 
                FROM {$this->table} t 
                JOIN users u ON t.guru_id = u.id 
                JOIN mata_pelajaran mp ON t.mapel_id = mp.id 
                WHERE t.kelas_id = :kelas_id AND t.tgl_deadline < NOW() AND t.is_published = 1 
                ORDER BY t.tgl_deadline DESC";
        return $this->query($sql, ['kelas_id' => $kelasId])->fetchAll();
    }
    
    /**
     * Search assignments
     */
    public function search($keyword, $kelasId = null) {
        if ($kelasId) {
            $sql = "SELECT t.*, u.full_name as guru_nama, mp.nama as mapel_nama, k.nama as kelas_nama 
                    FROM {$this->table} t 
                    JOIN users u ON t.guru_id = u.id 
                    JOIN mata_pelajaran mp ON t.mapel_id = mp.id 
                    JOIN kelas k ON t.kelas_id = k.id 
                    WHERE (t.judul LIKE :keyword OR t.deskripsi LIKE :keyword) 
                    AND t.kelas_id = :kelas_id AND t.is_published = 1 
                    ORDER BY t.created_at DESC";
            return $this->query($sql, ['keyword' => "%{$keyword}%", 'kelas_id' => $kelasId])->fetchAll();
        }
        
        $sql = "SELECT t.*, u.full_name as guru_nama, mp.nama as mapel_nama, k.nama as kelas_nama 
                FROM {$this->table} t 
                JOIN users u ON t.guru_id = u.id 
                JOIN mata_pelajaran mp ON t.mapel_id = mp.id 
                JOIN kelas k ON t.kelas_id = k.id 
                WHERE (t.judul LIKE :keyword OR t.deskripsi LIKE :keyword) AND t.is_published = 1 
                ORDER BY t.created_at DESC";
        return $this->query($sql, ['keyword' => "%{$keyword}%"])->fetchAll();
    }
}

// Model for jawaban_tugas table
class AssignmentSubmission extends Model {
    protected $table = 'jawaban_tugas';
    protected $primaryKey = 'id';
    
    /**
     * Get submission by task and student
     */
    public function findByTaskAndStudent($tugasId, $siswaId) {
        $sql = "SELECT jt.*, t.judul as tugas_judul, s.nis 
                FROM {$this->table} jt 
                JOIN tugas t ON jt.tugas_id = t.id 
                JOIN siswa s ON jt.siswa_id = s.id 
                WHERE jt.tugas_id = :tugas_id AND jt.siswa_id = :siswa_id";
        return $this->query($sql, ['tugas_id' => $tugasId, 'siswa_id' => $siswaId])->fetch();
    }
    
    /**
     * Get all submissions for a task
     */
    public function getByTugas($tugasId) {
        $sql = "SELECT jt.*, u.full_name as siswa_nama, s.nis, s.nisn 
                FROM {$this->table} jt 
                JOIN siswa s ON jt.siswa_id = s.id 
                JOIN users u ON s.user_id = u.id 
                WHERE jt.tugas_id = :tugas_id 
                ORDER BY jt.tgl_pengumpulan DESC";
        return $this->query($sql, ['tugas_id' => $tugasId])->fetchAll();
    }
    
    /**
     * Get submissions by student
     */
    public function getBySiswa($siswaId) {
        $sql = "SELECT jt.*, t.judul as tugas_judul, mp.nama as mapel_nama, u.full_name as guru_nama 
                FROM {$this->table} jt 
                JOIN tugas t ON jt.tugas_id = t.id 
                JOIN mata_pelajaran mp ON t.mapel_id = mp.id 
                JOIN users u ON t.guru_id = u.id 
                WHERE jt.siswa_id = :siswa_id 
                ORDER BY jt.tgl_pengumpulan DESC";
        return $this->query($sql, ['siswa_id' => $siswaId])->fetchAll();
    }
    
    /**
     * Create submission
     */
    public function create($data) {
        $sql = "INSERT INTO {$this->table} 
                (tugas_id, siswa_id, jawaban, file_path, tgl_pengumpulan, terlambat, status) 
                VALUES 
                (:tugas_id, :siswa_id, :jawaban, :file_path, :tgl_pengumpulan, :terlambat, :status)";
        return $this->execute($sql, $data);
    }
    
    /**
     * Update submission (for grading)
     */
    public function grade($id, $nilai, $feedback) {
        $sql = "UPDATE {$this->table} 
                SET nilai = :nilai, feedback = :feedback, status = 'Dinilai' 
                WHERE id = :id";
        return $this->execute($sql, ['id' => $id, 'nilai' => $nilai, 'feedback' => $feedback]);
    }
    
    /**
     * Count submissions by status
     */
    public function countByStatus($tugasId, $status) {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE tugas_id = :tugas_id AND status = :status";
        $result = $this->query($sql, ['tugas_id' => $tugasId, 'status' => $status])->fetch();
        return $result['count'];
    }
}
