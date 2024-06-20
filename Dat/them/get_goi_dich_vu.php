<?php
// Kết nối đến cơ sở dữ liệu
include ('../connect.php');

// Lấy id_dich_vu từ yêu cầu
$id_dich_vu = $_GET['id_dich_vu'];

// Thực hiện truy vấn để lấy thông tin về các gói dịch vụ
$query = "SELECT ID_GoiDichVu, TenGoiDichVu FROM goidichvu WHERE ID_DichVu = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id_dich_vu);
$stmt->execute();

// Thay vì sử dụng get_result(), chúng ta sẽ sử dụng bind_result() và fetch()
$stmt->bind_result($ID_GoiDichVu, $TenGoiDichVu);

// Chuyển đổi kết quả truy vấn thành một mảng các gói dịch vụ
$goi_dich_vu = array();
while ($stmt->fetch()) {
    $goi_dich_vu[] = array(
        'id' => $ID_GoiDichVu,
        'ten' => $TenGoiDichVu
    );
}

// Trả về mảng các gói dịch vụ dưới dạng JSON
echo json_encode($goi_dich_vu);

$stmt->close();
$conn->close();
?>