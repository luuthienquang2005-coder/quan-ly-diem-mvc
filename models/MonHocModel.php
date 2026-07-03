<?php
class MonHocModel extends KetNoiCSDL {
    public function layTatCa() {
        $sql = "SELECT * FROM MonHoc";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function themMoi($ma_mon, $ten_mon, $so_tin_chi) {
        $sql = "INSERT INTO MonHoc (ma_mon, ten_mon, so_tin_chi) VALUES (:ma, :ten, :stc)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':ma' => $ma_mon,
            ':ten' => $ten_mon,
            ':stc' => $so_tin_chi
        ]);
    }
    
    public function xoa($ma_mon) {
        $sql = "DELETE FROM MonHoc WHERE ma_mon = :ma";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':ma' => $ma_mon]);
    }
    // Lấy 1 môn
    public function layThongTin($ma_mon) {
        $sql = "SELECT * FROM MonHoc WHERE ma_mon = :ma";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':ma' => $ma_mon]);
        return $stmt->fetch();
    }

    // Cập nhật môn
    public function capNhat($ma_mon, $ten_mon, $so_tin_chi) {
        $sql = "UPDATE MonHoc SET ten_mon = :ten, so_tin_chi = :stc WHERE ma_mon = :ma";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':ma' => $ma_mon, ':ten' => $ten_mon, ':stc' => $so_tin_chi]);
    }
}
?>