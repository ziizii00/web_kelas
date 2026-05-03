<?php
session_start();
if(!isset($_SESSION['username'])) header("location:login.php");
include 'koneksi.php';
$role = $_SESSION['role'];

// Proses Simpan Data Mahasiswa
if(isset($_POST['add'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $nim = mysqli_real_escape_string($conn, $_POST['nim']);
    $jabatan = mysqli_real_escape_string($conn, $_POST['jabatan']);
    
    mysqli_query($conn, "INSERT INTO mahasiswa (nama, nim, jabatan) VALUES ('$nama', '$nim', '$jabatan')");
    echo "<script>alert('Mahasiswa Berhasil Ditambahkan!'); window.location='mahasiswa.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mahasiswa - TI 2B</title>
    <!-- Font Inter untuk kesan Formal -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }

        /* Container Grid Dinamis */
        .main-grid {
            display: grid;
            gap: 30px;
            align-items: start;
            margin-top: -40px;
        }

        /* Card Style */
        .card {
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            color: var(--primary);
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #f1f5f9;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Form Styling */
        form input {
            width: 100%;
            padding: 14px;
            margin-bottom: 15px;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.9rem;
            box-sizing: border-box;
            transition: 0.3s;
        }

        form input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 4px rgba(0, 86, 179, 0.05);
        }

        .btn {
            width: 100%;
            padding: 14px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn:hover {
            background: var(--accent);
            transform: translateY(-2px);
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f8fafc;
            color: #64748b;
            font-size: 0.75rem;
            text-transform: uppercase;
            padding: 15px;
            text-align: left;
            font-weight: 700;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.95rem;
            color: #1e293b;
        }

        .nim-text {
            color: var(--accent);
            font-weight: 700;
            font-family: 'Courier New', Courier, monospace; /* Gaya teknis untuk NIM */
        }

        .badge-jabatan {
            background: #f1f5f9;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #475569;
        }

        @media (max-width: 1000px) {
            .main-grid { grid-template-columns: 1fr !important; }
        }
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
            <a href="tugas.php">Tugas</a>
            <a href="logout.php" style="color:red; font-weight: bold;">Logout</a>
        </div>
    </nav>

    <header class="hero-banner">
        <div class="hero-info">
            <h1>DATA MAHASISWA</h1>
            <p>Manajemen anggota kelas TI 2B secara terpusat.</p>
        </div>
    </header>

    <main class="container main-grid" style="grid-template-columns: <?php echo ($role!='anggota') ? '1fr 2fr' : '1fr'; ?>;">
        
        <?php if($role!='anggota'): ?>
        <!-- Form Tambah Mahasiswa -->
        <div class="card">
            <div class="card-header">Tambah Anggota</div>
            <form method="POST">
                <input type="text" name="nama" placeholder="Nama Lengkap" required>
                <input type="text" name="nim" placeholder="NIM" required>
                <input type="text" name="jabatan" placeholder="Jabatan (Anggota/Sipen/dll)" required>
                <button name="add" class="btn">Simpan Data</button>
            </form>
        </div>
        <?php endif; ?>

        <!-- List Mahasiswa -->
        <div class="card">
            <div class="card-header">Daftar Mahasiswa Aktif</div>
            <table>
                <thead>
                    <tr>
                        <th>NIM</th>
                        <th>Nama Lengkap</th>
                        <th>Jabatan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $q = mysqli_query($conn, "SELECT * FROM mahasiswa ORDER BY nama ASC"); 
                    while($r = mysqli_fetch_assoc($q)): 
                    ?>
                    <tr>
                        <td class="nim-text"><?=$r['nim']?></td>
                        <td style="font-weight: 600;"><?=$r['nama']?></td>
                        <td><span class="badge-jabatan"><?=$r['jabatan']?></span></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

    </main>

    <footer style="text-align: center; padding: 40px; color: #94a3b8; font-size: 0.85rem;">
        &copy; 2026 Portal Kelas TI 2B | Formal & Aesthetic System
    </footer>
</body>
</html>