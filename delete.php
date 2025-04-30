<?php
session_start();
include 'db.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'] ?? '';

if ($id == '') {
    header("Location: index.php");
    exit();
}

// Cari surat berdasarkan ID
$stmt = $conn->prepare("SELECT file FROM surat WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
    $file = $data['file'];
    $filePath = 'upload/' . $file;

    // Hapus file fisik jika ada
    if (!empty($file) && file_exists($filePath)) {
        unlink($filePath);
    }

    // Hapus data dari database
    $del = $conn->prepare("DELETE FROM surat WHERE id = ?");
    $del->bind_param("i", $id);
    $del->execute();
}

header("Location: index.php");
exit();
?>
