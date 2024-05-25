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

// Thực hiện truy vấn
$sql = "SELECT 
dv.ID_DichVu,
dv.TenDichVu,
SUM(ttb.SoLuong) AS TongSoGoiDaBan
FROM 
ThongTinBanHang AS ttb
JOIN 
GoiDichVu AS gdv ON ttb.ID_GoiDichVu = gdv.ID_GoiDichVu
JOIN 
DichVu AS dv ON gdv.ID_DichVu = dv.ID_DichVu
GROUP BY 
dv.ID_DichVu, dv.TenDichVu
ORDER BY 
TongSoGoiDaBan DESC
LIMIT 10;
";

$result = $conn->query($sql);

$conn->close();
?>


<!-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dịch Vụ Được Bán Nhiều Nhất</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>-->

<?php include '../menu.php'; ?>
<div class="container">
    <h2 class="mt-5">Dịch Vụ Được Bán Nhiều Nhất</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tên Dịch Vụ</th>
                <th>Số gói dịch vụ bán được</th>
                <th>Tùy chọn</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                $labels = [];
                $data = [];
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['TenDichVu']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['TongSoGoiDaBan']) . "</td>";
                    echo "<td><a href='../chitiet/chi_tiet_dich_vu_dang_ky_nhieu.php?id=" . $row['ID_DichVu'] . "' class='btn btn-info bi bi-info-circle'> Xem chi tiết</a></td>";
                    echo "</tr>";

                    $labels[] = $row['TenDichVu'];
                    $data[] = $row['TongSoGoiDaBan'];
                }
            } else {
                echo "<tr><td colspan='7' class='text-center'>Không có dữ liệu</td></tr>";
            }
            ?>
        </tbody>

    </table>

    <div class="mt-3 mb-3">
        <h2 class="mt-3">Biểu đồ Dịch Vụ Được Bán Nhiều Nhất</h2>
        <canvas id="myChart"></canvas>
    </div>
</div>
<?php include '../footer.php'; ?>
<!-- </div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>



<script>
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($labels); ?>,
            datasets: [{
                label: 'Số lượng dịch vụ sử dụng',
                data: <?php echo json_encode($data); ?>,
                backgroundColor: 'rgba(141, 182, 205, 0.6)', // Thay đổi màu nền
                borderColor: 'rgba(92, 147, 180, 1)', // Thay đổi màu viền
                borderWidth: 1,
                borderRadius: 5, // Thêm bo góc cho thanh
                hoverBackgroundColor: 'rgba(92, 147, 180, 0.8)' // Thay đổi màu khi di chuột qua
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        fontSize: 14 // Thay đổi kích thước chữ trục y
                    }
                },
                x: {
                    ticks: {
                        fontSize: 14 // Thay đổi kích thước chữ trục x
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += new Intl.NumberFormat('en-US').format(context.parsed.y);
                            }
                            return label;
                        }
                    }
                },
                datalabels: {
                    anchor: 'center',
                    align: 'center',
                    color: 'white', // Thay đổi màu chữ
                    font: {
                        weight: 'bold',
                        size: 14
                    },
                    formatter: function(value, context) {
                        return value.toLocaleString();
                    }
                },
                legend: {
                    position: 'bottom',
                    align: 'center',
                    labels: {
                        font: {
                            size: 14
                        }
                    }
                }
            },
            layout: {
                padding: {
                    bottom: 80
                }
            }
        },
        plugins: [
            ChartDataLabels
        ]
    });
</script>
</body>

</html> -->