<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Unauthorized access.");
}

require 'vendor/autoload.php'; 

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

$host = "localhost";
$dbname = "form_db";
$username = "root";
$password = "";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT name, phone, email, cname FROM contacts";
$result = $conn->query($sql);
if (!$result) {
    die("Database query failed: " . $conn->error);
}

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle("Contacts");

$sheet->setCellValue('A1', 'Name');
$sheet->setCellValue('B1', 'Phone');
$sheet->setCellValue('C1', 'Email');
$sheet->setCellValue('D1', 'Company');

$sheet->getStyle('A1:D1')->getFont()->setBold(true);
$sheet->getStyle('A1:D1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

$row = 2;
while ($data = $result->fetch_assoc()) {
    $sheet->setCellValue("A$row", $data['name']);
    $sheet->setCellValue("B$row", $data['phone']);
    $sheet->setCellValue("C$row", $data['email']);
    $sheet->setCellValue("D$row", $data['cname']);
    $row++;
}

$sheet->getStyle("A1:D" . ($row - 1))
    ->getBorders()
    ->getAllBorders()
    ->setBorderStyle(Border::BORDER_THIN);

foreach (range('A', 'D') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="contacts.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

$conn->close();
exit;
?>
