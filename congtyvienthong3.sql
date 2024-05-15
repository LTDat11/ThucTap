CREATE DATABASE TelecomCompany;
USE TelecomCompany;

-- Bảng Khách hàng (Customers)
CREATE TABLE Customers (
    CustomerID INT AUTO_INCREMENT PRIMARY KEY,
    FullName VARCHAR(100) NOT NULL,
    PhoneNumber VARCHAR(20) NOT NULL,
    Address VARCHAR(255) NOT NULL,
    Email VARCHAR(100),
    Username VARCHAR(50) NOT NULL,
    Password VARCHAR(255) NOT NULL
);

-- Bảng Dịch vụ (Services)
CREATE TABLE Services (
    ServiceID INT AUTO_INCREMENT PRIMARY KEY,
    ServiceName VARCHAR(100) NOT NULL,
    Description TEXT
);

-- Bảng Gói cước dịch vụ (ServicePackages)
CREATE TABLE ServicePackages (
    PackageID INT AUTO_INCREMENT PRIMARY KEY,
    ServiceID INT,
    PackageName VARCHAR(100) NOT NULL,
    Speed VARCHAR(50),
    Price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (ServiceID) REFERENCES Services(ServiceID)
);

-- Bảng Nhân viên (Employees)
CREATE TABLE Employees (
    EmployeeID INT AUTO_INCREMENT PRIMARY KEY,
    FullName VARCHAR(100) NOT NULL,
    PhoneNumber VARCHAR(20) NOT NULL,
    Address VARCHAR(255) NOT NULL,
    Email VARCHAR(100),
    Username VARCHAR(50) NOT NULL,
    Password VARCHAR(255) NOT NULL
);

-- Bảng Hợp đồng (Contracts)
CREATE TABLE Contracts (
    ContractID INT AUTO_INCREMENT PRIMARY KEY,
    CustomerID INT,
    EmployeeID INT,
    PackageID INT,
    StartDate DATE NOT NULL,
    EndDate DATE,
    Status VARCHAR(20),
    FOREIGN KEY (CustomerID) REFERENCES Customers(CustomerID),
    FOREIGN KEY (EmployeeID) REFERENCES Employees(EmployeeID),
    FOREIGN KEY (PackageID) REFERENCES ServicePackages(PackageID)
);

-- Bảng Đăng nhập người dùng (UserLogins)
CREATE TABLE UserLogins (
    LoginID INT AUTO_INCREMENT PRIMARY KEY,
    UserID INT,
    UserType ENUM('Customer', 'Employee') NOT NULL,
    LoginTime DATETIME NOT NULL,
    LogoutTime DATETIME,
    FOREIGN KEY (UserID) REFERENCES Customers(CustomerID)
);

-- Bảng Bán hàng (Sales)
CREATE TABLE Sales (
    SaleID INT AUTO_INCREMENT PRIMARY KEY,
    EmployeeID INT,
    ContractID INT,
    SaleDate DATE NOT NULL,
    FOREIGN KEY (EmployeeID) REFERENCES Employees(EmployeeID),
    FOREIGN KEY (ContractID) REFERENCES Contracts(ContractID)
);

-- Bảng Doanh thu (Revenue)
CREATE TABLE Revenue (
    RevenueID INT AUTO_INCREMENT PRIMARY KEY,
    ServiceID INT,
    RevenueAmount DECIMAL(10, 2) NOT NULL,
    RevenueDate DATE NOT NULL,
    FOREIGN KEY (ServiceID) REFERENCES Services(ServiceID)
);

-- Sửa đổi bảng UserLogins để hỗ trợ cả khách hàng và nhân viên
ALTER TABLE UserLogins
ADD CONSTRAINT fk_user_customers FOREIGN KEY (UserID) REFERENCES Customers(CustomerID);

ALTER TABLE UserLogins
ADD CONSTRAINT fk_user_employees FOREIGN KEY (UserID) REFERENCES Employees(EmployeeID);
