</div> <!-- Đóng thẻ div của container -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

<script>
    // Hide submenus
    $('#body-row .collapse').collapse('hide');

    // Collapse/Expand icon
    $('#collapse-icon').addClass('fa-angle-double-left');

    // Collapse click
    $('[data-toggle=sidebar-colapse]').click(function() {
        SidebarCollapse();
    });

    function SidebarCollapse() {
        $('.menu-collapsed').toggleClass('d-none');
        $('.sidebar-submenu').toggleClass('d-none');
        $('.submenu-icon').toggleClass('d-none');
        $('#sidebar-container').toggleClass('sidebar-expanded sidebar-collapsed');

        // Treating d-flex/d-none on separators with title
        var SeparatorTitle = $('.sidebar-separator-title');
        if (SeparatorTitle.hasClass('d-flex')) {
            SeparatorTitle.removeClass('d-flex');
        } else {
            SeparatorTitle.addClass('d-flex');
        }

        // Collapse/Expand icon
        $('#collapse-icon').toggleClass('fa-angle-double-left fa-angle-double-right');
    }
</script>

<script>
    function previewImage(input) {
        const preview = document.getElementById('preview');
        const file = input.files[0];
        const reader = new FileReader();

        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';

        }

        if (file) {
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    }
</script>

<script>
    function handleSuccess(message) {
        if (message) {
            let continueAdding = confirm(message + "\nBạn có muốn tiếp tục thêm thông tin mới không?");
            if (!continueAdding) {
                window.location.href = '../danhsach/danh_sach_thong_tin_khach_hang.php';
            }
        }
    }
</script>

<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>

<script>
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
</script>


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