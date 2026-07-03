<?php
class HocKyModel extends KetNoiCSDL {
    public function layTatCa() {
        $sql = "SELECT * FROM HocKy ORDER BY nam_hoc DESC, ten_hk ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // --- CẬP NHẬT HÀM NÀY ---
    public function themMoi($ten_hk, $nam_hoc) {
        $sql = "INSERT INTO HocKy (ten_hk, nam_hoc, trang_thai) VALUES (:ten, :nam, 1)"; // Mặc định status=1 (Mở)
        $stmt = $this->conn->prepare($sql);
        if ($stmt->execute([':ten' => $ten_hk, ':nam' => $nam_hoc])) {
            // Trả về ID vừa tạo
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function kiemTraTonTai($ten_hk, $nam_hoc) {
        // Trả về ID nếu tồn tại, false nếu chưa
        $sql = "SELECT ma_hk FROM HocKy WHERE ten_hk = :ten AND nam_hoc = :nam";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':ten' => $ten_hk, ':nam' => $nam_hoc]);
        return $stmt->fetchColumn();
    }
}
?>