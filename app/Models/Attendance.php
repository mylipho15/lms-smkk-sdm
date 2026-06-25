<?php
/**
 * Attendance Model - app/Models/Attendance.php
 * 
 * Model untuk menangani operasi database terkait absensi siswa
 */

namespace App\Models;

use App\Core\Model;

class Attendance extends Model {
    protected $table = 'absensi';
    protected $primaryKey = 'id';
    
    /**
     * Get all attendance records
     */
    public function getAll() {
        $sql = "SELECT a.*, u.full_name as siswa_nama, s.nis, k.nama as kelas_nama, mp.nama as mapel_nama, i.full_name as inputer_nama 
                FROM {$this->table} a 
                JOIN siswa s ON a.siswa_id = s.id 
                JOIN users u ON s.user_id = u.id 
                JOIN kelas k ON a.kelas_id = k.id 
                LEFT JOIN mata_pelajaran mp ON a.mapel_id = mp.id 
                LEFT JOIN users i ON a.diinput_oleh = i.id 
                ORDER BY a.tanggal DESC, a.created_at DESC";
        return $this->query($sql)->fetchAll();
    }
    
    /**
     * Get attendance by ID
     */
    public function findById($id) {
        $sql = "SELECT a.*, u.full_name as siswa_nama, s.nis, s.nisn, k.nama as kelas_nama, mp.nama as mapel_nama 
                FROM {$this->table} a 
                JOIN siswa s ON a.siswa_id = s.id 
                JOIN users u ON s.user_id = u.id 
                JOIN kelas k ON a.kelas_id = k.id 
                LEFT JOIN mata_pelajaran mp ON a.mapel_id = mp.id 
                WHERE a.id = :id";
        return $this->query($sql, ['id' => $id])->fetch();
    }
    
    /**
     * Get attendance by class and date
     */
    public function getByKelasAndDate($kelasId, $tanggal) {
        $sql = "SELECT a.*, u.full_name as siswa_nama, s.nis 
                FROM {$this->table} a 
                JOIN siswa s ON a.siswa_id = s.id 
                JOIN users u ON s.user_id = u.id 
                WHERE a.kelas_id = :kelas_id AND a.tanggal = :tanggal 
                ORDER BY u.full_name ASC";
        return $this->query($sql, ['kelas_id' => $kelasId, 'tanggal' => $tanggal])->fetchAll();
    }
    
    /**
     * Get attendance by student
     */
    public function getBySiswa($siswaId, $limit = 30) {
        $sql = "SELECT a.*, k.nama as kelas_nama, mp.nama as mapel_nama 
                FROM {$this->table} a 
                JOIN kelas k ON a.kelas_id = k.id 
                LEFT JOIN mata_pelajaran mp ON a.mapel_id = mp.id 
                WHERE a.siswa_id = :siswa_id 
                ORDER BY a.tanggal DESC LIMIT :limit";
        return $this->query($sql, ['siswa_id' => $siswaId, 'limit' => $limit])->fetchAll();
    }
    
    /**
     * Get attendance by student and date range
     */
    public function getBySiswaAndDateRange($siswaId, $startDate, $endDate) {
        $sql = "SELECT a.*, k.nama as kelas_nama, mp.nama as mapel_nama 
                FROM {$this->table} a 
                JOIN kelas k ON a.kelas_id = k.id 
                LEFT JOIN mata_pelajaran mp ON a.mapel_id = mp.id 
                WHERE a.siswa_id = :siswa_id AND a.tanggal BETWEEN :start_date AND :end_date 
                ORDER BY a.tanggal ASC";
        return $this->query($sql, [
            'siswa_id' => $siswaId, 
            'start_date' => $startDate, 
            'end_date' => $endDate
        ])->fetchAll();
    }
    
    /**
     * Get monthly recap for class
     */
    public function getMonthlyRecap($kelasId, $bulan, $tahun) {
        $sql = "SELECT a.siswa_id, u.full_name as siswa_nama, s.nis,
                SUM(CASE WHEN a.status = 'Hadir' THEN 1 ELSE 0 END) as hadir,
                SUM(CASE WHEN a.status = 'Sakit' THEN 1 ELSE 0 END) as sakit,
                SUM(CASE WHEN a.status = 'Izin' THEN 1 ELSE 0 END) as izin,
                SUM(CASE WHEN a.status = 'Alpha' THEN 1 ELSE 0 END) as alpha,
                COUNT(*) as total
                FROM {$this->table} a 
                JOIN siswa s ON a.siswa_id = s.id 
                JOIN users u ON s.user_id = u.id 
                WHERE a.kelas_id = :kelas_id 
                AND YEAR(a.tanggal) = :tahun 
                AND MONTH(a.tanggal) = :bulan 
                GROUP BY a.siswa_id, u.full_name, s.nis 
                ORDER BY u.full_name ASC";
        return $this->query($sql, [
            'kelas_id' => $kelasId, 
            'tahun' => $tahun, 
            'bulan' => $bulan
        ])->fetchAll();
    }
    
    /**
     * Create attendance record
     */
    public function create($data) {
        $sql = "INSERT INTO {$this->table} 
                (siswa_id, kelas_id, mapel_id, tanggal, status, keterangan, bukti_file, diinput_oleh) 
                VALUES 
                (:siswa_id, :kelas_id, :mapel_id, :tanggal, :status, :keterangan, :bukti_file, :diinput_oleh)";
        return $this->execute($sql, $data);
    }
    
    /**
     * Update attendance
     */
    public function update($id, $data) {
        $sql = "UPDATE {$this->table} 
                SET status = :status, keterangan = :keterangan, bukti_file = :bukti_file 
                WHERE id = :id";
        $data['id'] = $id;
        return $this->execute($sql, $data);
    }
    
    /**
     * Bulk insert attendance
     */
    public function bulkInsert($records) {
        if (empty($records)) {
            return true;
        }
        
        $sql = "INSERT INTO {$this->table} 
                (siswa_id, kelas_id, mapel_id, tanggal, status, keterangan, diinput_oleh) 
                VALUES ";
        
        $values = [];
        foreach ($records as $index => $record) {
            $values[] = "(:siswa_id_$index, :kelas_id_$index, :mapel_id_$index, :tanggal_$index, :status_$index, :keterangan_$index, :diinput_oleh_$index)";
        }
        
        $sql .= implode(', ', $values);
        
        $params = [];
        foreach ($records as $index => $record) {
            $params["siswa_id_$index"] = $record['siswa_id'];
            $params["kelas_id_$index"] = $record['kelas_id'];
            $params["mapel_id_$index"] = $record['mapel_id'] ?? null;
            $params["tanggal_$index"] = $record['tanggal'];
            $params["status_$index"] = $record['status'];
            $params["keterangan_$index"] = $record['keterangan'] ?? null;
            $params["diinput_oleh_$index"] = $record['diinput_oleh'];
        }
        
        return $this->execute($sql, $params);
    }
    
    /**
     * Delete attendance
     */
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        return $this->execute($sql, ['id' => $id]);
    }
    
    /**
     * Check if attendance already exists for student on date
     */
    public function existsForStudentOnDate($siswaId, $tanggal) {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE siswa_id = :siswa_id AND tanggal = :tanggal";
        $result = $this->query($sql, ['siswa_id' => $siswaId, 'tanggal' => $tanggal])->fetch();
        return $result['count'] > 0;
    }
    
    /**
     * Get attendance statistics for class
     */
    public function getClassStatistics($kelasId, $startDate, $endDate) {
        $sql = "SELECT 
                SUM(CASE WHEN status = 'Hadir' THEN 1 ELSE 0 END) as hadir,
                SUM(CASE WHEN status = 'Sakit' THEN 1 ELSE 0 END) as sakit,
                SUM(CASE WHEN status = 'Izin' THEN 1 ELSE 0 END) as izin,
                SUM(CASE WHEN status = 'Alpha' THEN 1 ELSE 0 END) as alpha,
                COUNT(*) as total
                FROM {$this->table} 
                WHERE kelas_id = :kelas_id AND tanggal BETWEEN :start_date AND :end_date";
        return $this->query($sql, [
            'kelas_id' => $kelasId, 
            'start_date' => $startDate, 
            'end_date' => $endDate
        ])->fetch();
    }
    
    /**
     * Get students with low attendance
     */
    public function getLowAttendanceStudents($kelasId, $minPercentage = 75, $startDate, $endDate) {
        $sql = "SELECT a.siswa_id, u.full_name as siswa_nama, s.nis, k.nama as kelas_nama,
                COUNT(*) as total_hari,
                SUM(CASE WHEN a.status = 'Hadir' THEN 1 ELSE 0 END) as hadir_count,
                ROUND((SUM(CASE WHEN a.status = 'Hadir' THEN 1 ELSE 0 END) / COUNT(*)) * 100, 2) as persentase_kehadiran
                FROM {$this->table} a 
                JOIN siswa s ON a.siswa_id = s.id 
                JOIN users u ON s.user_id = u.id 
                JOIN kelas k ON s.kelas_id = k.id 
                WHERE a.kelas_id = :kelas_id AND a.tanggal BETWEEN :start_date AND :end_date 
                GROUP BY a.siswa_id, u.full_name, s.nis, k.nama 
                HAVING persentase_kehadiran < :min_percentage 
                ORDER BY persentase_kehadiran ASC";
        return $this->query($sql, [
            'kelas_id' => $kelasId, 
            'start_date' => $startDate, 
            'end_date' => $endDate,
            'min_percentage' => $minPercentage
        ])->fetchAll();
    }
}
