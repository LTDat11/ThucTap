<?php
session_start();

if (!isset($_SESSION['ID_KhachHang'])) {
    header("Location: dangnhap.php"); // Redirect đến trang đăng nhập nếu chưa đăng nhập
    exit();
}

$ID_KhachHang = $_SESSION['ID_KhachHang'];

$conn = new mysqli('localhost', 'root', '', 'Congtyvienthong');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$sql = "SELECT 
dv.TenDichVu,
gdv.TenGoiDichVu,
gdv.TocDo,
gdv.GiaTien,
gdv.MoTa,
ttb.NgayDangKy,
ttb.SoLuong
FROM 
KhachHang AS kh
JOIN 
ThongTinBanHang AS ttb ON kh.ID_KhachHang = ttb.ID_KhachHang
JOIN 
GoiDichVu AS gdv ON ttb.ID_GoiDichVu = gdv.ID_GoiDichVu
JOIN 
DichVu AS dv ON gdv.ID_DichVu = dv.ID_DichVu
WHERE 
kh.ID_KhachHang = $ID_KhachHang
ORDER BY 
ttb.NgayDangKy;
";
$result = $conn->query($sql);

$conn->close();
?>


<!-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Thông Tin Khách Hàng</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container"> -->
    <?php include 'menu_kh.php'; ?>
<div class="content container-fluid mt-0">
    <div class="card shadow-lg border-0 rounded-lg">
        <div class="card-header bg-primary text-white text-center py-4">
            <h2 class="mb-0"><i class="fas fa-list"></i> Danh Sách Dịch Vụ Bạn Đăng Kí</h2>
        </div>
        <div class="card-body p-5">
            <div class="table-responsive">
                <table class="table table-hover table-bordered table-striped rounded shadow-sm">
                    <thead class="bg-primary text-white text-center rounded-top">
                        <tr>
                            <th>Tên Dịch Vụ</th>
                            <th>Tên Gói Dịch Vụ</th>
                            <th>Tốc Độ</th>
                            <th>Giá Tiền</th>
                            <th>Mô Tả</th>
                            <th>Ngày Đăng Kí</th>
                            <th>Số Lượng</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['TenDichVu']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['TenGoiDichVu']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['TocDo']) . "</td>";
                                echo "<td>" . number_format($row['GiaTien'], 0, ',', '.') . "</td>";
                                echo "<td>" . htmlspecialchars($row['MoTa']) . "</td>";
                                echo "<td>" . date("d/m/Y", strtotime($row['NgayDangKy'])) . "</td>";
                                echo "<td class='text-center'>" . htmlspecialchars($row['SoLuong']) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7' class='text-center'>Không có dữ liệu</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
