<?php
// WAJIB me-load class definition SEBELUM session_start()
require_once "FilmTayang.php";
session_start();

// ============================================================
// DAFTAR GAMBAR DEFAULT UNTUK 5 FILM AWAL
// ============================================================
$defaultImages = [
    101 => "images/Laskar_Pelangi.jpg",
    102 => "images/The_Avengers.jpg",
    103 => "images/KKN_di_desa_penari.jpg",
    104 => "images/Inside_Out_2.jpg",
    105 => "images/The_Batman.jpeg",
];

// Validasi jika session sebelumnya berisi object rusak (__PHP_Incomplete_Class)
if (isset($_SESSION['daftarFilm']) && is_array($_SESSION['daftarFilm'])) {
    foreach ($_SESSION['daftarFilm'] as $item) {
        if ($item instanceof __PHP_Incomplete_Class || !($item instanceof FilmTayang)) {
            unset($_SESSION['daftarFilm']);
            break;
        }
    }
}

// Inisialisasi data awal di Session jika belum ada
if (!isset($_SESSION['daftarFilm'])) {
    $_SESSION['daftarFilm'] = [
        new FilmTayang(101, "Laskar Pelangi",  "Drama",   125, "Studio 1", "SU",  40000, "13:00", "Tersedia", $defaultImages[101]),
        new FilmTayang(102, "Avengers",         "Action",  143, "Studio 2", "13+", 50000, "15:30", "Tersedia", $defaultImages[102]),
        new FilmTayang(103, "KKN Desa Penari", "Horror",  116, "Studio 3", "17+", 45000, "19:00", "Tersedia", $defaultImages[103]),
        new FilmTayang(104, "Inside Out 2",    "Animasi",  96, "Studio 1", "SU",  35000, "10:00", "Tersedia", $defaultImages[104]),
        new FilmTayang(105, "The Batman",      "Action",  176, "Studio 4", "13+", 55000, "20:00", "Penuh",    $defaultImages[105]),
    ];
} else {
    // Sinkronisasi session lama secara aman
    try {
        foreach ($_SESSION['daftarFilm'] as $film) {
            if (empty($film->getGambar()) && isset($defaultImages[$film->getId()])) {
                $film->setGambar($defaultImages[$film->getId()]);
            }
        }
    } catch (Throwable $e) {
        $_SESSION['daftarFilm'] = [
            new FilmTayang(101, "Laskar Pelangi",  "Drama",   125, "Studio 1", "SU",  40000, "13:00", "Tersedia", $defaultImages[101]),
            new FilmTayang(102, "Avengers",         "Action",  143, "Studio 2", "13+", 50000, "15:30", "Tersedia", $defaultImages[102]),
            new FilmTayang(103, "KKN Desa Penari", "Horror",  116, "Studio 3", "17+", 45000, "19:00", "Tersedia", $defaultImages[103]),
            new FilmTayang(104, "Inside Out 2",    "Animasi",  96, "Studio 1", "SU",  35000, "10:00", "Tersedia", $defaultImages[104]),
            new FilmTayang(105, "The Batman",      "Action",  176, "Studio 4", "13+", 55000, "20:00", "Penuh",    $defaultImages[105]),
        ];
    }
}

// ============================================================
// SCAN GAMBAR YANG TERSEDIA DI FOLDER images/
// ============================================================
$availableImages = [];
if (is_dir("images")) {
    $files = scandir("images");
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..' && $file !== '.DS_Store' && !is_dir("images/" . $file)) {
            $availableImages[] = $file;
        }
    }
    sort($availableImages);
}

// ============================================================
// FUNGSI HELPER
// ============================================================
function idSudahAda(array $daftarFilm, int $id): bool {
    foreach ($daftarFilm as $film) {
        if ($film->getId() === $id) return true;
    }
    return false;
}

// ============================================================
// HANDLE FORM POST
// ============================================================
$pesan     = "";
$tipePesan = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = $_POST['aksi'] ?? '';

    // INSERT FILM
    if ($aksi === 'insert') {
        $idFilm          = (int)($_POST['id'] ?? 0);
        $judul           = trim($_POST['judul'] ?? '');
        $genre           = trim($_POST['genre'] ?? '');
        $durasi          = (int)($_POST['durasi'] ?? 0);
        $studio          = trim($_POST['studio'] ?? '');
        $klasifikasiUsia = trim($_POST['usia'] ?? '');
        $hargaTiket      = (int)($_POST['harga'] ?? 0);
        $jamTayang       = trim($_POST['jam'] ?? '');
        $statusTayang    = trim($_POST['status'] ?? '');
        $pilihGambar     = trim($_POST['pilih_gambar'] ?? '');

        if ($idFilm <= 0 || $judul === '' || $genre === '' || $durasi <= 0 ||
            $studio === '' || $klasifikasiUsia === '' || $hargaTiket <= 0 ||
            $jamTayang === '' || $statusTayang === '') {
            $pesan     = "Semua field harus diisi dengan benar.";
            $tipePesan = "error";
        } elseif (idSudahAda($_SESSION['daftarFilm'], $idFilm)) {
            $pesan     = "ID film $idFilm sudah digunakan. Silakan gunakan ID lain.";
            $tipePesan = "error";
        } else {
            $gambarPath = "";

            // 1. Upload file baru
            if (isset($_FILES['gambar_file']) && $_FILES['gambar_file']['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($_FILES['gambar_file']['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
                if (in_array($ext, $allowed)) {
                    if (!is_dir("images")) {
                        mkdir("images", 0777, true);
                    }
                    $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $judul);
                    $newFilename = "film_" . $idFilm . "_" . $safeName . "." . $ext;
                    $targetPath = "images/" . $newFilename;
                    if (move_uploaded_file($_FILES['gambar_file']['tmp_name'], $targetPath)) {
                        $gambarPath = $targetPath;
                    }
                }
            }

            // 2. Pilih gambar yang sudah ada
            if (empty($gambarPath) && !empty($pilihGambar) && file_exists("images/" . $pilihGambar)) {
                $gambarPath = "images/" . $pilihGambar;
            }

            $_SESSION['daftarFilm'][] = new FilmTayang(
                $idFilm, $judul, $genre, $durasi,
                $studio, $klasifikasiUsia, $hargaTiket,
                $jamTayang, $statusTayang, $gambarPath
            );

            $pesan     = "Data film \"$judul\" berhasil disimpan.";
            $tipePesan = "success";
        }
    }

    // DELETE FILM
    if ($aksi === 'delete') {
        $idHapus    = (int)($_POST['id_hapus'] ?? 0);
        $judulHapus = '';
        foreach ($_SESSION['daftarFilm'] as $key => $film) {
            if ($film->getId() === $idHapus) {
                $judulHapus = $film->getJudul();
                $img = $film->getGambar();
                if (!empty($img) && strpos($img, 'images/film_') === 0 && file_exists($img)) {
                    @unlink($img);
                }
                unset($_SESSION['daftarFilm'][$key]);
                $_SESSION['daftarFilm'] = array_values($_SESSION['daftarFilm']);
                break;
            }
        }
        if ($judulHapus !== '') {
            $pesan     = "Film \"$judulHapus\" berhasil dihapus.";
            $tipePesan = "success";
        } else {
            $pesan     = "Film dengan ID $idHapus tidak ditemukan.";
            $tipePesan = "error";
        }
    }

    // RESET SESSION
    if ($aksi === 'reset') {
        unset($_SESSION['daftarFilm']);
        header("Location: index.php");
        exit;
    }
}

$daftarFilm = $_SESSION['daftarFilm'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pendataan Film Bioskop</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            padding: 24px;
            font-size: 14px;
            line-height: 1.5;
        }

        .container {
            max-width: 1280px;
            margin: 0 auto;
        }

        .header-title {
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 1px solid #e2e8f0;
        }
        .header-title h1 {
            font-size: 22px;
            font-weight: 700;
            color: #0f172a;
        }
        .header-title p {
            color: #64748b;
            font-size: 13px;
            margin-top: 2px;
        }

        /* Alert */
        .alert {
            padding: 10px 14px;
            border-radius: 6px;
            margin-bottom: 16px;
            font-size: 13px;
        }
        .alert-success {
            background-color: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }
        .alert-error {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        /* Layout Grid */
        .grid-layout {
            display: grid;
            grid-template-columns: 360px 1fr;
            gap: 20px;
            align-items: start;
        }
        @media (max-width: 900px) {
            .grid-layout { grid-template-columns: 1fr; }
        }

        /* Card Container */
        .card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 18px;
        }
        .card h2 {
            font-size: 15px;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 14px;
            padding-bottom: 8px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Form Styles */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 10px;
        }
        .form-group {
            margin-bottom: 10px;
        }
        .form-group.full {
            grid-column: 1 / -1;
        }
        label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #475569;
            margin-bottom: 4px;
        }
        input[type="text"],
        input[type="number"],
        input[type="time"],
        select {
            width: 100%;
            padding: 7px 10px;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            font-size: 13px;
            background-color: #fff;
            color: #1e293b;
        }
        input:focus, select:focus {
            outline: none;
            border-color: #2563eb;
        }

        /* Image Box */
        .image-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px;
            margin-bottom: 12px;
        }
        .image-preview {
            max-width: 80px;
            max-height: 110px;
            border-radius: 4px;
            border: 1px solid #cbd5e1;
            display: none;
            margin-top: 8px;
        }

        /* Buttons */
        .btn {
            display: inline-block;
            padding: 8px 14px;
            font-size: 13px;
            font-weight: 500;
            border-radius: 4px;
            cursor: pointer;
            border: 1px solid transparent;
            text-align: center;
            width: 100%;
        }
        .btn-primary {
            background-color: #2563eb;
            color: #ffffff;
        }
        .btn-primary:hover {
            background-color: #1d4ed8;
        }
        .btn-secondary {
            background-color: #f1f5f9;
            color: #475569;
            border-color: #cbd5e1;
            margin-top: 8px;
        }
        .btn-secondary:hover {
            background-color: #e2e8f0;
        }
        .btn-danger {
            background-color: #ef4444;
            color: #ffffff;
            padding: 4px 8px;
            font-size: 12px;
            width: auto;
        }
        .btn-danger:hover {
            background-color: #dc2626;
        }

        /* Table */
        .table-responsive {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            text-align: left;
        }
        th, td {
            padding: 9px 10px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
        }
        th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: 600;
            white-space: nowrap;
        }
        tbody tr:hover {
            background-color: #f8fafc;
        }

        /* Poster Thumbnail */
        .poster-thumb {
            width: 42px;
            height: 58px;
            object-fit: cover;
            border-radius: 3px;
            border: 1px solid #cbd5e1;
            display: block;
        }
        .no-poster {
            width: 42px;
            height: 58px;
            border-radius: 3px;
            border: 1px dashed #cbd5e1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            color: #94a3b8;
            background: #f8fafc;
        }

        /* Tag / Badge */
        .tag {
            display: inline-block;
            padding: 2px 7px;
            font-size: 11px;
            font-weight: 600;
            border-radius: 4px;
        }
        .tag-green { background: #dcfce7; color: #166534; }
        .tag-red   { background: #fee2e2; color: #991b1b; }
        .tag-gray  { background: #f1f5f9; color: #475569; }

        .count-info {
            font-size: 12px;
            font-weight: normal;
            color: #64748b;
        }
    </style>
</head>
<body>

<div class="container">

    <div class="header-title">
        <h1>Sistem Pendataan Film Bioskop</h1>
        <p>Praktikum Desain dan Pemrograman Berorientasi Objek (PHP OOP)</p>
    </div>

    <?php if ($pesan): ?>
    <div class="alert alert-<?= $tipePesan ?>">
        <?= htmlspecialchars($pesan) ?>
    </div>
    <?php endif; ?>

    <div class="grid-layout">

        <!-- FORM TAMBAH FILM -->
        <div class="card">
            <h2>Tambah Film</h2>
            <form method="POST" action="" enctype="multipart/form-data">
                <input type="hidden" name="aksi" value="insert">

                <div class="form-row">
                    <div class="form-group">
                        <label for="id">ID Film</label>
                        <input type="number" id="id" name="id" placeholder="106" min="1"
                               value="<?= htmlspecialchars($_POST['id'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="durasi">Durasi (menit)</label>
                        <input type="number" id="durasi" name="durasi" placeholder="120" min="1"
                               value="<?= htmlspecialchars($_POST['durasi'] ?? '') ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="judul">Judul Film</label>
                    <input type="text" id="judul" name="judul" placeholder="Judul film"
                           value="<?= htmlspecialchars($_POST['judul'] ?? '') ?>" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="genre">Genre</label>
                        <input type="text" id="genre" name="genre" placeholder="Action / Drama"
                               value="<?= htmlspecialchars($_POST['genre'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="studio">Studio</label>
                        <input type="text" id="studio" name="studio" placeholder="Studio 1"
                               value="<?= htmlspecialchars($_POST['studio'] ?? '') ?>" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="usia">Usia</label>
                        <select id="usia" name="usia" required>
                            <option value="">Pilih</option>
                            <option value="SU"  <?= ($_POST['usia'] ?? '') === 'SU'  ? 'selected' : '' ?>>SU</option>
                            <option value="BTL" <?= ($_POST['usia'] ?? '') === 'BTL' ? 'selected' : '' ?>>BTL</option>
                            <option value="13+" <?= ($_POST['usia'] ?? '') === '13+' ? 'selected' : '' ?>>13+</option>
                            <option value="17+" <?= ($_POST['usia'] ?? '') === '17+' ? 'selected' : '' ?>>17+</option>
                            <option value="21+" <?= ($_POST['usia'] ?? '') === '21+' ? 'selected' : '' ?>>21+</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status" required>
                            <option value="Tersedia" <?= ($_POST['status'] ?? '') === 'Tersedia' ? 'selected' : '' ?>>Tersedia</option>
                            <option value="Penuh"    <?= ($_POST['status'] ?? '') === 'Penuh'    ? 'selected' : '' ?>>Penuh</option>
                            <option value="Selesai"  <?= ($_POST['status'] ?? '') === 'Selesai'  ? 'selected' : '' ?>>Selesai</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="harga">Harga Tiket (Rp)</label>
                        <input type="number" id="harga" name="harga" placeholder="50000" min="0"
                               value="<?= htmlspecialchars($_POST['harga'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="jam">Jam Tayang</label>
                        <input type="time" id="jam" name="jam"
                               value="<?= htmlspecialchars($_POST['jam'] ?? '') ?>" required>
                    </div>
                </div>

                <!-- Bagian Gambar -->
                <div class="image-box">
                    <div class="form-group">
                        <label for="gambar_file">Upload File Poster:</label>
                        <input type="file" id="gambar_file" name="gambar_file"
                               accept="image/jpeg,image/png,image/webp,image/gif"
                               onchange="previewUpload(this)">
                    </div>

                    <?php if (!empty($availableImages)): ?>
                    <div class="form-group" style="margin-bottom: 0; margin-top: 8px;">
                        <label for="pilih_gambar">Atau pilih dari folder images/:</label>
                        <select id="pilih_gambar" name="pilih_gambar" onchange="previewSelect(this)">
                            <option value="">-- Tidak memilih / gunakan upload --</option>
                            <?php foreach ($availableImages as $img): ?>
                            <option value="<?= htmlspecialchars($img) ?>"><?= htmlspecialchars($img) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php endif; ?>

                    <img id="previewImg" class="image-preview" src="" alt="Preview">
                </div>

                <button type="submit" class="btn btn-primary">Simpan Film</button>
            </form>

            <form method="POST" action="" onsubmit="return confirm('Kembalikan ke 5 data awal?');">
                <input type="hidden" name="aksi" value="reset">
                <button type="submit" class="btn btn-secondary">Reset ke Data Awal</button>
            </form>
        </div>

        <!-- TABEL DATA FILM -->
        <div class="card">
            <h2>
                Daftar Film Tayang
                <span class="count-info">Total: <?= count($daftarFilm) ?> film</span>
            </h2>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Poster</th>
                            <th>ID</th>
                            <th>Judul</th>
                            <th>Genre</th>
                            <th>Durasi</th>
                            <th>Studio</th>
                            <th>Usia</th>
                            <th>Harga</th>
                            <th>Jam</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($daftarFilm)): ?>
                        <tr>
                            <td colspan="11" style="text-align: center; color: #94a3b8; padding: 24px;">
                                Belum ada data film yang tersimpan.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($daftarFilm as $film):
                            $img = $film->getGambar();
                            $status = $film->getStatusTayang();
                            $tagClass = (strtolower($status) === 'tersedia') ? 'tag-green' : ((strtolower($status) === 'penuh') ? 'tag-red' : 'tag-gray');
                        ?>
                        <tr>
                            <td>
                                <?php if (!empty($img) && file_exists($img)): ?>
                                    <img class="poster-thumb" src="<?= htmlspecialchars($img) ?>" alt="Poster">
                                <?php else: ?>
                                    <div class="no-poster">N/A</div>
                                <?php endif; ?>
                            </td>
                            <td><?= $film->getId() ?></td>
                            <td><strong><?= htmlspecialchars($film->getJudul()) ?></strong></td>
                            <td><?= htmlspecialchars($film->getGenre()) ?></td>
                            <td><?= $film->getDurasi() ?> m</td>
                            <td><?= htmlspecialchars($film->getStudio()) ?></td>
                            <td><span class="tag tag-gray"><?= htmlspecialchars($film->getKlasifikasiUsia()) ?></span></td>
                            <td>Rp <?= number_format($film->getHargaTiket(), 0, ',', '.') ?></td>
                            <td><?= htmlspecialchars($film->getJamTayang()) ?></td>
                            <td><span class="tag <?= $tagClass ?>"><?= htmlspecialchars($status) ?></span></td>
                            <td>
                                <form method="POST" action="" style="display:inline"
                                      onsubmit="return confirm('Hapus film <?= htmlspecialchars(addslashes($film->getJudul())) ?>?');">
                                    <input type="hidden" name="aksi" value="delete">
                                    <input type="hidden" name="id_hapus" value="<?= $film->getId() ?>">
                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>

<script>
function previewUpload(input) {
    const preview = document.getElementById('previewImg');
    const select = document.getElementById('pilih_gambar');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            if (select) select.value = '';
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        if (!select || !select.value) {
            preview.style.display = 'none';
        }
    }
}

function previewSelect(select) {
    const preview = document.getElementById('previewImg');
    const fileInput = document.getElementById('gambar_file');
    if (select.value) {
        fileInput.value = '';
        preview.src = 'images/' + select.value;
        preview.style.display = 'block';
    } else {
        if (!fileInput.files || !fileInput.files[0]) {
            preview.style.display = 'none';
        }
    }
}
</script>

</body>
</html>
