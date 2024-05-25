<?php
session_start();

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

// Lấy ID Nhân Viên Bán Hàng từ URL
$ID_TTNVBH = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Kiểm tra nếu form đã submit để cập nhật thông tin nhân viên bán hàng
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ biểu mẫu
    $TenNhanVien = $conn->real_escape_string($_POST['TenNhanVien']);
    $SoDienThoai = $conn->real_escape_string($_POST['SoDienThoai']);
    $DiaChi = $conn->real_escape_string($_POST['DiaChi']);

    // Kiểm tra nếu số điện thoại đã tồn tại cho nhân viên khác
    $sql_check = "SELECT * FROM TTNhanVienBanHang WHERE SoDienThoai='$SoDienThoai' AND ID_TTNVBH != $ID_TTNVBH";
    $result_check = $conn->query($sql_check);

    if ($result_check->num_rows > 0) {
        echo "<script>alert('Số điện thoại đã tồn tại.');</script>";
    } else {
        // Cập nhật thông tin nhân viên bán hàng
        $sql_update = "UPDATE TTNhanVienBanHang SET TenNhanVien='$TenNhanVien', SoDienThoai='$SoDienThoai', DiaChi='$DiaChi' WHERE ID_TTNVBH=$ID_TTNVBH";

        if ($conn->query($sql_update) === TRUE) {
            echo "<script>alert('Cập nhật thành công.');</script>";
            header("refresh:0.5; url=../danhsach/danh_sach_thong_tin_nhan_vien_ban_hang.php");
            exit();
        } else {
            echo "Lỗi: " . $sql_update . "<br>" . $conn->error;
        }
    }
}

// Truy vấn thông tin chi tiết của nhân viên bán hàng
$sql_nhanvien = "SELECT * FROM TTNhanVienBanHang WHERE ID_TTNVBH = $ID_TTNVBH";
$result_nhanvien = $conn->query($sql_nhanvien);
$nhanvien = $result_nhanvien->fetch_assoc();

$conn->close();
?>

<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Thông Tin Nhân Viên Bán Hàng</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body> -->
<?php include '../menu.php'; ?>
<div class="container">
    <h2 class="mt-5">Sửa Thông Tin Nhân Viên Bán Hàng</h2>
    <?php if ($nhanvien) : ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="TenNhanVien">Tên Nhân Viên</label>
                <input type="text" class="form-control" id="TenNhanVien" name="TenNhanVien" value="<?php echo htmlspecialchars($nhanvien['TenNhanVien']); ?>" required>
            </div>
            <div class="form-group">
                <label for="SoDienThoai">Số Điện Thoại</label>
                <input type="text" class="form-control" id="SoDienThoai" name="SoDienThoai" value="<?php echo htmlspecialchars($nhanvien['SoDienThoai']); ?>" required>
            </div>
            <div class="form-group">
                <label for="DiaChi">Địa Chỉ</label>
                <input type="text" class="form-control" id="DiaChi" name="DiaChi" value="<?php echo htmlspecialchars($nhanvien['DiaChi']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary bi bi-floppy mr-2"> Lưu</button>
            <a href="../danhsach/danh_sach_thong_tin_nhan_vien_ban_hang.php" class="btn btn-secondary bi bi-backspace"> Quay Lại</a>
        </form>
    <?php else : ?>
        <p class="text-center">Không tìm thấy thông tin nhân viên bán hàng.</p>
    <?php endif; ?>

</div>
<?php include '../footer.php'; ?>
<!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html> -->