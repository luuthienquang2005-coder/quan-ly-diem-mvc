<?php
require_once './models/MonHocModel.php';

class MonHocController extends BaseController {
    private $monHocModel;

    public function __construct() {
        if (!isset($_SESSION['admin_logged_in'])) {
            header('Location: index.php?controller=DangNhap');
            exit;
        }
        $this->monHocModel = new MonHocModel();
    }

    public function index() {
        $dsMonHoc = $this->monHocModel->layTatCa();
        $this->goiGiaoDien('mon_hoc/index', ['dsMonHoc' => $dsMonHoc]);
    }

    public function them() {
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $this->monHocModel->themMoi($_POST['ma_mon'], $_POST['ten_mon'], $_POST['so_tin_chi']);
                header('Location: index.php?controller=MonHoc');
                exit;
            } catch (Exception $e) {
                $error = "Lỗi: Mã môn học đã tồn tại!";
            }
        }
        $this->goiGiaoDien('mon_hoc/them', ['error' => $error]);
    }

    public function xoa() {
        if (isset($_GET['ma_mon'])) {
            $this->monHocModel->xoa($_GET['ma_mon']);
        }
        header('Location: index.php?controller=MonHoc');
    }
    public function sua() {
        if (!isset($_GET['ma_mon'])) {
            header('Location: index.php?controller=MonHoc');
            exit;
        }
        $ma_mon = $_GET['ma_mon'];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->monHocModel->capNhat($ma_mon, $_POST['ten_mon'], $_POST['so_tin_chi']);
            header('Location: index.php?controller=MonHoc');
            exit;
        }

        $monHoc = $this->monHocModel->layThongTin($ma_mon);
        $this->goiGiaoDien('mon_hoc/sua', ['monHoc' => $monHoc]);
    }
}
?>