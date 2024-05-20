<?php
session_start();

// Kiểm tra đăng nhập
if (!isset($_SESSION['ID_NhanVien'])) {
    header("Location: dang_nhap_nv.php");
    exit();
}

// Nếu không có ID gói dịch vụ được chuyển đến trang sửa, chuyển hướng người dùng
if (!isset($_GET['id'])) {
    header("Location: danh_sach_goi_dich_vu.php");
    exit();
}

// Kết nối CSDL
$conn = new mysqli('localhost', 'root', '', 'Congtyvienthong');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$id = $_GET['id'];

// Lấy thông tin gói dịch vụ từ CSDL
$sql = "SELECT * FROM GoiDichVu WHERE ID_GoiDichVu = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$goiDichVu = $result->fetch_assoc();

// Kiểm tra xem gói dịch vụ có tồn tại không
if (!$goiDichVu) {
    header("Location: danh_sach_goi_dich_vu.php");
    exit();
}

$message = "";

// Xử lý khi nhấn nút "Lưu"
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $TenGoiDichVu = $_POST['TenGoiDichVu'];
    $TocDo = $_POST['TocDo'];
    $GiaTien = $_POST['GiaTien'];
    $MoTa = $_POST['MoTa'];

    // Cập nhật thông tin gói dịch vụ vào CSDL
    $sqlUpdate = "UPDATE GoiDichVu SET TenGoiDichVu = ?, TocDo = ?, GiaTien = ?, MoTa = ? WHERE ID_GoiDichVu = ?";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->bind_param("siisi", $TenGoiDichVu, $TocDo, $GiaTien, $MoTa, $id);

    if ($stmtUpdate->execute()) {
        $message = "Cập nhật thông tin gói dịch vụ thành công.";
    } else {
        $message = "Lỗi: " . $conn->error;
    }
}

// Đóng kết nối
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Gói Dịch Vụ</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h2 class="mt-5">Sửa Gói Dịch Vụ</h2>
        <form action="sua_goi_dich_vu.php?id=<?php echo $id; ?>" method="post">
            <div class="form-group">
                <label for="TenGoiDichVu">Tên Gói Dịch Vụ</label>
                <input type="text" class="form-control" id="TenGoiDichVu" name="TenGoiDichVu" value="<?php echo $goiDichVu['TenGoiDichVu']; ?>" required>
            </div>
            <div class="form-group">
                <label for="TocDo">Tốc Độ</label>
                <input type="number" class="form-control" id="TocDo" name="TocDo" value="<?php echo $goiDichVu['TocDo']; ?>" required>
            </div>
            <div class="form-group">
                <label for="GiaTien">Giá Tiền</label>
                <input type="number" class="form-control" id="GiaTien" name="GiaTien" value="<?php echo $goiDichVu['GiaTien']; ?>" required>
            </div>
            <div class="form-group">
                <label for="MoTa">Mô Tả</label>
                <textarea class="form-control" id="MoTa" name="MoTa" rows="3"><?php echo $goiDichVu['MoTa']; ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Lưu</button>
            <a href="chi_tiet_dich_vu.php" class="btn btn-secondary">Quay Lại</a>
        </form>
        <?php if (!empty($message)) : ?>
            <div class="mt-3 alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>