<?php
// Kiểm tra nếu nhân viên đã đăng nhập
if (!isset($_SESSION['ID_KhachHang'])) {
    header("Location: dangnhap.php"); // Redirect đến trang đăng nhập nếu chưa đăng nhập
    exit();
}

// Kết nối cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'Congtyvienthong');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy ID_KhachHang từ session
$ID_KhachHang = $_SESSION['ID_KhachHang'];

// Truy vấn SQL để lấy tên nhân viên dựa trên ID_NhanVien
$sql_user = "SELECT Ten FROM KhachHang WHERE ID_KhachHang='$ID_KhachHang'";
$result_user = $conn->query($sql_user);

if ($result_user->num_rows == 1) {
    $row_user = $result_user->fetch_assoc();
    $tenKhachHang = $row_user['Ten'];
} else {
    // Xử lý khi không tìm thấy thông tin nhân viên
    $tenKhachHang = "Không tìm thấy thông tin nhân viên";
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
            
        </div>
        <span class="navbar-text">
            Xin chào <?php echo $tenKhachHang; ?> | <a href="dang_xuat_nv.php">Đăng Xuất</a>
        </span>
    </nav>
    <div class="container mt-3">