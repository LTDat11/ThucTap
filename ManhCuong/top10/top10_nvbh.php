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

// Truy vấn năm đăng ký
$sqlNam = "SELECT 
MIN(YEAR(NgayDangKy)) AS NamDangKyXaNhat,
MAX(YEAR(NgayDangKy)) AS NamDangKyGanNhat
FROM 
ThongTinBanHang";
$resultNam = $conn->query($sqlNam);

$message = '';
$sqlChitiet = "";
if (isset($_POST['time'])) {
    $time = $_POST['time'];

    switch ($time) {
        case 'year':
            $yearSelect = $_POST['yearSelect'];
            //
            $message = "Năm " . $yearSelect;
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
                WHERE 
                YEAR(ttb.NgayDangKy) = $yearSelect
                GROUP BY 
                ttbh.ID_TTNVBH, ttbh.TenNhanVien, ttbh.SoDienThoai, ttbh.DiaChi
                ORDER BY 
                TongSoLuongDichVuBanDuoc DESC
                LIMIT 10;
                ";
            //post sql chitiet
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
            DichVu AS dv ON gdv.ID_DichVu = dv.ID_DichVu
        WHERE 
            YEAR(ttb.NgayDangKy) = $yearSelect ";

            break;
        case 'quarter':
            $yearSelect = $_POST['yearSelect'];
            $quarterSelect = $_POST['quarterSelect'];
            $message = "Quý " . $quarterSelect . " năm " . $yearSelect;
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
                WHERE 
                YEAR(ttb.NgayDangKy) = $yearSelect
                AND QUARTER(ttb.NgayDangKy) = $quarterSelect
                GROUP BY 
                ttbh.ID_TTNVBH, ttbh.TenNhanVien, ttbh.SoDienThoai, ttbh.DiaChi
                ORDER BY 
                TongSoLuongDichVuBanDuoc DESC
                LIMIT 10;
                ";
            //post sql chitiet
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
            DichVu AS dv ON gdv.ID_DichVu = dv.ID_DichVu
        WHERE 
            YEAR(ttb.NgayDangKy) = $yearSelect
            AND QUARTER(ttb.NgayDangKy) = $quarterSelect ";
            break;

        case 'month':
            $yearSelect = $_POST['yearSelect'];
            $monthSelect = $_POST['monthSelect'];
            $message = "Tháng " . $monthSelect . " năm " . $yearSelect;
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
                WHERE 
                YEAR(ttb.NgayDangKy) = $yearSelect
                AND MONTH(ttb.NgayDangKy) = $monthSelect
                GROUP BY 
                ttbh.ID_TTNVBH, ttbh.TenNhanVien, ttbh.SoDienThoai, ttbh.DiaChi
                ORDER BY 
                TongSoLuongDichVuBanDuoc DESC
                LIMIT 10;
                ";

            //post sql chitiet tháng
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
            DichVu AS dv ON gdv.ID_DichVu = dv.ID_DichVu
        WHERE 
            YEAR(ttb.NgayDangKy) = $yearSelect
            AND MONTH(ttb.NgayDangKy) = $monthSelect ";
            break;


        case 'week':
            $message = "Tuần này";
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
                WHERE 
                ttb.NgayDangKy BETWEEN DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY) 
                AND DATE_ADD(DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY), INTERVAL 6 DAY)
                GROUP BY 
                ttbh.ID_TTNVBH, ttbh.TenNhanVien, ttbh.SoDienThoai, ttbh.DiaChi
                ORDER BY 
                TongSoLuongDichVuBanDuoc DESC
                LIMIT 10;
                ";
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
            DichVu AS dv ON gdv.ID_DichVu = dv.ID_DichVu
        WHERE 
            ttb.NgayDangKy BETWEEN DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY) 
            AND DATE_ADD(DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY), INTERVAL 6 DAY) ";
            break;


    }

    //kết quả truy vấn
    $result = $conn->query($sql);
    // if ($result && $result->num_rows > 0) {
    //     while ($row = $result->fetch_assoc()) {
    //         // Thêm dữ liệu vào mảng
    //         $tenNhanVien[] = $row['TenNhanVien'];
    //         $soLuongDichVu[] = $row['TongSoLuongDichVuBanDuoc'];
    //     }
    // }
    $conn->close();
}
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
<?php include '../menu.php'; ?>
<h2 class="mt-3">Top 10 Nhân Viên</h2>
<form action="" method="post">

    <div class="form-group">
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
    <div class="form-group" id="yearForm">
        <div class="d-flex">
            <label for="year">Chọn năm:</label>
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
    </div>
    <div class="form-group" id="quaterForm">
        <div class="d-flex">
            <label for="quarter">Chọn quý:</label>
            <select class="form-control" id="quarterSelect" name="quarterSelect">
                <option value="" selected disabled>Chọn quý</option>
                <option value="1">Quý 1</option>
                <option value="2">Quý 2</option>
                <option value="3">Quý 3</option>
                <option value="4">Quý 4</option>
            </select>
        </div>
    </div>
    <div class="form-group" id="monthForm">
        <div class="d-flex">
            <label for="month">Chọn tháng:</label>
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
    </div>
    <button type="submit" class="btn btn-primary ml-2">Xem</button>
</form>
<br>
<?php
// if (isset($sqlChitiet) && $sqlChitiet !== "") {
//     echo "<input type='hidden' id='sqlChitiet' value='" . htmlspecialchars($sqlChitiet) . "'>";
// }
?>
<table class="table table-bordered" id="dataTable">
    <thead>
        <tr>
            <th>Tên Nhân viên bán hàng</th>
            <th>Số Điện Thoại</th>
            <th>Địa Chỉ</th>
            <th>Tổng số dịch vụ bán được</th>
            <th>Tùy Chọn</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (isset($result) && $result->num_rows > 0) {
            $tenNhanVien = [];
            $soLuongDichVu = [];
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['TenNhanVien']) . "</td>";
                echo "<td>" . htmlspecialchars($row['SoDienThoai']) . "</td>";
                echo "<td>" . htmlspecialchars($row['DiaChi']) . "</td>";
                echo "<td>" . htmlspecialchars($row['TongSoLuongDichVuBanDuoc']) . "</td>";
                // echo "<td><a  onclick='exportQueryToFile()'  href='../chitiet/check.php?id=" . $row['ID_TTNVBH'] . "' class='btn btn-info'>Xem Chi Tiết</a></td>";
                echo '<td><a href="#" onclick="event.preventDefault(); exportQueryToFile(' . $row["ID_TTNVBH"] . ')">Xem Chi Tiết</a></td>';


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
<?php

if (isset($result) && $result->num_rows > 0) {
    // while ($row = $result->fetch_assoc()) {
    // echo "<input type='hidden' id='sqlChitiet' value='" . htmlspecialchars($sqlChitiet) . "'>";
    echo "<button onclick=\"exportTableToExcel()\" class=\"btn btn-success\">Xuất Excel</button>";
    echo '<div class="mt-5">
        <h2 class="mt-5">Biểu đồ TOP 10 Nhân Viên Bán Hàng Nhiều Nhất Trong ' . $message . ' </h2>
        <canvas id="myChart_nvbh" class="mb-3"></canvas>
    </div>';
    // }
}
?>
</div>
</body>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    function exportTableToExcel() {

        var table = document.getElementById("dataTable");
        var rows = [];
        for (var i = 0, row; row = table.rows[i]; i++) {
            var cols = [];
            for (var j = 0, col; col = row.cells[j]; j++) {
                if (j !== row.cells.length - 1) {
                    cols.push(col.innerText);
                }
            }
            rows.push(cols);
        }
        var data = JSON.stringify(rows);

        // var h2Content = document.getElementById("Header").innerText;

        var form = document.createElement("form");
        form.method = "POST";
        form.action = "../xuat/xuat_excel_top10_nvbh_nhieu.php";

        var inputData = document.createElement("input");
        inputData.type = "hidden";
        inputData.name = "data";
        inputData.value = data;

        // var inputH2 = document.createElement("input");
        // inputH2.type = "hidden";
        // inputH2.name = "h2Content";
        // inputH2.value = h2Content;

        form.appendChild(inputData);
        // form.appendChild(inputH2);
        document.body.appendChild(form);
        form.submit();
    }
    //sql chitiet
    function exportQueryToFile(id) {
        const sqlChitiet = <?= json_encode($sqlChitiet) ?>;
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '../chitiet/chi_tiet_nvbh.php';

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'sqlChitiet';
        input.value = sqlChitiet;

        const idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = 'id';
        idInput.value = id;

        form.appendChild(input);
        form.appendChild(idInput);
        document.body.appendChild(form);
        form.submit();
    }

    function capNhatHienThiForm() {
        const namDuocChon = document.getElementById('year').checked;
        const quyDuocChon = document.getElementById('quarter').checked;
        const thangDuocChon = document.getElementById('month').checked;
        const tuanDuocChon = document.getElementById('week').checked;

        document.getElementById('yearForm').style.display = (namDuocChon || quyDuocChon || thangDuocChon) ? 'block' : 'none';
        document.getElementById('quaterForm').style.display = quyDuocChon ? 'block' : 'none';
        document.getElementById('monthForm').style.display = thangDuocChon ? 'block' : 'none';
    }

    function kiemTraForm() {
        const namDuocChon = document.getElementById('year').checked;
        const quyDuocChon = document.getElementById('quarter').checked;
        const thangDuocChon = document.getElementById('month').checked;

        if (namDuocChon && document.getElementById('yearSelect').value === '') {
            alert('Vui lòng chọn năm');
            return false;
        }

        if (quyDuocChon) {
            if (document.getElementById('yearSelect').value === '') {
                alert('Vui lòng chọn năm');
                return false;
            }
            if (document.getElementById('quarterSelect').value === '') {
                alert('Vui lòng chọn quý');
                return false;
            }
        }

        if (thangDuocChon) {
            if (document.getElementById('yearSelect').value === '') {
                alert('Vui lòng chọn năm');
                return false;
            }
            if (document.getElementById('monthSelect').value === '') {
                alert('Vui lòng chọn tháng');
                return false;
            }
        }
        return true;
    }

    document.getElementById('year').addEventListener('change', function () {
        document.getElementById('quarterSelect').selectedIndex = 0;
        document.getElementById('monthSelect').selectedIndex = 0;
        document.getElementById('yearSelect').selectedIndex = 0;
        capNhatHienThiForm();
    });

    document.getElementById('quarter').addEventListener('change', function () {
        document.getElementById('quarterSelect').selectedIndex = 0;
        document.getElementById('monthSelect').selectedIndex = 0;
        document.getElementById('yearSelect').selectedIndex = 0;
        capNhatHienThiForm();
    });

    document.getElementById('month').addEventListener('change', function () {
        document.getElementById('quarterSelect').selectedIndex = 0;
        document.getElementById('monthSelect').selectedIndex = 0;
        document.getElementById('yearSelect').selectedIndex = 0;
        capNhatHienThiForm();
    });

    document.getElementById('week').addEventListener('change', function () {
        document.getElementById('quarterSelect').selectedIndex = 0;
        document.getElementById('monthSelect').selectedIndex = 0;
        document.getElementById('yearSelect').selectedIndex = 0;
        capNhatHienThiForm();
    });

    // Đính kèm kiemTraForm vào sự kiện submit của biểu mẫu
    document.querySelector('form').addEventListener('submit', function (e) {
        if (!kiemTraForm()) {
            e.preventDefault(); // Ngăn biểu mẫu gửi đi
        }
    });

    // Gọi ban đầu để đảm bảo các form bị ẩn nếu không có checkbox nào được chọn
    capNhatHienThiForm();

    // Hiển thị biểu đồ nếu có dữ liệu
    <?php if (isset($tenNhanVien) && isset($soLuongDichVu)) { ?>
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
    <?php } ?>
</script>

</html>