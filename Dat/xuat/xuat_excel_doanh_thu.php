<?php
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (isset($_POST['data']) && isset($_POST['h2Content'])) {
    $data = json_decode($_POST['data'], true);
    $h2Content = $_POST['h2Content'];

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set the header (H2 content) and make it bold
    $sheet->setCellValue('A1', $h2Content);
    $sheet->mergeCells('A1:D1');
    $sheet->getStyle('A1')->getFont()->setBold(true);

    // Set headers for the columns
    $headers = ['TenGoiDichVu', 'GiaTien', 'TongSoLuong', 'ThanhTien'];
    $sheet->fromArray($headers, NULL, 'A2');

    // Set data starting from row 3
    $sheet->fromArray($data, NULL, 'A3');

    // Set number format for 'TongSoLuong' and 'ThanhTien' columns
    $sheet->getStyle('C3:C' . (count($data) + 2))
        ->getNumberFormat()
        ->setFormatCode('#,##0.000');
    $sheet->getStyle('E3:E' . (count($data) + 2))
        ->getNumberFormat()
        ->setFormatCode('#,##0.000');

    // Calculate total
    $totalRow = count($data) + 3; // Data starts at row 3
    $sheet->setCellValue('D' . $totalRow, 'Tổng tiền:');
    $sheet->setCellValue('E' . $totalRow, '=SUM(E3:E' . ($totalRow - 1) . ')');

    // Set number format for total row
    $sheet->getStyle('E' . $totalRow)
        ->getNumberFormat()
        ->setFormatCode('#,##0.000');

    // Set headers to be bold
    $headerStyle = [
        'font' => [
            'bold' => true,
        ],
    ];
    $sheet->getStyle('A2:D2')->applyFromArray($headerStyle);
    $sheet->getStyle('D' . $totalRow . ':E' . $totalRow)->applyFromArray($headerStyle);

    // Set Content-Type and file name for download
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="doanh_thu.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}
