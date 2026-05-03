<?php
session_start();
if(!isset($_SESSION['username'])) header("location:login.php");
include 'koneksi.php';
$role = $_SESSION['role'];

// Proses Simpan Data Tugas
if(isset($_POST['add'])) {
    $matkul = mysqli_real_escape_string($conn, $_POST['matkul']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $deadline = mysqli_real_escape_string($conn, $_POST['deadline']);
    
    // Sesuaikan dengan kolom tabel tugas: matkul, deskripsi, deadline
    mysqli_query($conn, "INSERT INTO tugas (matkul, deskripsi, deadline) VALUES ('$matkul', '$deskripsi', '$deadline')");
    echo "<script>alert('Tugas Berhasil Ditambahkan!'); window.location='tugas.php';</script>";
}

// Proses Hapus Tugas (Hanya BPH/SIPEN)
if(isset($_GET['hapus']) && ($role != 'anggota')) {
    $id = intval($_GET['hapus']);
    mysqli_query($conn, "DELETE FROM tugas WHERE id=$id");
    header("location:tugas.php");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tugas Kuliah - TI 2B</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        .grid-input { display: grid; grid-template-columns: 380px 1fr; gap: 40px; align-items: start; }

        .card-form {
            background: #ffffff; border-radius: 24px; box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            padding: 35px; position: sticky; top: 100px; border-top: 6px solid #241e54;
        }

        .card-form h2 { margin-top: 0; color: var(--primary); font-size: 1.5rem; text-align: center; margin-bottom: 30px; }

        .form-group { margin-bottom: 25px; }
        .form-group label { display: block; font-size: 0.75rem; font-weight: 800; color: #94a3b8; letter-spacing: 1px; margin-bottom: 10px; text-transform: uppercase; }

        .form-group input, .form-group textarea, .form-group select {
            width: 100%; padding: 14px 18px; background: #f8fafc; border: 2px solid #f1f5f9;
            border-radius: 15px; font-size: 0.95rem; transition: all 0.3s ease; box-sizing: border-box; font-family: inherit;
        }

        .form-group textarea { height: 100px; resize: none; }
        .form-group input:focus, .form-group textarea:focus, .form-group select:focus {
            outline: none; border-color: #242e66; background: #ffffff; box-shadow: 0 10px 20px rgba(239, 68, 68, 0.1);
        }

        .btn-submit {
            width: 100%; padding: 16px; border: none; border-radius: 15px;
            background: linear-gradient(135deg, #284064 0%, #22385b 100%);
            color: white; font-weight: 700; font-size: 1rem; cursor: pointer;
            box-shadow: 0 10px 20px rgba(239, 68, 68, 0.2); transition: 0.3s;
        }
        .btn-submit:hover { transform: translateY(-3px); box-shadow: 0 15px 30px rgba(68, 97, 239, 0.3); }

        .card-table { background: #ffffff; border-radius: 24px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.04); }
        .badge-deadline { background: #e6e2fe; color: #7e839f; padding: 5px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; display: inline-block; }

        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { text-align: left; padding: 15px; color: #94a3b8; font-size: 0.75rem; text-transform: uppercase; border-bottom: 2px solid #f1f5f9; }
        td { padding: 15px; border-bottom: 1px solid #f1f5f9; vertical-align: top; }

        @media (max-width: 1000px) { .grid-input { grid-template-columns: 1fr; } .card-form { position: static; } }
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
            <a href="logout.php" style="color:red; font-weight:bold;">Logout</a>
        </div>
    </nav>

    <header class="hero-banner" style="background: linear-gradient(135deg, #10174c 0%, #16325d 100%);">
        <div class="hero-info">
            <h1>LIST TUGAS</h1>
            <p>Pantau semua tugas kuliah dan batas waktu pengumpulan Kelas TI 2B.</p>
        </div>
    </header>

    <main class="container <?php echo ($role != 'anggota') ? 'grid-input' : ''; ?>">
        
        <?php if($role != 'anggota'): ?>
        <section class="card-form">
            <h2>Tambah Tugas</h2>
            <form method="POST">
                <div class="form-group">
                    <label>Mata Kuliah</label>
                    <select name="matkul" required>
                        <option value="" disabled selected>Pilih Mata Kuliah</option>
                        <?php 
                        // Mengambil list matkul dari tabel dosen agar sinkron
                        $res = mysqli_query($conn, "SELECT DISTINCT mata_kuliah FROM dosen ORDER BY mata_kuliah ASC");
                        while($dm = mysqli_fetch_assoc($res)) {
                            echo "<option value='".$dm['mata_kuliah']."'>".$dm['mata_kuliah']."</option>";
                        }
                        ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Deskripsi Tugas</label>
                    <textarea name="deskripsi" placeholder="Tulis instruksi tugas di sini..." required></textarea>
                </div>

                <div class="form-group">
                    <label>Batas Waktu (Deadline)</label>
                    <input type="date" name="deadline" required>
                </div>

                <button name="add" class="btn-submit">Publikasikan Tugas</button>
            </form>
        </section>
        <?php endif; ?>

        <section class="card-table">
            <div class="card-header" style="font-weight: 800; color: var(--primary);">Daftar Tugas Aktif</div>
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Mata Kuliah</th>
                            <th>Deskripsi</th>
                            <th>Deadline</th>
                            <?php if($role != 'anggota') echo "<th>Aksi</th>"; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $query = mysqli_query($conn, "SELECT * FROM tugas ORDER BY deadline ASC");
                        if(mysqli_num_rows($query) > 0) {
                            while($row = mysqli_fetch_assoc($query)): 
                        ?>
                        <tr>
                            <td style="color: #193a4d; font-weight: 700; min-width: 150px;"><?=$row['matkul']?></td>
                            <td style="min-width: 250px; font-size: 0.9rem; color: #475569; line-height: 1.6;">
                                <?= nl2br($row['deskripsi']) ?>
                            </td>
                            <td>
                                <span class="badge-deadline">
                                    <?= date('d M Y', strtotime($row['deadline'])) ?>
                                </span>
                            </td>
                            <?php if($role != 'anggota'): ?>
                            <td>
                                <a href="?hapus=<?=$row['id']?>" 
                                   style="color: #1b3760; font-size: 0.8rem; font-weight: bold; text-decoration: none;" 
                                   onclick="return confirm('Hapus tugas ini?')">Hapus</a>
                            </td>
                            <?php endif; ?>
                        </tr>
                        <?php endwhile; } else { ?>
                            <tr><td colspan="4" style="text-align:center; padding: 50px; color: #94a3b8;">Tidak ada tugas aktif saat ini.</td></tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <footer style="text-align: center; padding: 50px; color: #94a3b8;">
        &copy; 2026 TI 2B Portal. All rights reserved.
    </footer>
</body>
</html>