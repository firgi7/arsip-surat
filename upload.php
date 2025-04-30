<?php
session_start();
include 'db.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nomor      = $_POST['nomor'];
    $tanggal    = $_POST['tanggal'];
    $jenis      = $_POST['jenis'];
    $pengirim   = $_POST['pengirim'];
    $perihal    = $_POST['perihal'];
    $departemen = $_POST['departemen'];

    $file_name = $_FILES['file']['name'];
    $file_tmp  = $_FILES['file']['tmp_name'];
    $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $upload_dir = 'upload/';
    $new_file_name = uniqid() . '.' . $file_ext;

    // Validasi file
    if ($file_ext != 'pdf') {
        $error = "File harus dalam format PDF.";
    } else {
        if (move_uploaded_file($file_tmp, $upload_dir . $new_file_name)) {
            $stmt = $conn->prepare("INSERT INTO surat (nomor, tanggal, jenis, pengirim, perihal, departemen, file) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $nomor, $tanggal, $jenis, $pengirim, $perihal, $departemen, $new_file_name);
            $stmt->execute();

            header("Location: index.php");
            exit();
        } else {
            $error = "Gagal mengupload file.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Surat</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f4f8;
            padding: 50px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        h2 {
            margin-bottom: 20px;
        }

        form input[type="text"],
        form input[type="date"],
        form select,
        form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        form input[type="file"] {
            margin-bottom: 15px;
        }

        form button {
            background: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .error {
            background: #fdd;
            padding: 10px;
            margin-bottom: 15px;
            color: red;
            border: 1px solid red;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #007bff;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Upload Surat</h2>

    <?php if (isset($error)): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <label>Nomor Surat</label>
        <input type="text" name="nomor" required>

        <label>Tanggal</label>
        <input type="date" name="tanggal" required>

        <label>Jenis Surat</label>
<select name="jenis" required>
    <option value="Surat Masuk">Surat Masuk</option>
    <option value="Surat Umum/Keluar">Surat Umum/Keluar</option>
    <option value="Surat Rekomendasi">Surat Rekomendasi</option>
    <option value="Surat Mandat">Surat Mandat</option>
    <option value="Surat Keterangan Kader">Surat Keterangan Kader</option>
</select>

<label>Departemen</label>
<select name="departemen" required>
    <option value="Sekum">Sekum</option>
    <option value="Bidang Organisasi">Bidang Organisasi</option>
    <option value="Bidang Kaderisasi">Bidang Kaderisasi</option>
    <option value="Bidang RPK">Bidang RPK</option>
    <option value="Bidang TKI">Bidang TKI</option>
    <option value="Bidang SPM">Bidang SPM</option>
    <option value="Bidang IMMawati">Bidang IMMawati</option>
    <option value="Bidang Hikmah">Bidang Hikmah</option>
    <option value="Bidang EKW">Bidang EKW</option>
    <option value="Bidang MEDKOM">Bidang MEDKOM</option>
    <option value="Bidang SBO">Bidang SBO</option>
</select>

        <label>Pengirim</label>
        <input type="text" name="pengirim" required>

        <label>Perihal</label>
        <textarea name="perihal" rows="3" required></textarea>

        <label>File (PDF)</label>
        <input type="file" name="file" accept="application/pdf" required>

        <button type="submit">Upload</button>
    </form>

    <a href="index.php" class="back-link">← Kembali ke Dashboard</a>
</div>
</body>
</html>
