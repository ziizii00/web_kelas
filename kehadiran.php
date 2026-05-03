<?php
session_start();
if(!isset($_SESSION['username'])) header("location:login.php");
include 'koneksi.php';
$role = $_SESSION['role'];

// Proses Simpan Data Kehadiran
if(isset($_POST['add'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_mhs']);
    $matkul = mysqli_real_escape_string($conn, $_POST['matkul']);
    $pertemuan = mysqli_real_escape_string($conn, $_POST['pertemuan']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    // Query dengan kolom baru: matkul dan pertemuan
    $sql = "INSERT INTO kehadiran (nama, matkul, pertemuan, status, tanggal) 
            VALUES ('$nama', '$matkul', '$pertemuan', '$status', CURDATE())";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Presensi Berhasil!'); window.location='kehadiran.php';</script>";
    } else {
        die("Error Simpan: " . mysqli_error($conn));
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kehadiran - TI 2B</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        .main-grid { display: grid; gap: 30px; align-items: start; margin-top: -40px; }
        .card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 20px; padding: 30px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .card-header { color: #1e293b; font-size: 1.1rem; font-weight: 700; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 2px solid #f1f5f9; text-transform: uppercase; }
        form select, form input { width: 100%; padding: 12px; margin-bottom: 12px; border: 1.5px solid #e2e8f0; border-radius: 10px; box-sizing: border-box; }
        .btn-submit { width: 100%; padding: 14px; background: #203052; color: white; border: none; border-radius: 12px; font-weight: 600; cursor: pointer; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #f8fafc; color: #64748b; font-size: 0.7rem; text-transform: uppercase; padding: 12px; text-align: left; }
        td { padding: 12px; border-bottom: 1px solid #f1f5f9; font-size: 0.9rem; }
        .badge { padding: 4px 10px; border-radius: 6px; font-size: 0.7rem; font-weight: 700; }
        .badge-hadir { background: #dcfce7; color: #166534; }
        .badge-izin { background: #fef9c3; color: #854d0e; }
        .badge-alpha { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-links">
            <a href="dashboard.php">Home</a>
            <a href="mahasiswa.php">Mahasiswa</a>
            <a href="dosen.php">Dosen</a>
            <a href="jadwal.php">Jadwal</a>
            <a href="kehadiran.php">Kehadiran</a>
            <a href="Tugas.php">Tugas</a>
            <a href="logout.php" style="color:red; font-weight: bold;">Logout</a>
        </div>
    </nav>

    <header class="hero-banner">
        <div class="hero-info">
            <h1>PRESENSI TI 2B</h1>
            <p>Input kehadiran berdasarkan mata kuliah dan pertemuan.</p>
        </div>
    </header>

    <main class="container main-grid" style="grid-template-columns: <?php echo ($role!='anggota') ? '1fr 2.5fr' : '1fr'; ?>;">
        
        <?php if($role!='anggota'): ?>
        <div class="card">
            <div class="card-header">Input Data</div>
            <form method="POST">
                <label style="font-size: 0.8rem; font-weight: 600;">Mata Kuliah:</label>
                <select name="matkul" required>
                    <?php 
                    // Ambil daftar matkul unik dari tabel dosen[cite: 1]
                    $mk = mysqli_query($conn, "SELECT DISTINCT mata_kuliah FROM dosen ORDER BY mata_kuliah ASC");
                    while($dmk = mysqli_fetch_assoc($mk)) {
                        echo "<option value='".$dmk['mata_kuliah']."'>".$dmk['mata_kuliah']."</option>";
                    }
                    ?>
                </select>

                <label style="font-size: 0.8rem; font-weight: 600;">Pertemuan Ke:</label>
                <input type="number" name="pertemuan" min="1" max="16" placeholder="Contoh: 1" required>

                <label style="font-size: 0.8rem; font-weight: 600;">Nama Mahasiswa:</label>
                <select name="nama_mhs" required>
                    <option value="" disabled selected>Pilih Mahasiswa</option>
                    <?php 
                    $mhs = mysqli_query($conn, "SELECT nama FROM mahasiswa WHERE nama != '' ORDER BY nama ASC");
                    while($dm = mysqli_fetch_assoc($mhs)) {
                        echo "<option value='".$dm['nama']."'>".$dm['nama']."</option>";
                    }
                    ?>
                </select>
                
                <label style="font-size: 0.8rem; font-weight: 600;">Status:</label>
                <select name="status" required>
                    <option value="Hadir">Hadir</option>
                    <option value="Izin">Izin</option>
                    <option value="Alpha">Alpha</option>
                </select>
                
                <button type="submit" name="add" class="btn-submit">Simpan Presensi</button>
            </form>
        </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">Riwayat Kehadiran</div>
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Tgl</th>
                            <th>Matkul</th>
                            <th>Ptn</th>
                            <th>Nama</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $q = mysqli_query($conn, "SELECT * FROM kehadiran ORDER BY id DESC LIMIT 20"); 
                        while($r = mysqli_fetch_assoc($q)): 
                        ?>
                        <tr>
                            <td style="font-size: 0.8rem; color: #64748b;"><?= date('d/m', strtotime($r['tanggal'])) ?></td>
                            <td style="font-weight: 500; font-size: 0.85rem;"><?= $r['matkul'] ?></td>
                            <td style="text-align: center;"><?= $r['pertemuan'] ?></td>
                            <td style="font-weight: 600;"><?= $r['nama'] ?></td>
                            <td><span class="badge badge-<?= strtolower($r['status']) ?>"><?= $r['status'] ?></span></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>