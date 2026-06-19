<?php
/**
 * PklLog Model - app/Models/PklLog.php
 * 
 * Model khusus SMK untuk menangani logbook PKL/Prakerin
 */

namespace App\Models;

use App\Core\Model;

class PklLog extends Model {
    protected $table = 'pkl_logbook';
    protected $primaryKey = 'id';
    
    /**
     * Get all PKL logbooks with relations
     */
    public function getAll() {
        $sql = "SELECT pl.*, u.full_name as siswa_nama, s.nis, ip.nama as industri_nama,
                v.full_name as verifikator_nama
                FROM {$this->table} pl 
                JOIN siswa s ON pl.siswa_id = s.id 
                JOIN users u ON s.user_id = u.id 
                LEFT JOIN industri_pasangan ip ON pl.industri_id = ip.id 
                LEFT JOIN users v ON pl.verifikator_id = v.id 
                ORDER BY pl.tanggal DESC";
        return $this->query($sql)->fetchAll();
    }
    
    /**
     * Get logbook by ID
     */
    public function findById($id) {
        $sql = "SELECT pl.*, u.full_name as siswa_nama, s.nis, s.nisn, k.nama as kelas_nama,
                ip.nama as industri_nama, ip.alamat as industri_alamat, ip.telepon as industri_telepon,
                v.full_name as verifikator_nama
                FROM {$this->table} pl 
                JOIN siswa s ON pl.siswa_id = s.id 
                JOIN users u ON s.user_id = u.id 
                JOIN kelas k ON s.kelas_id = k.id 
                LEFT JOIN industri_pasangan ip ON pl.industri_id = ip.id 
                LEFT JOIN users v ON pl.verifikator_id = v.id 
                WHERE pl.id = :id";
        return $this->query($sql, ['id' => $id])->fetch();
    }
    
    /**
     * Get logbooks by student
     */
    public function getBySiswa($siswaId) {
        $sql = "SELECT pl.*, ip.nama as industri_nama
                FROM {$this->table} pl 
                LEFT JOIN industri_pasangan ip ON pl.industri_id = ip.id 
                WHERE pl.siswa_id = :siswa_id 
                ORDER BY pl.tanggal DESC";
        return $this->query($sql, ['siswa_id' => $siswaId])->fetchAll();
    }
    
    /**
     * Get logbooks by date range
     */
    public function getBySiswaAndDateRange($siswaId, $startDate, $endDate) {
        $sql = "SELECT pl.*, ip.nama as industri_nama
                FROM {$this->table} pl 
                LEFT JOIN industri_pasangan ip ON pl.industri_id = ip.id 
                WHERE pl.siswa_id = :siswa_id AND pl.tanggal BETWEEN :start_date AND :end_date 
                ORDER BY pl.tanggal ASC";
        return $this->query($sql, [
            'siswa_id' => $siswaId, 
            'start_date' => $startDate, 
            'end_date' => $endDate
        ])->fetchAll();
    }
    
    /**
     * Get logbooks pending verification
     */
    public function getPendingVerification() {
        $sql = "SELECT pl.*, u.full_name as siswa_nama, s.nis, ip.nama as industri_nama
                FROM {$this->table} pl 
                JOIN siswa s ON pl.siswa_id = s.id 
                JOIN users u ON s.user_id = u.id 
                LEFT JOIN industri_pasangan ip ON pl.industri_id = ip.id 
                WHERE pl.status_verifikasi = 'Pending' 
                ORDER BY pl.tanggal DESC";
        return $this->query($sql)->fetchAll();
    }
    
    /**
     * Create new logbook entry
     */
    public function create($data) {
        $sql = "INSERT INTO {$this->table} 
                (siswa_id, industri_id, tanggal, kegiatan, jam_mulai, jam_selesai, catatan, foto_kegiatan, status_verifikasi) 
                VALUES 
                (:siswa_id, :industri_id, :tanggal, :kegiatan, :jam_mulai, :jam_selesai, :catatan, :foto_kegiatan, :status_verifikasi)";
        return $this->execute($sql, $data);
    }
    
    /**
     * Update logbook entry
     */
    public function update($id, $data) {
        $sql = "UPDATE {$this->table} 
                SET kegiatan = :kegiatan, jam_mulai = :jam_mulai, jam_selesai = :jam_selesai, 
                    catatan = :catatan, foto_kegiatan = :foto_kegiatan 
                WHERE id = :id";
        $data['id'] = $id;
        return $this->execute($sql, $data);
    }
    
    /**
     * Verify logbook by mentor
     */
    public function verifyByMentor($id, $status, $feedback = null, $verifikatorId = null) {
        $sql = "UPDATE {$this->table} 
                SET status_verifikasi = :status, feedback_verifikator = :feedback, 
                    verifikator_id = :verifikator_id, tanggal_verifikasi = NOW()
                WHERE id = :id";
        return $this->execute($sql, [
            'id' => $id, 
            'status' => $status, 
            'feedback' => $feedback,
            'verifikator_id' => $verifikatorId
        ]);
    }
    
    /**
     * Delete logbook
     */
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        return $this->execute($sql, ['id' => $id]);
    }
    
    /**
     * Count logbooks by student
     */
    public function countBySiswa($siswaId) {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE siswa_id = :siswa_id";
        $result = $this->query($sql, ['siswa_id' => $siswaId])->fetch();
        return $result['count'];
    }
    
    /**
     * Count by status
     */
    public function countByStatus($status) {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE status_verifikasi = :status";
        $result = $this->query($sql, ['status' => $status])->fetch();
        return $result['count'];
    }
    
    /**
     * Search logbooks
     */
    public function search($keyword, $siswaId = null) {
        if ($siswaId) {
            $sql = "SELECT pl.*, u.full_name as siswa_nama, ip.nama as industri_nama
                    FROM {$this->table} pl 
                    JOIN siswa s ON pl.siswa_id = s.id 
                    JOIN users u ON s.user_id = u.id 
                    LEFT JOIN industri_pasangan ip ON pl.industri_id = ip.id 
                    WHERE (pl.kegiatan LIKE :keyword OR pl.catatan LIKE :keyword) 
                    AND pl.siswa_id = :siswa_id 
                    ORDER BY pl.tanggal DESC";
            return $this->query($sql, ['keyword' => "%{$keyword}%", 'siswa_id' => $siswaId])->fetchAll();
        }
        
        $sql = "SELECT pl.*, u.full_name as siswa_nama, ip.nama as industri_nama
                FROM {$this->table} pl 
                JOIN siswa s ON pl.siswa_id = s.id 
                JOIN users u ON s.user_id = u.id 
                LEFT JOIN industri_pasangan ip ON pl.industri_id = ip.id 
                WHERE (pl.kegiatan LIKE :keyword OR pl.catatan LIKE :keyword) 
                ORDER BY pl.tanggal DESC";
        return $this->query($sql, ['keyword' => "%{$keyword}%"])->fetchAll();
    }
}
