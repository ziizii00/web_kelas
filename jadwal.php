<?php
session_start();
if(!isset($_SESSION['username'])) header("location:login.php");
include 'koneksi.php';
$role = $_SESSION['role'];

// Proses Simpan Data Jadwal
if(isset($_POST['add'])) {
    $hari = mysqli_real_escape_string($conn, $_POST['hari']);
    $matkul = mysqli_real_escape_string($conn, $_POST['matkul']);
    $jam = mysqli_real_escape_string($conn, $_POST['jam']);
    $ruangan = mysqli_real_escape_string($conn, $_POST['ruangan']);
    $sipen = mysqli_real_escape_string($conn, $_POST['sipen']);

    $sql = "INSERT INTO jadwal (hari, matkul, jam, ruangan, sipen) VALUES ('$hari', '$matkul', '$jam', '$ruangan', '$sipen')";
    
    if(mysqli_query($conn, $sql)) {
        echo "<script>alert('Jadwal Berhasil Ditambahkan!'); window.location='jadwal.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
    }
}

// Proses Hapus Jadwal
if(isset($_GET['hapus']) && ($role != 'anggota')) {
    $id = intval($_GET['hapus']);
    mysqli_query($conn, "DELETE FROM jadwal WHERE id=$id");
    header("location:jadwal.php");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Kuliah - TI 2B</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        .main-grid { display: grid; gap: 30px; align-items: start; margin-top: -40px; }
        .card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 20px; padding: 30px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .card-header { color: #1e293b; font-size: 1.1rem; font-weight: 700; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 2px solid #f1f5f9; text-transform: uppercase; }
        
        form input, form select { width: 100%; padding: 14px; margin-bottom: 15px; border: 1.5px solid #e2e8f0; border-radius: 12px; font-size: 0.9rem; box-sizing: border-box; transition: 0.3s; }
        form input:focus, form select:focus { outline: none; border-color: #3b82f6; box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1); }

        .btn { width: 100%; padding: 14px; background: #2c3f67; color: white; border: none; border-radius: 12px; font-weight: 600; cursor: pointer; transition: 0.3s; }
        .btn:hover { background: #27334f; transform: translateY(-2px); }

        table { width: 100%; border-collapse: collapse; }
        th { background: #f8fafc; color: #64748b; font-size: 0.75rem; text-transform: uppercase; padding: 15px; text-align: left; font-weight: 700; border-bottom: 2px solid #f1f5f9; }
        td { padding: 15px; border-bottom: 1px solid #f1f5f9; font-size: 0.95rem; color: #1e293b; }
        
        .badge-hari { background: #dbeafe; color: #1e40af; padding: 4px 10px; border-radius: 8px; font-weight: 600; font-size: 0.8rem; }
        .badge-jam { background: #f1f5f9; color: #475569; padding: 4px 10px; border-radius: 8px; font-weight: 500; font-size: 0.8rem; }
        
        @media (max-width: 1000px) { .main-grid { grid-template-columns: 1fr !important; } }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-links">
            <a href="dashboard.php">Home</a>
            <a href="mahasiswa.php">Mahasiswa</a>
            <a href="dosen.php">Dosen</a>
            <a href="jadwal.php" class="active">Jadwal</a>
            <a href="kehadiran.php">Kehadiran</a>
            <a href="tugas.php">Tugas</a>
            <a href="logout.php" style="color:#ef4444; font-weight: bold;">Logout</a>
        </div>
    </nav>

    <header class="hero-banner">
        <div class="hero-info">
            <h1>JADWAL KULIAH TI 2B</h1>
            <p>Manajemen waktu perkuliahan semester aktif.</p>
        </div>
    </header>

    <main class="container main-grid" style="grid-template-columns: <?php echo ($role!='anggota') ? '1fr 2.5fr' : '1fr'; ?>;">
        
        <?php if($role!='anggota'): ?>
        <!-- FORM INPUT JADWAL -->
        <div class="card">
            <div class="card-header">Tambah Jadwal</div>
            <form method="POST">
                <select name="hari" required>
                    <option value="" disabled selected>Pilih Hari</option>
                    <option value="Senin">Senin</option>
                    <option value="Selasa">Selasa</option>
                    <option value="Rabu">Rabu</option>
                    <option value="Kamis">Kamis</option>
                    <option value="Jumat">Jumat</option>
                    <option value="Sabtu">Sabtu</option>
                    <option value="Minggu">Minggu</option>
                </select>

                <select name="matkul" required>
                    <option value="" disabled selected>Pilih Mata Kuliah</option>
                    <?php 
                    // Mengambil data mata kuliah unik dari tabel dosen[cite: 1]
                    $res = mysqli_query($conn, "SELECT DISTINCT mata_kuliah FROM dosen ORDER BY mata_kuliah ASC");
                    while($dm = mysqli_fetch_assoc($res)) {
                        echo "<option value='".$dm['mata_kuliah']."'>".$dm['mata_kuliah']."</option>";
                    }
                    ?>
                </select>

                <select name="jam" required>
                    <option value="" disabled selected>Pilih Jam Kuliah</option>
                    <option value="08:00 - 09:40">08:00 - 09:40</option>
                    <option value="09:40 - 11:20">10:00 - 11:40</option>
                    <option value="09:40 - 11:20">10:00 - 12:00</option>
                    <option value="12:30 - 14:10">13:00 - 14:40</option>
                    <option value="12:30 - 14:10">13:00 - 15:00</option>
                    <option value="14:10 - 15:50">15:00 - 16:00</option>
                    <option> <input type="text" name="jam">jam</option>
                </select>

                <input type="text" name="ruangan" placeholder="Ruangan (Contoh: Lab 1 / R. 201)" required>
                <input type="text" name="sipen" placeholder="Nama SIPEN Matkul" required>
                
                <button name="add" class="btn">Simpan Jadwal</button>
            </form>
        </div>
        <?php endif; ?>

        <!-- TABEL DATA JADWAL -->
        <div class="card">
            <div class="card-header">Jadwal Perkuliahan</div>
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Hari</th>
                            <th>Mata Kuliah</th>
                            <th>Jam</th>
                            <th>Ruang</th>
                            <th>SIPEN</th>
                            <?php if($role!='anggota') echo "<th>Aksi</th>"; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $q = mysqli_query($conn, "SELECT * FROM jadwal ORDER BY FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'), jam ASC");
                        if(mysqli_num_rows($q) > 0) {
                            while($r = mysqli_fetch_assoc($q)): 
                        ?>
                        <tr>
                            <td><span class="badge-hari"><?=$r['hari']?></span></td>
                            <td style="font-weight: 600;"><?=$r['matkul']?></td>
                            <td><span class="badge-jam"><?=$r['jam']?></span></td>
                            <td><?=$r['ruangan']?></td>
                            <td><?=$r['sipen']?></td>
                            <?php if($role!='anggota'): ?>
                            <td>
                                <a href="?hapus=<?=$r['id']?>" style="color: #ef4444; font-weight: 700; text-decoration: none;" onclick="return confirm('Hapus jadwal ini?')">Hapus</a>
                            </td>
                            <?php endif; ?>
                        </tr>
                        <?php endwhile; } else { ?>
                            <tr><td colspan="6" style="text-align:center; padding: 40px; color: #94a3b8;">Belum ada jadwal yang diinput.</td></tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <footer style="text-align: center; padding: 40px; color: #94a3b8; font-size: 0.85rem;">
        &copy; 2026 Portal Kelas TI 2B | Database Synchronized
    </footer>
</body>
</html>