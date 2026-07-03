<?php
class ThongKeModel extends KetNoiCSDL {
    public function demSinhVien() {
        $stmt = $this->conn->query("SELECT COUNT(*) FROM SinhVien");
        return $stmt->fetchColumn();
    }
    public function demMonHoc() {
        $stmt = $this->conn->query("SELECT COUNT(*) FROM MonHoc");
        return $stmt->fetchColumn();
    }
    public function demLopHocPhan() {
        $stmt = $this->conn->query("SELECT COUNT(*) FROM LopHocPhan");
        return $stmt->fetchColumn();
    }
    public function demKhoa() {
        $stmt = $this->conn->query("SELECT COUNT(*) FROM Khoa");
        return $stmt->fetchColumn();
    }
}
?>