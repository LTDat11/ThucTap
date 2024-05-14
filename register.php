<?php
include 'connect.php';

if(isset($_POST['register'])){
    $hoten = $_POST['hoten'];
    $sdt = $_POST['sdt'];
    $diachi = $_POST['diachi'];
    $tendangnhap = $_POST['tendangnhap'];
    $matkhau = $_POST['matkhau'];

    // Insert into KhachHang table
    $sql_khachhang = "INSERT INTO KhachHang (HoTen, SDT, DiaChi) VALUES ('$hoten', '$sdt', '$diachi')";
    $conn->query($sql_khachhang);

    // Get the last inserted ID_KhachHang
    $id_khachhang = $conn->insert_id;

    // Insert into TaiKhoanDangNhap table
    $sql_taikhoan = "INSERT INTO TaiKhoanDangNhap (TenDangNhap, MatKhau, ID_KhachHang) VALUES ('$tendangnhap', '$matkhau', '$id_khachhang')";
    $conn->query($sql_taikhoan);

    echo "Đăng ký thành công!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">
            <img src="https://upload.wikimedia.org/wikipedia/vi/thumb/6/6c/Logo_Dai_hoc_Can_Tho.svg/282px-Logo_Dai_hoc_Can_Tho.svg.png?20200610062224" alt="Logo" width="30" height="30" class="d-inline-block align-top">
            CÔNG TY VIỄN THÔNG
        </a>
    </nav>

    <div class="container">
        <div class="card mt-5">
            <div class="card-header">
                <h3>Đăng ký tài khoản</h3>
            </div>
            <div class="card-body">
                <form method="post" action="">
                    <div class="form-group">
                        <label>Họ tên</label>
                        <input type="text" class="form-control" name="hoten">
                    </div>
                    <div class="form-group">
                        <label>Số điện thoại</label>
                        <input type="text" class="form-control" name="sdt">
                    </div>
                    <div class="form-group">
                        <label>Địa chỉ</label>
                        <input type="text" class="form-control" name="diachi">
                    </div>
                    <div class="form-group">
                        <label>Tên đăng nhập</label>
                        <input type="text" class="form-control" name="tendangnhap">
                    </div>
                    <div class="form-group">
                        <label>Mật khẩu</label>
                        <input type="password" class="form-control" name="matkhau">
                    </div>
                    <button type="submit" class="btn btn-primary" name="register">Đăng ký</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>