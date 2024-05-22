<?php
// Kiểm tra nếu nhân viên đã đăng nhập
if (!isset($_SESSION['ID_NhanVien'])) {
    header("Location: ../dangnhap_NV.php"); // Redirect đến trang đăng nhập nếu chưa đăng nhập
    exit();
}

// Kết nối cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'Congtyvienthong');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy ID_NhanVien từ session
$ID_NhanVien = $_SESSION['ID_NhanVien'];

// Truy vấn SQL để lấy tên nhân viên dựa trên ID_NhanVien
$sql_user = "SELECT TenNhanVien FROM NhanVien WHERE ID_NhanVien='$ID_NhanVien'";
$result_user = $conn->query($sql_user);

if ($result_user->num_rows == 1) {
    $row_user = $result_user->fetch_assoc();
    $tenNhanVien = $row_user['TenNhanVien'];
} else {
    // Xử lý khi không tìm thấy thông tin nhân viên
    $tenNhanVien = "Không tìm thấy thông tin nhân viên";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Menu</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Logo</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <!-- Dropdown Menu Danh Sach -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="../danhsach/danh_sach_thong_tin_khach_hang.php" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Danh Sách
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="../danhsach/danh_sach_thong_tin_khach_hang.php">Thông Tin Khách Hàng</a>
                        <a class="dropdown-item" href="../danhsach/danh_sach_thong_tin_dich_vu.php">Thông Tin Dịch Vụ</a>
                        <a class="dropdown-item" href="../danhsach/danh_sach_thong_tin_nhan_vien_ban_hang.php">Thông Tin Nhân
                            Viên</a>
                        <a class="dropdown-item" href="../danhsach/danh_sach_thong_tin_ban_hang.php">Thông Tin Bán Hàng</a>
                        <!-- Thêm các mục khác nếu cần -->
                    </div>
                </li>
                <!-- Dropdown Menu Top 10 -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="../top10/top10_nvbh.php" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Top 10
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="../top10/top10_nvbh.php">Nhân Viên Bán Hàng Nhiều</a>
                        <a class="dropdown-item" href="../top10/top10kh_dvMax.php">Khách Hàng Sử Dụng Nhiều Dịch Vụ</a>
                        <a class="dropdown-item" href="../top10/dich_vu_dang_ky_nhieu.php">Dịch Vụ Được Đăng Ký Nhiều</a>
                        <!-- Thêm các mục khác nếu cần -->
                    </div>
                </li>
                <!-- Other Menu Items -->
                <li class="nav-item">
                    <a class="nav-link" href="../danhsach/doanh_thu.php">Doanh Thu</a>
                </li>

            </ul>
        </div>
        <span class="navbar-text">
            Xin chào <?php echo $tenNhanVien; ?> | <a href="../dang_xuat_nv.php">Đăng Xuất</a>
        </span>
    </nav>
    <div class="container mt-3">