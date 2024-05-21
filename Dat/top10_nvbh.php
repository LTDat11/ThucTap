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

// Truy vấn thông tin nhân viên
$sql = "SELECT 
ttbh.ID_TTNVBH,
ttbh.TenNhanVien,
ttbh.SoDienThoai,
ttbh.DiaChi,
COUNT(ttb.ID_TTNVBH) AS TongSoLuongDichVuBanDuoc
FROM 
ThongTinBanHang AS ttb
JOIN 
TTNhanVienBanHang AS ttbh ON ttb.ID_TTNVBH = ttbh.ID_TTNVBH
GROUP BY 
ttbh.ID_TTNVBH, ttbh.TenNhanVien, ttbh.SoDienThoai, ttbh.DiaChi
ORDER BY 
TongSoLuongDichVuBanDuoc DESC
LIMIT 10";
$result = $conn->query($sql);

$conn->close();
?>

<!-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách TOP 10 Nhân Viên Bán Hàng Nhiều</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="container"> -->
<?php include 'menu.php'; ?>
<h2 class="mt-3">Top 10 Nhân Viên</h2>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Tên Nhân viên bán hàng</th>
            <th>Số Điện Thoại</th>
            <th>Địa Chỉ</th>
            <th>Tổng số dịch vụ bán được</th>
            <th>Lựa Chọn</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            $tenNhanVien = [];
            $soLuongDichVu = [];
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['TenNhanVien']) . "</td>";
                echo "<td>" . htmlspecialchars($row['SoDienThoai']) . "</td>";
                echo "<td>" . htmlspecialchars($row['DiaChi']) . "</td>";
                echo "<td>" . htmlspecialchars($row['TongSoLuongDichVuBanDuoc']) . "</td>";
                echo "<td><a href='chi_tiet_nvbh.php?id=" . $row['ID_TTNVBH'] . "' class='btn btn-info'>Xem Chi Tiết</a></td>";
                echo "</tr>";

                // Thêm dữ liệu vào mảng
                $tenNhanVien[] = $row['TenNhanVien'];
                $soLuongDichVu[] = $row['TongSoLuongDichVuBanDuoc'];
            }
        } else {
            echo "<tr><td colspan='5' class='text-center'>Không có dữ liệu</td></tr>";
        }
        ?>
    </tbody>
</table>
<a href="xuat_excel_top10_nvbh_nhieu.php" class="btn btn-success">Xuất Excel</a>
<!-- <a href="them_thong_tin_ban_hang.php" class="btn btn-primary">Thêm Thông Tin Bán Hàng Mới</a> -->

<!-- Biểu đồ -->
<div class="mt-5">
    <h2 class="mt-5">Biểu đồ TOP 10 Nhân Viên Bán Hàng Nhiều Nhất</h2>
    <canvas id="myChart_nvbh" class="mb-3"></canvas>
</div>
<?php include 'footer.php'; ?>
<!-- </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        var ctx = document.getElementById('myChart_nvbh').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($tenNhanVien); ?>,
                datasets: [{
                    label: 'Tổng số dịch vụ bán được',
                    data: <?php echo json_encode($soLuongDichVu); ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>

</html> -->