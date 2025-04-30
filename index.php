<?php
session_start();
include 'db.php';

// Cek login
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

// Proses pencarian dan filter
$search = $_GET['search'] ?? '';
$jenis = $_GET['jenis'] ?? '';
$departemen = $_GET['departemen'] ?? '';

$query = "SELECT * FROM surat WHERE 1=1";

if ($search != '') {
    $search = $conn->real_escape_string($search);
    $query .= " AND (perihal LIKE '%$search%' OR nomor LIKE '%$search%')";
}

if ($jenis != '') {
    $jenis = $conn->real_escape_string($jenis);
    $query .= " AND jenis = '$jenis'";
}

if ($departemen != '') {
    $departemen = $conn->real_escape_string($departemen);
    $query .= " AND departemen = '$departemen'";
}

$query .= " ORDER BY id DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Arsip Surat</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f4f8;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1000px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .navbar h2 {
            margin: 0;
        }

        .navbar a {
            text-decoration: none;
            margin-left: 10px;
            color: #007bff;
            font-weight: bold;
        }

        .filter-form {
            margin-bottom: 30px;
            display: flex;
            gap: 10px;
        }

        .filter-form input, .filter-form select {
            padding: 8px;
            font-size: 14px;
        }

        .filter-form button {
            padding: 8px 16px;
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }

        .folder-grid {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .folder-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 150px;
            height: 100px;
            background-color: #f8f9fa;
            border: 2px solid #007bff33;
            border-radius: 10px;
            text-align: center;
            text-decoration: none;
            color: #007bff;
            font-size: 16px;
            transition: 0.3s;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .folder-item:hover {
            background-color: #e9f3ff;
            border-color: #007bff;
            transform: translateY(-3px);
        }

        .folder-item span {
            margin-top: 8px;
            font-weight: 500;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background: #007bff;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="navbar">
            <h2>Dashboard Arsip Surat</h2>
            <div>
                <a href="logout.php">Logout</a>
                <a href="upload.php">Upload Surat</a>
            </div>
        </div>

        <form class="filter-form" method="get">
            <input type="text" name="search" placeholder="Cari perihal/nomor" value="<?= htmlspecialchars($search) ?>">
            <select name="jenis">
            <option value="">-- Semua Jenis --</option>
    <option value="Surat Masuk" <?= $jenis == 'Surat Masuk' ? 'selected' : '' ?>>Surat Masuk</option>
    <option value="Surat Umum/Keluar" <?= $jenis == 'Surat Umum/Keluar' ? 'selected' : '' ?>>Surat Umum/Keluar</option>
    <option value="Surat Rekomendasi" <?= $jenis == 'Surat Rekomendasi' ? 'selected' : '' ?>>Surat Rekomendasi</option>
    <option value="Surat Mandat" <?= $jenis == 'Surat Mandat' ? 'selected' : '' ?>>Surat Mandat</option>
    <option value="Surat Keterangan Kader" <?= $jenis == 'Surat Keterangan Kader' ? 'selected' : '' ?>>Surat Keterangan Kader</option>
</select>
            <button type="submit">Filter</button>
        </form>

        <!-- Folder shortcut per departemen -->
        <div class="folder-grid">
        <a href="index.php" class="folder-item">🗂️<span>Semua Surat</span></a>
    <a href="?departemen=Sekum" class="folder-item">📁<span>Sekum</span></a>
    <a href="?departemen=Bidang Organisasi" class="folder-item">🏢<span>Bidang Organisasi</span></a>
    <a href="?departemen=Bidang Kaderisasi" class="folder-item">👤<span>Bidang Kaderisasi</span></a>
    <a href="?departemen=Bidang RPK" class="folder-item">📚<span>Bidang RPK</span></a>
    <a href="?departemen=Bidang TKI" class="folder-item">🌍<span>Bidang TKI</span></a>
    <a href="?departemen=Bidang SPM" class="folder-item">📈<span>Bidang SPM</span></a>
    <a href="?departemen=Bidang IMMawati" class="folder-item">👩‍🎓<span>Bidang IMMawati</span></a>
    <a href="?departemen=Bidang Hikmah" class="folder-item">🧠<span>Bidang Hikmah</span></a>
    <a href="?departemen=Bidang EKW" class="folder-item">💼<span>Bidang EKW</span></a>
    <a href="?departemen=Bidang MEDKOM" class="folder-item">🗞️<span>Bidang MEDKOM</span></a>
    <a href="?departemen=Bidang SBO" class="folder-item">📷<span>Bidang SBO</span></a>
</div>

        <!-- Tabel data surat -->
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nomor</th>
                    <th>Tanggal</th>
                    <th>Jenis</th>
                    <th>Departemen</th>
                    <th>Pengirim</th>
                    <th>Perihal</th>
                    <th>File</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): 
                    $no = 1;
                    while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['nomor']) ?></td>
                        <td><?= htmlspecialchars($row['tanggal']) ?></td>
                        <td><?= htmlspecialchars($row['jenis']) ?></td>
                        <td><?= htmlspecialchars($row['departemen']) ?></td>
                        <td><?= htmlspecialchars($row['pengirim']) ?></td>
                        <td><?= htmlspecialchars($row['perihal']) ?></td>
                        <td><a href="upload/<?= htmlspecialchars($row['file']) ?>" target="_blank">Lihat</a></td>
                    
                        <td>
    <a href="edit.php?id=<?= $row['id'] ?>">Edit</a> |
    <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus surat ini?');" style="color:red;">Hapus</a>
</td>

                    </tr>
                <?php endwhile; else: ?>
                    <tr><td colspan="8">Data tidak ditemukan.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
