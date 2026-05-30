<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once('connect.php');
require_once('session.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

// Get current academic year
$academic_year = isset($_SESSION['academic_year']) ? $_SESSION['academic_year'] : '2024-2025';

// Determine position filter and title
$position_title = "All Positions";
$position_filter = "";
$filename = "all_positions_votes_report";

if(isset($_POST['position_filter'])) {
    switch($_POST['position_filter']) {
        case 'C_governor':
            $filename = "governor_votes_report";
            $position_title = "Governor";
            $position_filter = "AND c.Position='Governor'";
            break;
        case 'C_vice-governor':
            $filename = "vice_governor_votes_report";
            $position_title = "Vice-Governor";
            $position_filter = "AND c.Position='Vice-Governor'";
            break;
        case 'C_1st_year':
            $filename = "1st_year_representative_votes_report";
            $position_title = "1st Year Representative";
            $position_filter = "AND c.Position='1st Year Representative'";
            break;
        case 'C_2nd_year':
            $filename = "2nd_year_representative_votes_report";
            $position_title = "2nd Year Representative";
            $position_filter = "AND c.Position='2nd Year Representative'";
            break;
        case 'C_3rd_year':
            $filename = "3rd_year_representative_votes_report";
            $position_title = "3rd Year Representative";
            $position_filter = "AND c.Position='3rd Year Representative'";
            break;
        case 'C_4th_year':
            $filename = "4th_year_representative_votes_report";
            $position_title = "4th Year Representative";
            $position_filter = "AND c.Position='4th Year Representative'";
            break;
        default:
            $filename = "all_positions_votes_report";
            $position_title = "All Positions";
            $position_filter = "";
    }
}

// Query votes
$query = "SELECT c.*, 
    (SELECT COUNT(*) FROM votes v 
     WHERE v.CandidateID = c.CandidateID 
     AND v.academic_year = '$academic_year') as vote_count
    FROM candidate c 
    WHERE c.academic_year = '$academic_year' 
    $position_filter
    ORDER BY c.Position, vote_count DESC";

$result = mysqli_query($conn, $query) or die(mysqli_error($conn));

// Create spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$logoPath = __DIR__ . '/images/ucv.png';
$rowOffset = 1;

// Insert logo in A1 and university name in B1, merge B1:G1
if (file_exists($logoPath)) {
    $drawing = new Drawing();
    $drawing->setName('UCV Logo');
    $drawing->setDescription('UCV Logo');
    $drawing->setPath($logoPath);
    $drawing->setHeight(40); // px
    $drawing->setCoordinates('A1');
    $drawing->setOffsetY(2); // Optional: fine-tune vertical alignment
    $drawing->setWorksheet($sheet);
    $rowOffset = 1;
    $sheet->setCellValue('B1', 'UNIVERSITY OF CAGAYAN VALLEY');
    $sheet->mergeCells('B1:G1');
    // Style the university name
    $sheet->getStyle('B1')->getFont()->setBold(true)->setSize(14);
    $sheet->getStyle('B1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
    $sheet->getRowDimension('1')->setRowHeight(42); // Match logo height
} else {
    $sheet->setCellValue('A1', 'UNIVERSITY OF CAGAYAN VALLEY');
    $sheet->mergeCells('A1:G1');
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
    $rowOffset = 1;
}

// Continue with the rest of your headers
$sheet->setCellValue('A2', 'College of Information Technology');
$sheet->mergeCells('A2:G2');
$sheet->setCellValue('A3', 'Canvassing Report - ' . $position_title);
$sheet->mergeCells('A3:G3');
$sheet->setCellValue('A4', 'Academic Year: ' . $academic_year);
$sheet->mergeCells('A4:G4');
$sheet->setCellValue('A5', 'Generated on: ' . date('F d, Y h:i A'));
$sheet->mergeCells('A5:G5');

// Style the headers
$sheet->getStyle('A2:A3')->getFont()->setBold(true)->setSize(12);
$sheet->getStyle('A1:G5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

// Table headers
$headerRow = 7;
$sheet->setCellValue('A' . $headerRow, 'Position')
      ->setCellValue('B' . $headerRow, 'First Name')
      ->setCellValue('C' . $headerRow, 'Middle Name')
      ->setCellValue('D' . $headerRow, 'Last Name')
      ->setCellValue('E' . $headerRow, 'Year')
      ->setCellValue('F' . $headerRow, 'Party')
      ->setCellValue('G' . $headerRow, 'Votes');

// Style table headers
$sheet->getStyle('A' . $headerRow . ':G' . $headerRow)->getFont()->setBold(true);
$sheet->getStyle('A' . $headerRow . ':G' . $headerRow)->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('FF800000'); // Maroon
$sheet->getStyle('A' . $headerRow . ':G' . $headerRow)->getFont()->getColor()->setARGB('FFFFFFFF');
$sheet->getStyle('A' . $headerRow . ':G' . $headerRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

// Table data
$row = $headerRow + 1;
$total_votes = 0;

while ($data = mysqli_fetch_array($result)) {
    $sheet->setCellValue('A' . $row, $data['Position'])
          ->setCellValue('B' . $row, $data['FirstName'])
          ->setCellValue('C' . $row, $data['MiddleName'])
          ->setCellValue('D' . $row, $data['LastName'])
          ->setCellValue('E' . $row, $data['Year'])
          ->setCellValue('F' . $row, $data['Party'])
          ->setCellValue('G' . $row, $data['vote_count']);
    
    $total_votes += $data['vote_count'];
    $row++;
}

// Add total row
$totalRow = $row;
$sheet->setCellValue('A' . $totalRow, 'TOTAL VOTES');
$sheet->mergeCells('A' . $totalRow . ':F' . $totalRow);
$sheet->setCellValue('G' . $totalRow, $total_votes);

// Style total row
$sheet->getStyle('A' . $totalRow . ':G' . $totalRow)->getFont()->setBold(true);
$sheet->getStyle('A' . $totalRow . ':G' . $totalRow)->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('FFFFFF00'); // Yellow
$sheet->getStyle('A' . $totalRow . ':G' . $totalRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

// Add borders to all data cells
$lastDataRow = $totalRow;
$sheet->getStyle('A' . $headerRow . ':G' . $lastDataRow)
    ->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

// Autosize columns
foreach (range('A', 'G') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Output as Excel file
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '_' . date('Y-m-d') . '.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>