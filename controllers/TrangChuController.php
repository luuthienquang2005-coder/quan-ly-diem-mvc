<?php
require_once './models/ThongKeModel.php';

class TrangChuController extends BaseController {
    private $thongKeModel;

    public function __construct() {
        if (!isset($_SESSION['admin_logged_in'])) {
            header('Location: index.php?controller=DangNhap');
            exit;
        }
        $this->thongKeModel = new ThongKeModel();
    }

    public function index() {
        $soLieu = [
            'sv' => $this->thongKeModel->demSinhVien(),
            'mon' => $this->thongKeModel->demMonHoc(),
            'lop' => $this->thongKeModel->demLopHocPhan(),
            'khoa' => $this->thongKeModel->demKhoa()
        ];

        $duLieu = [
            'tieu_de' => 'Dashboard Quản trị',
            'loi_chao' => 'Xin chào, ' . $_SESSION['admin_name'],
            'soLieu' => $soLieu
        ];
        $this->goiGiaoDien('trang_chu/index', $duLieu);
    }
}
?>