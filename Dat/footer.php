</div> <!-- Đóng thẻ div của container -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

<script>
    function confirmDelete_ttnv(id) {
        var confirmed = confirm("Bạn có chắc chắn muốn xóa nhân viên này?");
        if (confirmed) {
            window.location.href = '../xoa/xoa_thong_tin_nhan_vien.php?id=' + id;
        }
    }

    function confirmDelete_dv(id) {
        var confirmed = confirm("Bạn có chắc chắn muốn xóa dịch vụ này?");
        if (confirmed) {
            window.location.href = '../xoa/xoa_dich_vu.php?id=' + id;
        }
    }
</script>

<script>
var ctx_nvbh = document.getElementById('myChart_nvbh').getContext('2d');
var myChart_nvbh = new Chart(ctx_nvbh, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($tenNhanVien); ?>,
        datasets: [{
            label: 'Tổng số dịch vụ bán được',
            data: <?php echo json_encode($soLuongDichVu); ?>,
            backgroundColor: 'rgba(141, 182, 205, 0.6)', // Thay đổi màu nền
            borderColor: 'rgba(92, 147, 180, 1)', // Thay đổi màu viền
            borderWidth: 1,
            borderRadius: 5,
            hoverBackgroundColor: 'rgba(92, 147, 180, 0.8)' // Thay đổi màu khi di chuột qua
        }]
    },
    options: {
        plugins: {
            datalabels: {
                anchor: 'center',
                align: 'top',
                color: 'white', // Màu chữ
                font: {
                    weight: 'bold',
                    size: 14
                },
                formatter: function(value, context) {
                    return value;
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
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    fontSize: 14
                }
            },
            x: {
                ticks: {
                    fontSize: 14
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

<script>
var ctx = document.getElementById('myChart_kh_dv_max').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($tenKhachHang); ?>,
        datasets: [{
            label: 'Số lượng dịch vụ sử dụng',
            data: <?php echo json_encode($soLuongDichVu); ?>,
            backgroundColor: 'rgba(205, 160, 141, 0.6)', // Thay đổi màu nền
            borderColor: 'rgba(180, 119, 92, 1)', // Thay đổi màu viền
            borderWidth: 1,
            borderRadius: 5,
            hoverBackgroundColor: 'rgba(180, 119, 92, 0.8)' // Thay đổi màu khi di chuột qua
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    fontSize: 14
                }
            },
            x: {
                ticks: {
                    fontSize: 14
                }
            }
        },
        layout: {
            padding: {
                bottom: 80
            }
        },
        plugins: {
            datalabels: {
                anchor: 'center',
                align: 'top',
                formatter: function(value, context) {
                    return value;
                },
                color: 'white', // Màu chữ
                font: {
                    weight: 'bold',
                    size: 14
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
        }
    },
    plugins: [
        ChartDataLabels
    ]
});
</script>
</body>

</html>