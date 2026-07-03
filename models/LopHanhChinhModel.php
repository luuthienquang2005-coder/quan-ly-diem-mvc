<?php
class LopHanhChinhModel extends KetNoiCSDL {
    // Lấy tất cả lớp kèm tên Khoa
    public function layTatCa() {
        $sql = "SELECT l.*, k.ten_khoa 
                FROM LopHanhChinh l 
                JOIN Khoa k ON l.ma_khoa = k.ma_khoa 
                ORDER BY l.ma_lop_hc DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Lấy thông tin 1 lớp để sửa
    public function layThongTin($ma_lop_hc) {
        $sql = "SELECT * FROM LopHanhChinh WHERE ma_lop_hc = :ma";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':ma' => $ma_lop_hc]);
        return $stmt->fetch();
    }

    public function themMoi($ma_lop, $ten_lop, $ma_khoa) {
        $sql = "INSERT INTO LopHanhChinh (ma_lop_hc, ten_lop_hc, ma_khoa) VALUES (:ma, :ten, :khoa)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':ma' => $ma_lop, ':ten' => $ten_lop, ':khoa' => $ma_khoa]);
    }

    public function capNhat($ma_lop, $ten_lop, $ma_khoa) {
        $sql = "UPDATE LopHanhChinh SET ten_lop_hc = :ten, ma_khoa = :khoa WHERE ma_lop_hc = :ma";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':ma' => $ma_lop, ':ten' => $ten_lop, ':khoa' => $ma_khoa]);
    }

    public function xoa($ma_lop) {
        $sql = "DELETE FROM LopHanhChinh WHERE ma_lop_hc = :ma";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':ma' => $ma_lop]);
    }
}
?>