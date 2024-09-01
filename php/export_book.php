<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require 'db.php';

// Ensure author is logged in and the book exists
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'author') {
    header("Location: ../index.php");
    exit;
}

// Include necessary files and use statements
require_once '../fpdf186/fpdf.php'; // Ensure the path is correct
require_once '../vendor/autoload.php'; // Ensure the path is correct

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

if (isset($_GET['book_id']) && isset($_GET['format'])) {
    $book_id = intval($_GET['book_id']);
    $format = $_GET['format'];

    // Fetch the book from the database
    $stmt = $conn->prepare("SELECT * FROM ebooks WHERE id = ? AND author_id = ?");
    $stmt->bind_param("ii", $book_id, $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $book = $result->fetch_assoc();
    $stmt->close();

    if ($book) {
        if ($format === 'pdf') {
            // PDF Export using FPDF
            $pdf = new FPDF();
            $pdf->AddPage();
            $pdf->SetFont('Arial', 'B', 26);
            $pdf->Cell(0, 10, $book['title'], 0, 1, 'C');
            $pdf->Ln(10);
            $pdf->SetFont('Arial', '', 20);
            $pdf->MultiCell(0, 16, $book['content']);

            // Clean output buffer and send PDF
            ob_clean(); // Clean the output buffer to avoid extra output
            $pdf->Output('D', $book['title'] . '.pdf'); // Output directly to browser
        } elseif ($format === 'word') {
            // Word Export using PHPWord
            $phpWord = new PhpWord();
            $section = $phpWord->addSection();
            $section->addTitle($book['title']);
            $section->addText($book['content']);

            $fileName = $book['title'] . ".docx";
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $fileName . '"');

            $writer = IOFactory::createWriter($phpWord, 'Word2007');
            $writer->save("php://output"); // Output the Word file
        } else {
            echo "Invalid format.";
        }
    } else {
        echo "Book not found.";
    }
} else {
    echo "Invalid request. Please ensure you provide both 'book_id' and 'format' parameters in the URL.";
}
?>
