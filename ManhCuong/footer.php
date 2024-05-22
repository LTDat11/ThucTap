</div> <!-- Đóng thẻ div của container -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

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

<script>
    var ctx = document.getElementById('myChart_kh_dv_max').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($tenKhachHang); ?>,
            datasets: [{
                label: 'Số lượng dịch vụ sử dụng',
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

</html>