-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 30, 2024 at 05:44 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `congtyvienthong`
--

-- --------------------------------------------------------

--
-- Table structure for table `dichvu`
--

CREATE TABLE `dichvu` (
  `ID_DichVu` int NOT NULL,
  `TenDichVu` varchar(255) NOT NULL,
  `nguoisua` varchar(255) DEFAULT NULL,
  `ngaysua` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `dichvu`
--

INSERT INTO `dichvu` (`ID_DichVu`, `TenDichVu`, `nguoisua`, `ngaysua`) VALUES
(1, 'Internet', 'Nguyen Van F', '2024-05-30 11:36:50'),
(2, 'Truyền Hình', NULL, NULL),
(3, 'Di động', NULL, NULL),
(4, 'Điện thoại cố định', NULL, NULL),
(5, 'Dịch vụ Khác', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `doanhthu`
--

CREATE TABLE `doanhthu` (
  `ID_DoanhThu` int NOT NULL,
  `ID_ThongTinBanHang` int DEFAULT NULL,
  `ThoiGian` date NOT NULL,
  `SoTien` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `doanhthu`
--

INSERT INTO `doanhthu` (`ID_DoanhThu`, `ID_ThongTinBanHang`, `ThoiGian`, `SoTien`) VALUES
(7, 7, '2024-01-07', '150000.00'),
(8, 8, '2024-01-08', '300000.00'),
(9, 9, '2024-01-09', '100000.00'),
(10, 10, '2024-01-10', '200000.00'),
(11, 13, '2024-05-20', '1000000.00'),
(12, 14, '2024-05-22', '1500000.00'),
(14, 16, '2024-05-24', '100000.00'),
(15, 17, '2024-05-25', '500000.00'),
(16, 18, '2024-05-30', '200000.00'),
(17, 19, '2024-05-30', '500000.00');

-- --------------------------------------------------------

--
-- Table structure for table `goidichvu`
--

CREATE TABLE `goidichvu` (
  `ID_GoiDichVu` int NOT NULL,
  `ID_DichVu` int DEFAULT NULL,
  `TenGoiDichVu` varchar(255) NOT NULL,
  `TocDo` varchar(50) DEFAULT NULL,
  `GiaTien` decimal(10,0) NOT NULL,
  `MoTa` varchar(255) DEFAULT NULL,
  `ImgURL` varchar(255) DEFAULT NULL,
  `nguoisua` varchar(255) DEFAULT NULL,
  `ngaysua` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `goidichvu`
--

INSERT INTO `goidichvu` (`ID_GoiDichVu`, `ID_DichVu`, `TenGoiDichVu`, `TocDo`, `GiaTien`, `MoTa`, `ImgURL`, `nguoisua`, `ngaysua`) VALUES
(1, 1, 'Gói cước Internet Cơ bản', '100', '200000', 'Internet tốc độ cơ bản', './image/', 'Nguyen Van F', '2024-05-30 11:25:08'),
(2, 1, 'Gói cước Internet Cao cấp', '200', '500000', 'Internet tốc độ cao', './image/', NULL, NULL),
(3, 2, 'Gói cước Truyền Hình Cơ bản', NULL, '150000', 'Truyền hình cáp cơ bản', './image/', NULL, NULL),
(4, 2, 'Gói cước Truyền Hình Cao cấp', NULL, '300000', 'Truyền hình cáp cao cấp', './image/TruyenHinh_nangcao.PNG', NULL, NULL),
(5, 3, 'Gói cước Di động Cơ bản', NULL, '100000', 'Di động cơ bản', './image/test.png', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `khachhang`
--

CREATE TABLE `khachhang` (
  `ID_KhachHang` int NOT NULL,
  `Ten` varchar(100) NOT NULL,
  `SoDienThoai` varchar(20) NOT NULL,
  `DiaChi` varchar(255) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `nguoisua` varchar(255) DEFAULT NULL,
  `ngaysua` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `khachhang`
--

INSERT INTO `khachhang` (`ID_KhachHang`, `Ten`, `SoDienThoai`, `DiaChi`, `Username`, `Password`, `nguoisua`, `ngaysua`) VALUES
(1, 'Nguyen Van A', '0123456788', 'Cantho', 'nguyenvana', 'password1', NULL, NULL),
(2, 'Tran Thi B', '0987654321', 'Saigon', 'tranthib', 'password2', NULL, NULL),
(3, 'Le Van C', '0912345678', 'Danang', 'levanc', 'password3', NULL, NULL),
(4, 'Pham Thi D', '0908765432', 'Hue', 'phamthid', 'password4', NULL, NULL),
(5, 'Hoang Van E', '0934567890', 'Haiphong', 'hoangvane', 'password5', NULL, NULL),
(6, 'dat', '123456789', 'abc', 'test', 'test', 'Nguyen Van F', '2024-05-30 11:57:29');

-- --------------------------------------------------------

--
-- Table structure for table `nhanvien`
--

CREATE TABLE `nhanvien` (
  `ID_NhanVien` int NOT NULL,
  `TenNhanVien` varchar(100) NOT NULL,
  `SoDienThoai` varchar(20) NOT NULL,
  `DiaChi` varchar(255) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `nhanvien`
--

INSERT INTO `nhanvien` (`ID_NhanVien`, `TenNhanVien`, `SoDienThoai`, `DiaChi`, `Username`, `Password`) VALUES
(1, 'Nguyen Van F', '0123456781', 'Hanoi', 'nguyenvanf', 'password6'),
(2, 'Tran Thi G', '0987654322', 'Saigon', 'tranthig', 'password7'),
(3, 'Le Van H', '0912345679', 'Danang', 'levanh', 'password8'),
(4, 'Pham Thi I', '0908765433', 'Hue', 'phamthii', 'password9'),
(5, 'Hoang Van J', '0934567891', 'Haiphong', 'hoangvanj', 'password10');

-- --------------------------------------------------------

--
-- Table structure for table `thongtinbanhang`
--

CREATE TABLE `thongtinbanhang` (
  `ID_ThongTinBanHang` int NOT NULL,
  `ID_KhachHang` int DEFAULT NULL,
  `ID_GoiDichVu` int DEFAULT NULL,
  `ID_TTNVBH` int DEFAULT NULL,
  `NgayDangKy` date NOT NULL,
  `SoLuong` int DEFAULT NULL,
  `nguoisua` varchar(255) DEFAULT NULL,
  `ngaysua` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `thongtinbanhang`
--

INSERT INTO `thongtinbanhang` (`ID_ThongTinBanHang`, `ID_KhachHang`, `ID_GoiDichVu`, `ID_TTNVBH`, `NgayDangKy`, `SoLuong`, `nguoisua`, `ngaysua`) VALUES
(7, 2, 3, 2, '2024-01-07', 1, NULL, NULL),
(8, 3, 4, 3, '2024-01-08', 1, NULL, NULL),
(9, 4, 5, 4, '2024-01-09', 1, NULL, NULL),
(10, 5, 1, 5, '2024-01-10', 1, NULL, NULL),
(13, 4, 2, 5, '2024-05-20', 2, NULL, NULL),
(14, 6, 4, 8, '2024-05-22', 5, NULL, NULL),
(16, 2, 5, 9, '2024-05-24', 1, NULL, NULL),
(17, 6, 2, 5, '2024-05-25', 1, 'Nguyen Van F', '2024-05-30 11:43:13'),
(18, 6, 1, 3, '2024-05-30', 1, 'Nguyen Van F', '2024-05-30 11:51:51'),
(19, 4, 2, 11, '2024-05-30', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ttnhanvienbanhang`
--

CREATE TABLE `ttnhanvienbanhang` (
  `ID_TTNVBH` int NOT NULL,
  `TenNhanVien` varchar(100) NOT NULL,
  `SoDienThoai` varchar(20) NOT NULL,
  `DiaChi` varchar(255) NOT NULL,
  `ID_NhanVien` int DEFAULT NULL,
  `nguoisua` varchar(255) DEFAULT NULL,
  `ngaysua` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `ttnhanvienbanhang`
--

INSERT INTO `ttnhanvienbanhang` (`ID_TTNVBH`, `TenNhanVien`, `SoDienThoai`, `DiaChi`, `ID_NhanVien`, `nguoisua`, `ngaysua`) VALUES
(1, 'Nguyen Van F', '0123456781', 'Hanoi', 1, NULL, NULL),
(2, 'Tran Thi G', '0987654322', 'Saigon', 2, NULL, NULL),
(3, 'Le Van H', '0912345679', 'Danang', 3, NULL, NULL),
(4, 'Pham Thi I', '0908765433', 'Hue', 4, NULL, NULL),
(5, 'Hoang Van J', '0934567895', 'Haiphong', 5, NULL, NULL),
(6, 'Nguyen Van 2', '0123456788', 'Hanoi', 1, NULL, NULL),
(8, 'Le Van 7', '0912345671', 'Danang', 3, NULL, NULL),
(9, 'Pham Thi hu', '0908765437', 'Binhphuoc', 2, NULL, NULL),
(10, 'Hoang Van L', '0934567894', 'Haiphong', 1, NULL, NULL),
(11, 'test 123', '123456789', '1233', 1, 'Nguyen Van F', '2024-05-30 12:05:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dichvu`
--
ALTER TABLE `dichvu`
  ADD PRIMARY KEY (`ID_DichVu`);

--
-- Indexes for table `doanhthu`
--
ALTER TABLE `doanhthu`
  ADD PRIMARY KEY (`ID_DoanhThu`),
  ADD KEY `ID_ThongTinBanHang` (`ID_ThongTinBanHang`);

--
-- Indexes for table `goidichvu`
--
ALTER TABLE `goidichvu`
  ADD PRIMARY KEY (`ID_GoiDichVu`),
  ADD KEY `ID_DichVu` (`ID_DichVu`);

--
-- Indexes for table `khachhang`
--
ALTER TABLE `khachhang`
  ADD PRIMARY KEY (`ID_KhachHang`);

--
-- Indexes for table `nhanvien`
--
ALTER TABLE `nhanvien`
  ADD PRIMARY KEY (`ID_NhanVien`);

--
-- Indexes for table `thongtinbanhang`
--
ALTER TABLE `thongtinbanhang`
  ADD PRIMARY KEY (`ID_ThongTinBanHang`),
  ADD KEY `ID_KhachHang` (`ID_KhachHang`),
  ADD KEY `ID_GoiDichVu` (`ID_GoiDichVu`),
  ADD KEY `ID_TTNVBH` (`ID_TTNVBH`);

--
-- Indexes for table `ttnhanvienbanhang`
--
ALTER TABLE `ttnhanvienbanhang`
  ADD PRIMARY KEY (`ID_TTNVBH`),
  ADD KEY `ID_NhanVien` (`ID_NhanVien`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dichvu`
--
ALTER TABLE `dichvu`
  MODIFY `ID_DichVu` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `doanhthu`
--
ALTER TABLE `doanhthu`
  MODIFY `ID_DoanhThu` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `goidichvu`
--
ALTER TABLE `goidichvu`
  MODIFY `ID_GoiDichVu` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `khachhang`
--
ALTER TABLE `khachhang`
  MODIFY `ID_KhachHang` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `nhanvien`
--
ALTER TABLE `nhanvien`
  MODIFY `ID_NhanVien` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `thongtinbanhang`
--
ALTER TABLE `thongtinbanhang`
  MODIFY `ID_ThongTinBanHang` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `ttnhanvienbanhang`
--
ALTER TABLE `ttnhanvienbanhang`
  MODIFY `ID_TTNVBH` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `doanhthu`
--
ALTER TABLE `doanhthu`
  ADD CONSTRAINT `doanhthu_ibfk_1` FOREIGN KEY (`ID_ThongTinBanHang`) REFERENCES `thongtinbanhang` (`ID_ThongTinBanHang`);

--
-- Constraints for table `goidichvu`
--
ALTER TABLE `goidichvu`
  ADD CONSTRAINT `goidichvu_ibfk_1` FOREIGN KEY (`ID_DichVu`) REFERENCES `dichvu` (`ID_DichVu`);

--
-- Constraints for table `thongtinbanhang`
--
ALTER TABLE `thongtinbanhang`
  ADD CONSTRAINT `thongtinbanhang_ibfk_1` FOREIGN KEY (`ID_KhachHang`) REFERENCES `khachhang` (`ID_KhachHang`),
  ADD CONSTRAINT `thongtinbanhang_ibfk_2` FOREIGN KEY (`ID_GoiDichVu`) REFERENCES `goidichvu` (`ID_GoiDichVu`),
  ADD CONSTRAINT `thongtinbanhang_ibfk_3` FOREIGN KEY (`ID_TTNVBH`) REFERENCES `ttnhanvienbanhang` (`ID_TTNVBH`);

--
-- Constraints for table `ttnhanvienbanhang`
--
ALTER TABLE `ttnhanvienbanhang`
  ADD CONSTRAINT `ttnhanvienbanhang_ibfk_1` FOREIGN KEY (`ID_NhanVien`) REFERENCES `nhanvien` (`ID_NhanVien`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
