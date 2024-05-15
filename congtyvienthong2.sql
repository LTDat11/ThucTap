
CREATE TABLE KhachHang (
    ID INT PRIMARY KEY AUTO_INCREMENT,
    HoTen VARCHAR(255),
    SDT VARCHAR(15),
    DiaChi VARCHAR(255)
);

CREATE TABLE DichVu (
    ID INT PRIMARY KEY AUTO_INCREMENT,
    TenDichVu VARCHAR(255)
);

CREATE TABLE GoiCuoc (
    ID INT PRIMARY KEY AUTO_INCREMENT,
    DichVuID INT,
    TenGoiCuoc VARCHAR(255),
    TocDo VARCHAR(255),
    GiaTien DECIMAL(10, 2),
    FOREIGN KEY (DichVuID) REFERENCES DichVu(ID)
);

CREATE TABLE NhanVien (
    ID INT PRIMARY KEY AUTO_INCREMENT,
    Ten VARCHAR(255),
    SDT VARCHAR(15),
    DiaChi VARCHAR(255)
);

CREATE TABLE DangKy (
    KhachHangID INT,
    GoiCuocID INT,
    NhanVienID INT,
    NgayDangKy DATE,
    FOREIGN KEY (KhachHangID) REFERENCES KhachHang(ID),
    FOREIGN KEY (GoiCuocID) REFERENCES GoiCuoc(ID),
    FOREIGN KEY (NhanVienID) REFERENCES NhanVien(ID)
);

CREATE TABLE ThongKeNhanVien (
    NhanVienID INT,
    SoLuongDichVuBanDuoc INT,
    FOREIGN KEY (NhanVienID) REFERENCES NhanVien(ID)
);

CREATE TABLE ThongKeKhachHang (
    KhachHangID INT,
    SoLuongDichVuSuDung INT,
    FOREIGN KEY (KhachHangID) REFERENCES KhachHang(ID)
);

CREATE TABLE ThongKeDichVu (
    DichVuID INT,
    SoLuongBanDuoc INT,
    DoanhThu DECIMAL(10, 2),
    FOREIGN KEY (DichVuID) REFERENCES DichVu(ID)
);

CREATE TABLE TaiKhoanKhachHang (
    KhachHangID INT PRIMARY KEY,
    TenDangNhap VARCHAR(255),
    MatKhau VARCHAR(255),
    FOREIGN KEY (KhachHangID) REFERENCES KhachHang(ID)
);

CREATE TABLE TaiKhoanNhanVien (
    NhanVienID INT PRIMARY KEY,
    TenDangNhap VARCHAR(255),
    MatKhau VARCHAR(255),
    FOREIGN KEY (NhanVienID) REFERENCES NhanVien(ID)
);