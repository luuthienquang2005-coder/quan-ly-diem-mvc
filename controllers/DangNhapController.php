<?php
require_once './models/QuanTriVienModel.php';

class DangNhapController extends BaseController {
    private $userModel;

    public function __construct() {
        $this->userModel = new QuanTriVienModel();
    }

    // Hiển thị form đăng nhập
    public function index() {
        // Nếu đã đăng nhập rồi thì chuyển thẳng sang trang chủ
        if (isset($_SESSION['admin_logged_in'])) {
            header('Location: index.php?controller=TrangChu');
            exit;
        }
        $this->goiGiaoDien('dang_nhap/index');
    }

    // Xử lý khi bấm nút Đăng nhập
    public function dangNhap() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user = $_POST['username'];
            $pass = $_POST['password'];

            // 1. Lấy thông tin từ CSDL
            $admin = $this->userModel->layThongTinAdmin($user);

            // 2. Kiểm tra mật khẩu
            // Lưu ý: Ở đây mình so sánh trực tiếp '==' cho đơn giản theo yêu cầu của bạn.
            // Nếu làm thực tế bảo mật thì phải dùng: password_verify($pass, $admin['mat_khau'])
            if ($admin && $admin['mat_khau'] == $pass) {
                // Đăng nhập thành công
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_name'] = $admin['ho_ten'];
                $_SESSION['admin_id'] = $admin['ma_admin'];

                header('Location: index.php?controller=TrangChu');
            } else {
                // Đăng nhập thất bại
                $data = ['error' => 'Sai tên đăng nhập hoặc mật khẩu!'];
                $this->goiGiaoDien('dang_nhap/index', $data);
            }
        }
    }

    // Đăng xuất
    public function dangXuat() {
        session_destroy();
        header('Location: index.php?controller=DangNhap');
    }
}
?>