<?php
session_start();

// Kiểm tra nếu nhân viên đã đăng nhập
if (!isset($_SESSION['ID_NhanVien'])) {
    header("Location: dang_nhap.php"); // Redirect đến trang đăng nhập nếu chưa đăng nhập
    exit();
}

// Kết nối cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'Congtyvienthong');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy ID Khách Hàng từ URL
$ID_KhachHang = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Kiểm tra nếu form đã submit để cập nhật thông tin khách hàng
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ biểu mẫu
    $Ten = $conn->real_escape_string($_POST['Ten']);
    $SoDienThoai = $conn->real_escape_string($_POST['SoDienThoai']);
    $DiaChi = $conn->real_escape_string($_POST['DiaChi']);

    // Cập nhật thông tin khách hàng
    $sql_update = "UPDATE KhachHang SET Ten='$Ten', SoDienThoai='$SoDienThoai', DiaChi='$DiaChi' WHERE ID_KhachHang=$ID_KhachHang";

    if ($conn->query($sql_update) === TRUE) {
        echo "Cập nhật thông tin thành công.";
    } else {
        echo "Lỗi: " . $sql_update . "<br>" . $conn->error;
    }
}

// Truy vấn thông tin chi tiết của khách hàng
$sql_khachhang = "SELECT * FROM KhachHang WHERE ID_KhachHang = $ID_KhachHang";
$result_khachhang = $conn->query($sql_khachhang);
$khachhang = $result_khachhang->fetch_assoc();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Thông Tin Khách Hàng</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h2 class="mt-5">Sửa Thông Tin Khách Hàng</h2>
    <?php if ($khachhang): ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="Ten">Tên Khách Hàng</label>
                <input type="text" class="form-control" id="Ten" name="Ten" value="<?php echo htmlspecialchars($khachhang['Ten']); ?>" required>
            </div>
            <div class="form-group">
                <label for="SoDienThoai">Số Điện Thoại</label>
                <input type="text" class="form-control" id="SoDienThoai" name="SoDienThoai" value="<?php echo htmlspecialchars($khachhang['SoDienThoai']); ?>" required>
            </div>
            <div class="form-group">
                <label for="DiaChi">Địa Chỉ</label>
                <input type="text" class="form-control" id="DiaChi" name="DiaChi" value="<?php echo htmlspecialchars($khachhang['DiaChi']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Cập Nhật</button>
        </form>
    <?php else: ?>
        <p class="text-center">Không tìm thấy thông tin khách hàng.</p>
    <?php endif; ?>
    <a href="danh_sach_thong_tin_khach_hang.php" class="btn btn-secondary mt-3">Quay Lại Danh Sách Khách Hàng</a>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
