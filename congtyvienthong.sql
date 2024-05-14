CREATE TABLE KhachHang (
    ID_KhachHang INT PRIMARY KEY AUTO_INCREMENT,
    HoTen VARCHAR(255),
    SDT VARCHAR(15),
    DiaChi VARCHAR(255)
);

CREATE TABLE DichVu (
    ID_DichVu INT PRIMARY KEY AUTO_INCREMENT,
    TenDichVu VARCHAR(255)
);

CREATE TABLE GoiCuoc (
    ID_GoiCuoc INT PRIMARY KEY AUTO_INCREMENT,
    ID_DichVu INT,
    TocDo VARCHAR(255),
    GiaTien DECIMAL(10, 2),
    FOREIGN KEY (ID_DichVu) REFERENCES DichVu(ID_DichVu)
);

CREATE TABLE NhanVien (
    ID_NhanVien INT PRIMARY KEY AUTO_INCREMENT,
    HoTen VARCHAR(255),
    SDT VARCHAR(15),
    DiaChi VARCHAR(255)
);

CREATE TABLE DichVuDaDangKy (
    ID_DichVuDaDangKy INT PRIMARY KEY AUTO_INCREMENT,
    ID_KhachHang INT,
    ID_GoiCuoc INT,
    NgayDangKy DATE,
    FOREIGN KEY (ID_KhachHang) REFERENCES KhachHang(ID_KhachHang),
    FOREIGN KEY (ID_GoiCuoc) REFERENCES GoiCuoc(ID_GoiCuoc)
);

CREATE TABLE NhanVienBanHang (
    ID_NhanVienBanHang INT PRIMARY KEY AUTO_INCREMENT,
    ID_NhanVien INT,
    ID_DichVuDaDangKy INT,
    FOREIGN KEY (ID_NhanVien) REFERENCES NhanVien(ID_NhanVien),
    FOREIGN KEY (ID_DichVuDaDangKy) REFERENCES DichVuDaDangKy(ID_DichVuDaDangKy)
);

CREATE TABLE DoanhThu (
    ID_DoanhThu INT PRIMARY KEY AUTO_INCREMENT,
    ID_DichVu INT,
    ThoiGian DATE,
    SoTien DECIMAL(10, 2),
    FOREIGN KEY (ID_DichVu) REFERENCES DichVu(ID_DichVu)
);

CREATE TABLE TaiKhoanDangNhap ( 
  ID_TaiKhoanDangNhap INT PRIMARY KEY AUTO_INCREMENT, 
  TenDangNhap VARCHAR(255), 
  MatKhau VARCHAR(255), 
  LoaiTaiKhoan BOOLEAN DEFAULT 0,
  ID_KhachHang INT, 
  ID_NhanVien INT, 
  FOREIGN KEY (ID_KhachHang) REFERENCES KhachHang(ID_KhachHang), 
  FOREIGN KEY (ID_NhanVien) REFERENCES NhanVien(ID_NhanVien) 
);