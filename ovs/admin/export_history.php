<?php
session_start();
require_once('connect.php');
require_once __DIR__ . '/../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

// Get current academic year
$current_year = $_SESSION['academic_year'];

// Query history log - Simple query without JOIN
$query = mysqli_query($conn, "SELECT * FROM history 
    WHERE academic_year = '$current_year' 
    ORDER BY date DESC") 
or die(mysqli_error($conn));

// Create spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$logoPath = __DIR__ . '/images/ucv.png';

// Insert logo in A1 and university name in B1, merge B1:C1
if (file_exists($logoPath)) {
    $drawing = new Drawing();
    $drawing->setName('UCV Logo');
    $drawing->setDescription('UCV Logo');
    $drawing->setPath($logoPath);
    $drawing->setHeight(40);
    $drawing->setCoordinates('A1');
    $drawing->setOffsetY(2);
    $drawing->setWorksheet($sheet);
    
    $sheet->setCellValue('B1', 'UNIVERSITY OF CAGAYAN VALLEY');
    $sheet->mergeCells('B1:C1');
    $sheet->getStyle('B1')->getFont()->setBold(true)->setSize(14);
    $sheet->getStyle('B1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
    $sheet->getRowDimension('1')->setRowHeight(42);
} else {
    $sheet->setCellValue('A1', 'UNIVERSITY OF CAGAYAN VALLEY');
    $sheet->mergeCells('A1:C1');
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
}

// Report headers
$sheet->setCellValue('A2', 'College of Information Technology');
$sheet->mergeCells('A2:C2');
$sheet->setCellValue('A3', 'History Log Report');
$sheet->mergeCells('A3:C3');
$sheet->setCellValue('A4', 'Academic Year: ' . $current_year);
$sheet->mergeCells('A4:C4');
$sheet->setCellValue('A5', 'Generated on: ' . date('F d, Y h:i A'));
$sheet->mergeCells('A5:C5');

// Style headers
$sheet->getStyle('A2:A3')->getFont()->setBold(true)->setSize(12);
$sheet->getStyle('A1:C5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

// Table headers
$headerRow = 7;
$sheet->setCellValue('A' . $headerRow, 'Date')
      ->setCellValue('B' . $headerRow, 'Action')
      ->setCellValue('C' . $headerRow, 'Data');

// Style table headers - Maroon background, white text
$sheet->getStyle('A' . $headerRow . ':C' . $headerRow)->getFont()->setBold(true);
$sheet->getStyle('A' . $headerRow . ':C' . $headerRow)->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('FF800000'); // Maroon
$sheet->getStyle('A' . $headerRow . ':C' . $headerRow)->getFont()->getColor()->setARGB('FFFFFFFF'); // White text
$sheet->getStyle('A' . $headerRow . ':C' . $headerRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

// Table data
$row = $headerRow + 1;
$counter = 0;
while ($data = mysqli_fetch_array($query)) {
    $counter++;
    $sheet->setCellValue('A' . $row, date('M d, Y h:i A', strtotime($data['date'])))
          ->setCellValue('B' . $row, $data['action'])
          ->setCellValue('C' . $row, $data['data']);
    $row++;
}

// Add total count row
$totalRow = $row;
$sheet->setCellValue('A' . $totalRow, 'TOTAL RECORDS');
$sheet->mergeCells('A' . $totalRow . ':B' . $totalRow);
$sheet->setCellValue('C' . $totalRow, $counter);

// Style total row - Yellow background, bold
$sheet->getStyle('A' . $totalRow . ':C' . $totalRow)->getFont()->setBold(true);
$sheet->getStyle('A' . $totalRow . ':C' . $totalRow)->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('FFFFFF00'); // Yellow
$sheet->getStyle('A' . $totalRow . ':C' . $totalRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

// Add borders to all data cells
$lastDataRow = $totalRow;
$sheet->getStyle('A' . $headerRow . ':C' . $lastDataRow)
    ->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

// Autosize columns
foreach (range('A', 'C') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Make Date column wider for better readability
$sheet->getColumnDimension('A')->setWidth(20);
$sheet->getColumnDimension('C')->setWidth(40); // Data column wider

// Output as Excel file
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="History_Log_' . date('Y-m-d') . '.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>