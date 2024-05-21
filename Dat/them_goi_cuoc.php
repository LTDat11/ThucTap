<?php
session_start();

// Kiểm tra nếu nhân viên đã đăng nhập
if (!isset($_SESSION['ID_NhanVien'])) {
    header("Location: dang_nhap_nv.php");
    exit();
}

// Kiểm tra xem ID dịch vụ đã được truyền qua URL hay không
if (!isset($_GET['idDichVu'])) {
    header("Location: danh_sach_thong_tin_dich_vu.php");
    exit();
}

$idDichVu = $_GET['idDichVu'];
$message = "";

// Kết nối cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'Congtyvienthong');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Xử lý khi nhấn nút "Lưu"
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $TenGoiDichVu = $_POST['TenGoiDichVu'];
    $TocDo = $_POST['TocDo'];
    $GiaTien = $_POST['GiaTien'];
    $MoTa = $_POST['MoTa'];

    // Thêm thông tin gói dịch vụ vào CSDL
    $sqlInsert = "INSERT INTO GoiDichVu (ID_DichVu, TenGoiDichVu, TocDo, GiaTien, MoTa) VALUES (?, ?, ?, ?, ?)";
    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->bind_param("isiss", $idDichVu, $TenGoiDichVu, $TocDo, $GiaTien, $MoTa);

    if ($stmtInsert->execute()) {
        $message = "Thêm gói cước mới thành công.";
        header("refresh:1; url=chi_tiet_dich_vu.php?id=$idDichVu");
        exit();
    } else {
        $message = "Lỗi: " . $stmtInsert->error;
    }

    $stmtInsert->close();
}

// Đóng kết nối
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Gói Cước</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h2 class="mt-5">Thêm Gói Cước Mới</h2>
        <form action="them_goi_cuoc.php?idDichVu=<?php echo htmlspecialchars($idDichVu); ?>" method="post">
            <div class="form-group">
                <label for="TenGoiDichVu">Tên Gói Cước</label>
                <input type="text" class="form-control" id="TenGoiDichVu" name="TenGoiDichVu" required>
            </div>
            <div class="form-group">
                <label for="TocDo">Tốc Độ</label>
                <input type="text" class="form-control" id="TocDo" name="TocDo">
            </div>
            <div class="form-group">
                <label for="GiaTien">Giá Tiền</label>
                <input type="number" class="form-control" id="GiaTien" name="GiaTien" required>
            </div>
            <div class="form-group">
                <label for="MoTa">Mô Tả</label>
                <textarea class="form-control" id="MoTa" name="MoTa" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Lưu</button>
            <a href="chi_tiet_dich_vu.php?id=<?php echo htmlspecialchars($idDichVu); ?>" class="btn btn-secondary">Quay Lại</a>
        </form>
        <?php if (!empty($message)) : ?>
            <div class="mt-3 alert alert-success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
