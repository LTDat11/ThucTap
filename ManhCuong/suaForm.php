<?php
//nhân viên phải đăng nhập mới xem được
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

// Truy vấn năm đăng ký
$sqlNam = "SELECT 
MIN(YEAR(NgayDangKy)) AS NamDangKyXaNhat,
MAX(YEAR(NgayDangKy)) AS NamDangKyGanNhat
FROM 
ThongTinBanHang";
$resultNam = $conn->query($sqlNam);

$message = '';
$sqlChitiet = "";

// Xử lý form

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $time = $_POST['time'] ?? '';
    $yearSelect = $_POST['yearSelect'] ?? '';
    $quarterSelect = $_POST['quarterSelect'] ?? '';
    $monthSelect = $_POST['monthSelect'] ?? '';
    $weekStartSelect = $_POST['weekStartSelect'] ?? '';
    $weekEndSelect = $_POST['weekEndSelect'] ?? '';

    $sql = "SELECT 
    ttbh.ID_TTNVBH,
    ttbh.TenNhanVien,
    ttbh.SoDienThoai,
    ttbh.DiaChi,
    COUNT(ttb.ID_TTNVBH) AS TongSoLuongDichVuBanDuoc
    FROM 
    ThongTinBanHang AS ttb
    JOIN 
    TTNhanVienBanHang AS ttbh ON ttb.ID_TTNVBH = ttbh.ID_TTNVBH ";
    //dành cho chi tiết các khách hàng
    $sqlChitiet = "SELECT 
    ttb.ID_ThongTinBanHang,
    ttb.NgayDangKy,
    kh.Ten AS TenKhachHang,
    dv.TenDichVu,
    gdv.TenGoiDichVu,
    ttb.SoLuong,
    (gdv.GiaTien * ttb.SoLuong) AS TongTien
FROM 
    ThongTinBanHang AS ttb
JOIN 
    TTNhanVienBanHang AS nv ON ttb.ID_TTNVBH = nv.ID_TTNVBH
JOIN 
    KhachHang AS kh ON ttb.ID_KhachHang = kh.ID_KhachHang
JOIN 
    GoiDichVu AS gdv ON ttb.ID_GoiDichVu = gdv.ID_GoiDichVu
JOIN 
    DichVu AS dv ON gdv.ID_DichVu = dv.ID_DichVu ";

    if ($time == 'year') {               //năm
        $sql .= "WHERE 
        YEAR(ttb.NgayDangKy) = $yearSelect
        GROUP BY 
        ttbh.ID_TTNVBH, ttbh.TenNhanVien, ttbh.SoDienThoai, ttbh.DiaChi
        ORDER BY 
        TongSoLuongDichVuBanDuoc DESC
        LIMIT 10;
    ";
        $sqlChitiet .= "WHERE 
        YEAR(ttb.NgayDangKy) = $yearSelect ";
        $message = " Năm $yearSelect";
    } elseif ($time == 'quarter') {     //quý
        $sql .= "WHERE 
        YEAR(ttb.NgayDangKy) = $yearSelect
        AND QUARTER(ttb.NgayDangKy) = $quarterSelect
        GROUP BY 
        ttbh.ID_TTNVBH, ttbh.TenNhanVien, ttbh.SoDienThoai, ttbh.DiaChi
        ORDER BY 
        TongSoLuongDichVuBanDuoc DESC
        LIMIT 10;
    ";
        $sqlChitiet .= "WHERE 
        YEAR(ttb.NgayDangKy) = $yearSelect
        AND QUARTER(ttb.NgayDangKy) = $quarterSelect ";
        $message = " Quý $quarterSelect Năm $yearSelect";
    } elseif ($time == 'month') {       //tháng
        $sql .= "WHERE 
        YEAR(ttb.NgayDangKy) = $yearSelect
        AND MONTH(ttb.NgayDangKy) = $monthSelect
        GROUP BY 
        ttbh.ID_TTNVBH, ttbh.TenNhanVien, ttbh.SoDienThoai, ttbh.DiaChi
        ORDER BY 
        TongSoLuongDichVuBanDuoc DESC
        LIMIT 10;
    ";
        $sqlChitiet .= "WHERE 
        YEAR(ttb.NgayDangKy) = $yearSelect
        AND MONTH(ttb.NgayDangKy) = $monthSelect ";
        $message = " Tháng $monthSelect Năm $yearSelect";
    } elseif ($time == 'week') {        //tuần
        $sql .= "WHERE 
        ttb.NgayDangKy BETWEEN '$weekStartSelect' AND '$weekEndSelect' 
        GROUP BY 
        ttbh.ID_TTNVBH, ttbh.TenNhanVien, ttbh.SoDienThoai, ttbh.DiaChi
        ORDER BY 
        TongSoLuongDichVuBanDuoc DESC
        LIMIT 10;
    ";
        $sqlChitiet .= "WHERE 
        ttb.NgayDangKy BETWEEN '$weekStartSelect' AND '$weekEndSelect' ";
        $message = "Theo Tuần Từ $weekStartSelect Đến $weekEndSelect";
    }
    if ($time != '') {
        $result = $conn->query($sql);
    }
}

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách TOP 10 Thông Tin Khách Hàng Sử Dụng Dịch Vụ Nhiều Nhất</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .hidden {
            display: none;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <!-- <form action="" method="post"> -->
        <div class="form-group period">
            <label for="period">Chọn kiểu thống kê:</label>
            <br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="time" id="year" value="year">
                <label class="form-check-label" for="year">Năm</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="time" id="quarter" value="quarter">
                <label class="form-check-label" for="quarter">Quý</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="time" id="month" value="month">
                <label class="form-check-label" for="month">Tháng</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="time" id="week" value="week">
                <label class="form-check-label" for="week">Tuần</label>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-3 hidden" id="yearForm">
                <label for="yearSelect">Chọn năm:</label>
                <select class="form-control" id="yearSelect" name="yearSelect">
                    <option value="" selected disabled>Chọn năm</option>
                    <?php
                    if ($resultNam->num_rows > 0) {
                        while ($row = $resultNam->fetch_assoc()) {
                            $namMin = $row['NamDangKyXaNhat'];
                            $namMax = $row['NamDangKyGanNhat'];
                            for ($i = $namMin; $i <= $namMax; $i++) {
                                echo '<option value="' . $i . '">' . $i . '</option>';
                            }
                        }
                    } else {
                        echo "Chưa có dữ liệu";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group col-md-3 hidden" id="quarterForm">
                <label for="quarterSelect">Chọn quý:</label>
                <select class="form-control" id="quarterSelect" name="quarterSelect">
                    <option value="" selected disabled>Chọn quý</option>
                    <option value="1">Quý 1</option>
                    <option value="2">Quý 2</option>
                    <option value="3">Quý 3</option>
                    <option value="4">Quý 4</option>
                </select>
            </div>

            <div class="form-group col-md-3 hidden" id="monthForm">
                <label for="monthSelect">Chọn tháng:</label>
                <select class="form-control" id="monthSelect" name="monthSelect">
                    <option value="" selected disabled>Chọn tháng</option>
                    <option value="1">Tháng 1</option>
                    <option value="2">Tháng 2</option>
                    <option value="3">Tháng 3</option>
                    <option value="4">Tháng 4</option>
                    <option value="5">Tháng 5</option>
                    <option value="6">Tháng 6</option>
                    <option value="7">Tháng 7</option>
                    <option value="8">Tháng 8</option>
                    <option value="9">Tháng 9</option>
                    <option value="10">Tháng 10</option>
                    <option value="11">Tháng 11</option>
                    <option value="12">Tháng 12</option>
                </select>
            </div>

            <div class="form-group hidden" id="weekForm">
                <div class="form-row">
                    <div class="col">
                        <label for="weekStartSelect">Ngày bắt đầu</label>
                        <input type="date" class="form-control" id="weekStartSelect" name="weekStartSelect">
                    </div>
                    <div class="col">
                        <label for="weekEndSelect">Ngày kết thúc</label>
                        <input type="date" class="form-control" id="weekEndSelect" name="weekEndSelect">
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">Lọc</button>
        </div>
        <!-- </form> -->
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const yearForm = document.getElementById('yearForm');
            const quarterForm = document.getElementById('quarterForm');
            const monthForm = document.getElementById('monthForm');
            const weekForm = document.getElementById('weekForm');

            document.getElementsByName('time').forEach((radio) => {
                radio.addEventListener('change', function () {
                    yearForm.classList.add('hidden');
                    quarterForm.classList.add('hidden');
                    monthForm.classList.add('hidden');
                    weekForm.classList.add('hidden');

                    if (this.value === 'year') {
                        yearForm.classList.remove('hidden');
                    } else if (this.value === 'quarter') {
                        yearForm.classList.remove('hidden');
                        quarterForm.classList.remove('hidden');
                    } else if (this.value === 'month') {
                        yearForm.classList.remove('hidden');
                        monthForm.classList.remove('hidden');
                    } else if (this.value === 'week') {
                        weekForm.classList.remove('hidden');
                    }
                });
            });
        });
    </script>
</body>

</html>