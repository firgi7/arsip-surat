<!DOCTYPE html>
<html>
<head><title>Login Admin</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<?php
include 'db.php';
if (!isset($_SESSION['login'])) header("Location: login.php");

$nomor = $_POST['nomor'];
$tanggal = $_POST['tanggal'];
$jenis = $_POST['jenis'];
$pengirim = $_POST['pengirim'];
$perihal = $_POST['perihal'];
$filename = $_FILES['file']['name'];
$tmp = $_FILES['file']['tmp_name'];

if (move_uploaded_file($tmp, "uploads/" . $filename)) {
    $stmt = $conn->prepare("INSERT INTO surat (nomor, tanggal, jenis, pengirim, perihal, file) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $nomor, $tanggal, $jenis, $pengirim, $perihal, $filename);
    $stmt->execute();
    echo "Upload berhasil! <a href='index.php'>Kembali</a>";
} else {
    echo "Upload gagal!";
}
?>
