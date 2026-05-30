<?php
session_start();
require_once('connect.php');
require_once __DIR__ . '/../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

// Get current academic year from settings
$settings_query = mysqli_query($conn, "SELECT academic_year FROM settings WHERE is_current = 1") 
    or die(mysqli_error($conn));
$settings_row = mysqli_fetch_array($settings_query);
$current_academic_year = $settings_row['academic_year'];

// Query voted voters
$qryreport = mysqli_query($conn, 
    "SELECT DISTINCT v.StudentID, v.FirstName, v.LastName, v.MiddleName, v.Year
     FROM voters v
     INNER JOIN votes vt ON v.StudentID = vt.voter_id
     WHERE v.academic_year = '$current_academic_year'
       AND vt.academic_year = '$current_academic_year'
       AND v.Enrollment = 'Enrolled'
     ORDER BY v.Year, v.LastName, v.FirstName") 
or die(mysqli_error($conn));

// Create spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$logoPath = __DIR__ . '/images/ucv.png';

// Insert logo in A1 and university name in B1, merge B1:F1
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
    $sheet->mergeCells('B1:F1');
    $sheet->getStyle('B1')->getFont()->setBold(true)->setSize(14);
    $sheet->getStyle('B1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
    $sheet->getRowDimension('1')->setRowHeight(42);
} else {
    $sheet->setCellValue('A1', 'UNIVERSITY OF CAGAYAN VALLEY');
    $sheet->mergeCells('A1:F1');
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
}

// Report headers
$sheet->setCellValue('A2', 'College of Information Technology');
$sheet->mergeCells('A2:F2');
$sheet->setCellValue('A3', 'Voted Voters List');
$sheet->mergeCells('A3:F3');
$sheet->setCellValue('A4', 'Academic Year: ' . $current_academic_year);
$sheet->mergeCells('A4:F4');
$sheet->setCellValue('A5', 'Generated on: ' . date('F d, Y h:i A'));
$sheet->mergeCells('A5:F5');

// Style headers
$sheet->getStyle('A2:A3')->getFont()->setBold(true)->setSize(12);
$sheet->getStyle('A1:F5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

// Table headers
$headerRow = 7;
$sheet->setCellValue('A' . $headerRow, 'Student ID')
      ->setCellValue('B' . $headerRow, 'First Name')
      ->setCellValue('C' . $headerRow, 'Last Name')
      ->setCellValue('D' . $headerRow, 'Middle Name')
      ->setCellValue('E' . $headerRow, 'Year')
      ->setCellValue('F' . $headerRow, 'Status');

// Style table headers - Maroon background, white text
$sheet->getStyle('A' . $headerRow . ':F' . $headerRow)->getFont()->setBold(true);
$sheet->getStyle('A' . $headerRow . ':F' . $headerRow)->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('FF800000'); // Maroon
$sheet->getStyle('A' . $headerRow . ':F' . $headerRow)->getFont()->getColor()->setARGB('FFFFFFFF'); // White text
$sheet->getStyle('A' . $headerRow . ':F' . $headerRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

// Table data
$row = $headerRow + 1;
$counter = 0;
while ($reportdisp = mysqli_fetch_array($qryreport)) {
    $counter++;
    $sheet->setCellValue('A' . $row, $reportdisp['StudentID'])
          ->setCellValue('B' . $row, $reportdisp['FirstName'])
          ->setCellValue('C' . $row, $reportdisp['LastName'])
          ->setCellValue('D' . $row, $reportdisp['MiddleName'])
          ->setCellValue('E' . $row, $reportdisp['Year'])
          ->setCellValue('F' . $row, 'Voted');
    $row++;
}

// Add total count row
$totalRow = $row;
$sheet->setCellValue('A' . $totalRow, 'TOTAL VOTED VOTERS');
$sheet->mergeCells('A' . $totalRow . ':E' . $totalRow);
$sheet->setCellValue('F' . $totalRow, $counter);

// Style total row - Yellow background, bold
$sheet->getStyle('A' . $totalRow . ':F' . $totalRow)->getFont()->setBold(true);
$sheet->getStyle('A' . $totalRow . ':F' . $totalRow)->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('FFFFFF00'); // Yellow
$sheet->getStyle('A' . $totalRow . ':F' . $totalRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

// Add borders to all data cells
$lastDataRow = $totalRow;
$sheet->getStyle('A' . $headerRow . ':F' . $lastDataRow)
    ->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

// Autosize columns
foreach (range('A', 'F') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Output as Excel file
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Voted_Voters_List_' . date('Y-m-d') . '.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>