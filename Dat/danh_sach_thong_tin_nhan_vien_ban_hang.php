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

// Truy vấn thông tin nhân viên bán hàng
$sql = "SELECT * FROM TTNhanVienBanHang";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Thông Tin Nhân Viên Bán Hàng</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h2 class="mt-5">Danh Sách Thông Tin Nhân Viên Bán Hàng</h2>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Tên Nhân Viên</th>
            <th>Số Điện Thoại</th>
            <th>Địa Chỉ</th>
            <th>Lựa Chọn</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['TenNhanVien']) . "</td>";
                echo "<td>" . htmlspecialchars($row['SoDienThoai']) . "</td>";
                echo "<td>" . htmlspecialchars($row['DiaChi']) . "</td>";
                echo "<td>
                        <a href='sua_thong_tin_nhan_vien.php?id=" . $row['ID_TTNVBH'] . "' class='btn btn-warning'>Sửa</a>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4' class='text-center'>Không có dữ liệu</td></tr>";
        }
        ?>
        </tbody>
    </table>
    <a href="them_nhan_vien_ban_hang.php" class="btn btn-primary mt-3">Thêm Nhân Viên Bán Hàng Mới</a>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
