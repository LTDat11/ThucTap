<?php
session_start();

// Check if the employee is logged in
if (!isset($_SESSION['ID_NhanVien'])) {
    // Redirect to the login page or display an error message
    header("Location: ../dangnhap_NV.php");
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

// Thực hiện truy vấn dịch vụ
$sql = "SELECT ID_DichVu, TenDichVu FROM DichVu";
$result = $conn->query($sql);

// Truy vấn năm đăng ký
$sqlNam = "SELECT 
MIN(YEAR(NgayDangKy)) AS NamDangKyXaNhat,
MAX(YEAR(NgayDangKy)) AS NamDangKyGanNhat
FROM 
ThongTinBanHang";
$resultNam = $conn->query($sqlNam);

// Truy vấn doanh thu
$sql1 = "";
$message = "";
$message2 = "";

if (isset($_POST['service']) && isset($_POST['time'])) {
    $ID_DichVu = $_POST['service'];
    $sqlTenDichVu = "SELECT TenDichVu FROM DichVu WHERE ID_DichVu = $ID_DichVu";
    $resultTenDichVu = $conn->query($sqlTenDichVu);

    if ($resultTenDichVu->num_rows > 0) {
        $row = $resultTenDichVu->fetch_assoc();
        $message = "dịch vụ " . $row['TenDichVu'];
    }


    $timeOption = $_POST['time'];
    // echo $timeOption;

    switch ($timeOption) {
        case 'week':
            $message2 = "tuần này";
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
                dv.ID_DichVu, dv.TenDichVu";

            $sql2 = "SELECT 
            gdv.ID_GoiDichVu,
            gdv.TenGoiDichVu,
            gdv.GiaTien,
            SUM(ttb.SoLuong) AS TongSoLuong,
            (gdv.GiaTien * SUM(ttb.SoLuong)) AS ThanhTien
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
            gdv.ID_GoiDichVu, gdv.TenGoiDichVu, gdv.GiaTien;
        ";
            break;


        case 'year':
            if (isset($_POST['yearSelect'])) {
                $year = $_POST['yearSelect'];
                $message2 = "năm $year";
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
                    AND YEAR(ttb.NgayDangKy) = $year
                GROUP BY 
                    dv.ID_DichVu, dv.TenDichVu";

                $sql2 = "SELECT
                    gdv.ID_GoiDichVu,
                    gdv.TenGoiDichVu,
                    gdv.GiaTien,
                    SUM(ttb.SoLuong) AS TongSoLuong,
                    (gdv.GiaTien * SUM(ttb.SoLuong)) AS ThanhTien
                FROM
                    ThongTinBanHang AS ttb
                JOIN
                    GoiDichVu AS gdv ON ttb.ID_GoiDichVu = gdv.ID_GoiDichVu
                JOIN
                    DichVu AS dv ON gdv.ID_DichVu = dv.ID_DichVu
                WHERE
                    dv.ID_DichVu = $ID_DichVu
                    AND YEAR(ttb.NgayDangKy) = $year
                GROUP BY
                    gdv.ID_GoiDichVu, gdv.TenGoiDichVu, gdv.GiaTien;
                ";
            }


            break;

        case 'quarter':
            if (isset($_POST['quarterSelect']) && isset($_POST['yearSelect'])) {
                $year = $_POST['yearSelect'];
                $quarter = $_POST['quarterSelect'];
                $message2 = "quý $quarter năm $year";
                $startMonth = ($quarter - 1) * 3 + 1;
                $endMonth = $startMonth + 2;
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
                        AND YEAR(ttb.NgayDangKy) = $year
                        AND MONTH(ttb.NgayDangKy) BETWEEN $startMonth AND $endMonth 
                    GROUP BY 
                        dv.ID_DichVu, dv.TenDichVu";

                $sql2 = "SELECT
                    gdv.ID_GoiDichVu,
                    gdv.TenGoiDichVu,
                    gdv.GiaTien,
                    SUM(ttb.SoLuong) AS TongSoLuong,
                    (gdv.GiaTien * SUM(ttb.SoLuong)) AS ThanhTien
                FROM
                    ThongTinBanHang AS ttb
                JOIN
                    GoiDichVu AS gdv ON ttb.ID_GoiDichVu = gdv.ID_GoiDichVu
                JOIN
                    DichVu AS dv ON gdv.ID_DichVu = dv.ID_DichVu
                WHERE
                    dv.ID_DichVu = $ID_DichVu
                    AND YEAR(ttb.NgayDangKy) = $year
                    AND MONTH(ttb.NgayDangKy) BETWEEN $startMonth AND $endMonth
                GROUP BY
                    gdv.ID_GoiDichVu, gdv.TenGoiDichVu, gdv.GiaTien;
                ";
            }
            break;

        case 'month':
            if (isset($_POST['monthSelect']) && isset($_POST['yearSelect'])) {
                $year = $_POST['yearSelect'];
                $month = $_POST['monthSelect'];
                $message2 = "tháng $month năm $year";
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
                    AND YEAR(ttb.NgayDangKy) = $year
                    AND MONTH(ttb.NgayDangKy) = $month
                GROUP BY 
                    dv.ID_DichVu, dv.TenDichVu";

                $sql2 = "SELECT
                    gdv.ID_GoiDichVu,
                    gdv.TenGoiDichVu,
                    gdv.GiaTien,
                    SUM(ttb.SoLuong) AS TongSoLuong,
                    (gdv.GiaTien * SUM(ttb.SoLuong)) AS ThanhTien
                FROM
                    ThongTinBanHang AS ttb
                JOIN
                    GoiDichVu AS gdv ON ttb.ID_GoiDichVu = gdv.ID_GoiDichVu
                JOIN
                    DichVu AS dv ON gdv.ID_DichVu = dv.ID_DichVu
                WHERE
                    dv.ID_DichVu = $ID_DichVu
                    AND YEAR(ttb.NgayDangKy) = $year
                    AND MONTH(ttb.NgayDangKy) = $month
                GROUP BY
                    gdv.ID_GoiDichVu, gdv.TenGoiDichVu, gdv.GiaTien;
                ";
            }
            break;
    }


    $result1 = $conn->query($sql1);
    $result2 = $conn->query($sql2);
}
?>

<!-- <!DOCTYPE html>
<html>

<head>
    <title>Doanh thu</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container"> -->
<?php include '../menu.php'; ?>
<div class="container">
    <h1>Doanh thu</h1>
    <form action="" method="post">
        <div class="form-group">
            <label for="service">Chọn dịch vụ:</label>
            <div class="d-flex">
                <select class="form-control" id="service" name="service">
                    <?php
                    if ($result->num_rows > 0) {
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
            <label for="period">Chọn kiểu kết toán:</label>
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
</div>

<div class="container">

    <table class="table table-bordered" id="dataTable">

        <?php
        if (isset($message) && isset($message2)) {
            echo "<h2 class='mt-5' id='Header'>Kết quả doanh thu $message $message2</h2>";
        } else {
            echo "<h2 class='mt-5'>Kết quả doanh thu</h2>";
        }
        ?>
        <thead>
            <tr>
                <th>Tên Gói Dịch Vụ</th>
                <th>Giá Tiền</th>
                <th>Số Lượng</th>
                <th>Thành Tiền</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (isset($result2) && $result2->num_rows > 0) {
                while ($row = $result2->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['TenGoiDichVu']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['GiaTien']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['TongSoLuong']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['ThanhTien']) . "</td>";

                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4' class='text-center'>Không có dữ liệu</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <?php
    if (isset($result1) && $result1->num_rows > 0) {
        while ($row = $result1->fetch_assoc()) {
            echo "<h3 class='mt-3'>Tổng
        doanh thu: " . htmlspecialchars($row['TongTienThuDuoc']) . "</h3>";
            // echo "<a href=\"xuat_excel_doanh_thu.php\" class=\"btn btn-success\">Xuất Excel</a>";
            echo "<button onclick=\"exportTableToExcel()\" class=\"btn btn-success\">Xuất Excel</button>";
        }
    }
    ?>

    </body>
</div>
<?php include '../footer.php'; ?>
<!-- </div>

</body> -->

<!-- <script>
    function exportTableToExcel() {
        var table = document.getElementById("dataTable");
        var rows = [];
        for (var i = 0, row; row = table.rows[i]; i++) {
            var cols = [];
            for (var j = 0, col; col = row.cells[j]; j++) {
                cols.push(col.innerText);
            }
            rows.push(cols);
        }
        var data = JSON.stringify(rows);

        var h2Content = document.getElementById("Header").innerText;

        var form = document.createElement("form");
        form.method = "POST";
        form.action = "../xuat/xuat_excel_doanh_thu.php";

        var inputData = document.createElement("input");
        inputData.type = "hidden";
        inputData.name = "data";
        inputData.value = data;

        var inputH2 = document.createElement("input");
        inputH2.type = "hidden";
        inputH2.name = "h2Content";
        inputH2.value = h2Content;

        form.appendChild(inputData);
        form.appendChild(inputH2);
        document.body.appendChild(form);
        form.submit();
    }

    function updateFormDisplay() {
        const yearChecked = document.getElementById('year').checked;
        const quarterChecked = document.getElementById('quarter').checked;
        const monthChecked = document.getElementById('month').checked;

        document.getElementById('yearForm').style.display = (yearChecked || quarterChecked || monthChecked) ? 'block' : 'none';
        document.getElementById('quaterForm').style.display = quarterChecked ? 'block' : 'none';
        document.getElementById('monthForm').style.display = monthChecked ? 'block' : 'none';
    }

    function validateForm() {
        const yearChecked = document.getElementById('year').checked;
        const quarterChecked = document.getElementById('quarter').checked;
        const monthChecked = document.getElementById('month').checked;
        const weekChecked = document.getElementById('week').checked;

        if (yearChecked && document.getElementById('yearSelect').value === '') {
            alert('Vui lòng chọn năm');
            return false;
        }

        if (quarterChecked) {
            if (document.getElementById('yearSelect').value === '') {
                alert('Vui lòng chọn năm');
                return false;
            }
            if (document.getElementById('quarterSelect').value === '') {
                alert('Vui lòng chọn quý');
                return false;
            }
        }

        if (monthChecked) {
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

    document.getElementById('year').addEventListener('change', function() {
        document.getElementById('quarterSelect').selectedIndex = 0;
        document.getElementById('monthSelect').selectedIndex = 0;
        document.getElementById('yearSelect').selectedIndex = 0;
        updateFormDisplay();
    });

    document.getElementById('quarter').addEventListener('change', function() {
        document.getElementById('quarterSelect').selectedIndex = 0;
        document.getElementById('monthSelect').selectedIndex = 0;
        document.getElementById('yearSelect').selectedIndex = 0;
        updateFormDisplay();
    });

    document.getElementById('month').addEventListener('change', function() {
        document.getElementById('quarterSelect').selectedIndex = 0;
        document.getElementById('monthSelect').selectedIndex = 0;
        document.getElementById('yearSelect').selectedIndex = 0;
        updateFormDisplay();
    });

    document.getElementById('week').addEventListener('change', function() {
        document.getElementById('quarterSelect').selectedIndex = 0;
        document.getElementById('monthSelect').selectedIndex = 0;
        document.getElementById('yearSelect').selectedIndex = 0;
        updateFormDisplay();
    });

    // Attach validateForm to the form's submit event
    document.querySelector('form').addEventListener('submit', function(e) {
        if (!validateForm()) {
            e.preventDefault(); // Prevent the form from submitting
        }
    });

    // Initial call to ensure forms are hidden if no checkbox is selected
    updateFormDisplay();
</script> -->

<!-- </html> -->