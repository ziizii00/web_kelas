<?php
session_start();
// Cek apakah sudah login
if(!isset($_SESSION['username'])) header("location:login.php");
include 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard TI 2B</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Navigasi Atas Sesuai Gambar Referensi -->
    <nav class="navbar">
        <div class="nav-links">
            <a href="dashboard.php">Home</a>
            <a href="mahasiswa.php">Mahasiswa</a>
            <a href="dosen.php">Dosen</a>
            <a href="jadwal.php">Jadwal</a>
            <a href="kehadiran.php">Kehadiran</a>
            <a href="tugas.php">Tugas</a>
            <a href="logout.php" style="color: #ef4444;">Logout</a>
        </div>
    </nav>

    <!-- Hero Banner Biru Profesional -->
    <header class="hero-banner">
        <div class="hero-info">
            <p>Sistem Informasi Manajemen Kelas</p>
            <h1>KELAS TI 2B - POLITEKNIK PURBAYA</h1>
            <p>Selamat Datang, <strong><?php echo $_SESSION['username']; ?></strong> (<?php echo strtoupper($_SESSION['role']); ?>)</p>
        </div>
        <div class="hero-contact">
            <p>Kontak Darurat Sipen:</p>
            <h3>087778037311</h3>
        </div>
    </header>

    <!-- Grid Layout 3 Kolom (Otomatis Realtime) -->
<main class="container grid-dashboard">
    
    <!-- Kolom 1: Jadwal (Tetap seperti kode kamu) -->
    <section class="card">
        <div class="card-header">Jadwal Kuliah</div>
        <?php 
        $query_jadwal = mysqli_query($conn, "SELECT * FROM jadwal LIMIT 2"); 
        if(mysqli_num_rows($query_jadwal) > 0) {
            while($row_j = mysqli_fetch_assoc($query_jadwal)): 
        ?>
            <div style="padding: 10px 0;">
                <p><strong><?=$row_j['matkul']?></strong></p>
                <small><?=$row_j['hari']?> | <?=$row_j['jam']?> | <?=$row_j['ruangan']?></small>
            </div>
            <hr style="border: 0; border-top: 1px solid #eee;">
        <?php endwhile; } else { echo "<p style='color:gray;'>Belum ada jadwal.</p>"; } ?>
        <br>
        <a href="jadwal.php" class="btn" style="width: 100%; box-sizing: border-box;">Cek Full Jadwal</a>
    </section>

    <!-- Kolom 2: Informasi (Tetap seperti kode kamu) -->
    <section class="card">
        <div class="card-header">Informasi Kelas</div>
        <h3 style="color: var(--primary);">Pemberitahuan Akademik</h3>
        <p>Pastikan setiap mahasiswa sudah mengisi absensi di menu Kehadiran.</p>
        <div style="background: #fff9db; padding: 10px; border-radius: 8px; border-left: 5px solid #fbbf24;">
            <small><b>Tips:</b> Klik menu di atas untuk navigasi cepat.</small>
        </div>
    </section>

    <!-- Kolom 3: Tugas (Tetap seperti kode kamu) -->
    <section class="card">
        <div class="card-header">Tugas Terbaru</div>
        <ul style="padding-left: 20px; line-height: 1.8;">
            <?php 
            $query_tugas = mysqli_query($conn, "SELECT * FROM tugas ORDER BY deadline ASC LIMIT 3"); 
            if(mysqli_num_rows($query_tugas) > 0) {
                while($row_t = mysqli_fetch_assoc($query_tugas)): 
            ?>
                <li>
                    <b><?=$row_t['matkul']?></b><br>
                    <small>Deadline: <?=$row_t['deadline']?></small>
                </li>
            <?php endwhile; } else { echo "<p style='color:gray;'>Tidak ada tugas aktif.</p>"; } ?>
        </ul>
        <br>
        <a href="tugas.php" class="btn" style="background:#64748b; width: 100%; box-sizing: border-box;">Lihat Semua Tugas</a>
    </section>

</main>

    <footer style="text-align: center; padding: 30px; color: #64748b; font-size: 0.9rem;">
        <p>&copy; 2026 Portal Kelas TI 2B | Designed with Professional Style</p>
    </footer>
</body>
</html>