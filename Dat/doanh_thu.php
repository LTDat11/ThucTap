<?php
session_start();

// Check if the employee is logged in
if (!isset($_SESSION['ID_NhanVien'])) {
    // Redirect to the login page or display an error message
    header("Location: dang_nhap_nv.php");
    exit;
}

// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Congtyvienthong";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);

}

// Thực hiện truy vấn
$sql = "SELECT ID_DichVu, TenDichVu FROM DichVu";
$result = $conn->query($sql);

// Display the interface with Bootstrap
if (isset($_POST['service']) && isset($_POST['period'])) {
    $ID_DichVu = $_POST['service'];
    $period = $_POST['period'];

    // Tạo truy vấn SQL tương ứng với khoảng thời gian
    switch ($period) {
        case 'week':
            $sql1 = "SELECT 
                dv.ID_DichVu,
                dv.TenDichVu,
                SUM(gdv.GiaTien * ttb.SoLuong) AS TongTienThuDuoc
            FROM 
                ThongTinBanHang AS ttb
            JOIN 
                GoiDichVu AS gdv ON ttb.ID_GoiDichVu = gdv.ID_GoiDichVu
            JOIN 
                DichVu AS dv ON gdv.ID_DichVu = dv.ID_DichVu
            WHERE 
                dv.ID_DichVu = $ID_DichVu
                AND ttb.NgayDangKy BETWEEN DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY) 
                AND DATE_ADD(DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY), INTERVAL 6 DAY)
            GROUP BY 
                dv.ID_DichVu, dv.TenDichVu;";
            break;
        case 'month':
            $sql1 = "SELECT 
                dv.ID_DichVu,
                dv.TenDichVu,
                SUM(gdv.GiaTien * ttb.SoLuong) AS TongTienThuDuoc
            FROM 
                ThongTinBanHang AS ttb
            JOIN 
                GoiDichVu AS gdv ON ttb.ID_GoiDichVu = gdv.ID_GoiDichVu
            JOIN 
                DichVu AS dv ON gdv.ID_DichVu = dv.ID_DichVu
            WHERE 
                dv.ID_DichVu = $ID_DichVu
                AND MONTH(ttb.NgayDangKy) = MONTH(CURDATE())
                AND YEAR(ttb.NgayDangKy) = YEAR(CURDATE())
            GROUP BY 
                dv.ID_DichVu, dv.TenDichVu;";
            break;
        case 'year':
            $sql1 = "SELECT 
                dv.ID_DichVu,
                dv.TenDichVu,
                SUM(gdv.GiaTien * ttb.SoLuong) AS TongTienThuDuoc
            FROM 
                ThongTinBanHang AS ttb
            JOIN 
                GoiDichVu AS gdv ON ttb.ID_GoiDichVu = gdv.ID_GoiDichVu
            JOIN 
                DichVu AS dv ON gdv.ID_DichVu = dv.ID_DichVu
            WHERE 
                dv.ID_DichVu = $ID_DichVu
                AND YEAR(ttb.NgayDangKy) = YEAR(CURDATE())
            GROUP BY 
                dv.ID_DichVu, dv.TenDichVu;";
            break;
    }

    // Thực hiện truy vấn
    $result1 = $conn->query($sql1);
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Doanh thu</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h1>Doanh thu</h1>
        <form action="" method="post">
            <div class="form-group">
                <label for="service">Chọn dịch vụ:</label>
                <div class="d-flex">
                    <select class="form-control" id="service" name="service">
                        <?php
                        // Kiểm tra kết quả
                        if ($result->num_rows > 0) {
                            // Lặp qua từng dòng kết quả
                            while ($row = $result->fetch_assoc()) {
                                echo '<option value="' . $row['ID_DichVu'] . '">' . $row['TenDichVu'] . '</option>';
                            }
                        } else {
                            echo "Không có dịch vụ nào";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="period">Chọn khoảng thời gian:</label>
                <div class="d-flex">
                    <select class="form-control" id="period" name="period">
                        <option value="week">Tuần</option>
                        <option value="month">Tháng</option>
                        <option value="year">Năm</option>
                    </select>
                    <button type="submit" class="btn btn-primary ml-2">Xem</button>
                </div>
            </div>
        </form>
    </div>

    <div class="container">
        <h2 class="mt-5">Kết quả</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Tên Dịch Vụ</th>
                    <th>Tổng Tiền Thu Được</th>
                    <th>Lựa Chọn</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($result1) && $result1->num_rows > 0) {
                    while ($row = $result1->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['TenDichVu']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['TongTienThuDuoc']) . "</td>";
                        echo "<td><a href='chi_tiet_doanh_thu.php?service=" . $row['ID_DichVu'] . "&period=" . htmlspecialchars($period) . "' class='btn btn-info'>Xem Chi Tiết</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3' class='text-center'>Không có dữ liệu</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
