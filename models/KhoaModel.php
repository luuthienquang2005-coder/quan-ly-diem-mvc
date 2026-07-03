<?php
class KhoaModel extends KetNoiCSDL {
    public function layTatCa() {
        $sql = "SELECT * FROM Khoa ORDER BY ten_khoa ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // --- CÁC HÀM MỚI THÊM ---

    // 1. Thêm khoa mới
    public function themMoi($ma_khoa, $ten_khoa) {
        $sql = "INSERT INTO Khoa (ma_khoa, ten_khoa) VALUES (:ma, :ten)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':ma' => $ma_khoa, ':ten' => $ten_khoa]);
    }

    // 2. Kiểm tra mã khoa đã tồn tại chưa
    public function kiemTraTonTai($ma_khoa) {
        $sql = "SELECT COUNT(*) FROM Khoa WHERE ma_khoa = :ma";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':ma' => $ma_khoa]);
        return $stmt->fetchColumn() > 0;
    }
}
?>