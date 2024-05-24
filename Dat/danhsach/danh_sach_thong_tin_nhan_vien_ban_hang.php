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

// Truy vấn thông tin nhân viên bán hàng
$sql = "SELECT * FROM TTNhanVienBanHang";
if (isset($_GET['search_query']) && !empty($_GET['search_query'])) {
    $search_query = $_GET['search_query'];
    // Thêm điều kiện tìm kiếm vào truy vấn SQL
    $sql .= " WHERE TenNhanVien LIKE '%$search_query%' OR SoDienThoai LIKE '%$search_query%' OR DiaChi LIKE '%$search_query%'";
}
$sql .= " ORDER BY TenNhanVien ASC";
$result = $conn->query($sql);

$conn->close();
?>

<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Thông Tin Nhân Viên Bán Hàng</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container"> -->
<?php include '../menu.php'; ?>
<div class="container">
    <h2 class="mt-3">Danh Sách Thông Tin Nhân Viên Bán Hàng</h2>
    <form action="" method="GET" class="mb-3">
        <div class="form-row">
            <div class="col">
                <input type="text" class="form-control" placeholder="Tìm kiếm..." name="search_query">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Tìm Kiếm</button>
            </div>
        </div>
    </form>
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
                        <a href='../chitiet/chi_tiet_nvbh2.php?id=" . $row['ID_TTNVBH'] . "' class='btn btn-info'>Xem Chi Tiết</a>
                        <a href='../sua/sua_thong_tin_nhan_vien.php?id=" . $row['ID_TTNVBH'] . "' class='btn btn-warning'>Sửa</a>
                        <a href='#' onclick='confirmDelete_ttnv(" . $row['ID_TTNVBH'] . ")' class='btn btn-danger'>Xóa</a>
                      </td>";

                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4' class='text-center'>Không có dữ liệu</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <a href="../them/them_nhan_vien_ban_hang.php" class="btn btn-primary mb-3">Thêm Nhân Viên Bán Hàng Mới</a>
</div>
<?php include '../footer.php'; ?>
<!-- </div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html> -->