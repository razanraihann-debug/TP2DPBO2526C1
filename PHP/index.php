<?php
require_once __DIR__ . '/film.php';
session_start(); // start session to store film data

// Initialize the film list in session if not already set
if (!isset($_SESSION['daftarFilm'])) {
    $_SESSION['daftarFilm'] = [];
}

// Helper function to find the index of a film by its ID
function cariIndex($id) {
    foreach ($_SESSION['daftarFilm'] as $index => $film) {
        if ($film->getId() === $id) return $index;
    }
    return -1;
}

// Helper function to escape output for HTML
function e($teks) {
    return htmlspecialchars($teks, ENT_QUOTES, 'UTF-8');
}

// Initialize variables for messages and search results
$pesan = '';
$hasilCari = null;
$dataEdit = null;

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = $_POST['aksi'] ?? '';
    $idInput = trim($_POST['id'] ?? '');
    $id = (int) $idInput;

    // Validate ID input
    if ($aksi === 'tambah' || $aksi === 'update') {
        $judul = trim($_POST['judul'] ?? '');
        $genre = trim($_POST['genre'] ?? '');
        $durasi = trim($_POST['durasi'] ?? '');
        $studio = trim($_POST['studio'] ?? '');
        $gambar = trim($_POST['gambar'] ?? '');

        // Validate that all fields are filled
        if ($idInput === '' || $judul === '' || $genre === '' || $durasi === '' || $studio === '' || $gambar === '') {
            $pesan = 'Semua data harus diisi.';
        } elseif ($aksi === 'tambah') { // Add new film
            if (cariIndex($id) !== -1) $pesan = 'ID sudah digunakan.'; // Check if ID is already used
            else { // Create a new Film object and add it to the session
                $film = new Film();
                $film->setId($id);
                $film->setJudul($judul);
                $film->setGenre($genre);
                $film->setDurasi($durasi);
                $film->setStudio($studio);
                $film->setGambar($gambar);
                $_SESSION['daftarFilm'][] = $film;
                $pesan = 'Data berhasil ditambahkan.';
            }
        } else { // Update existing film
            $index = cariIndex($id);
            if ($index === -1) $pesan = 'Data tidak ditemukan.'; // Check if the film exists
            else { // Update the existing Film object in the session
                $_SESSION['daftarFilm'][$index]->setJudul($judul);
                $_SESSION['daftarFilm'][$index]->setGenre($genre);
                $_SESSION['daftarFilm'][$index]->setDurasi($durasi);
                $_SESSION['daftarFilm'][$index]->setStudio($studio);
                $_SESSION['daftarFilm'][$index]->setGambar($gambar);
                $pesan = 'Data berhasil diubah.';
            }
        }
    } elseif ($aksi === 'hapus') { // Delete film
        $index = cariIndex($id);
        if ($index === -1) $pesan = 'Data tidak ditemukan.'; // Check if the film exists
        else { // Remove the film from the session array
            array_splice($_SESSION['daftarFilm'], $index, 1);
            $pesan = 'Data berhasil dihapus.';
        }
    } elseif ($aksi === 'cari') { // Search for film
        $index = cariIndex($id); 
        if ($index === -1) $pesan = 'Data tidak ditemukan.'; // Check if the film exists
        else $hasilCari = $_SESSION['daftarFilm'][$index]; // Store the found film for display
    } elseif ($aksi === 'ambilEdit') { // Prepare film data for editing
        $index = cariIndex($id);
        if ($index === -1) $pesan = 'Data tidak ditemukan.'; // Check if the film exists
        else $dataEdit = $_SESSION['daftarFilm'][$index]; // Store the film data for editing
    }
}
?>

// Main content
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Manajemen Bioskop</title>
  <style>
    body { font-family: Arial, sans-serif; max-width: 1100px; margin: 32px auto; padding: 0 18px; color: #202124; }
    .grid { display: grid; grid-template-columns: 340px 1fr; gap: 28px; align-items: start; }
    form, .hasil { padding: 20px; border: 1px solid #ddd; border-radius: 8px; }
    label { display: block; margin-top: 10px; font-weight: bold; }
    input { box-sizing: border-box; width: 100%; padding: 8px; margin-top: 4px; }
    button { margin-top: 16px; padding: 8px 13px; cursor: pointer; }
    .pesan { padding: 10px; background: #e8f0fe; border-radius: 6px; }
    table { width: 100%; border-collapse: collapse; margin-top: 16px; }
    th, td { border: 1px solid #ddd; padding: 9px; text-align: left; vertical-align: middle; }
    th { background: #f4f4f4; }
    img { width: 64px; height: 86px; object-fit: cover; }
    .aksi { display: inline; border: 0; padding: 0; }
    .aksi button { margin: 2px; }
    small { color: #666; }
  </style>
</head>
<body>
  <h1>Manajemen Data Bioskop</h1>
  <p>Data disimpan sementara pada session browser.</p>
  <?php if ($pesan !== ''): ?><p class="pesan"><?= e($pesan) ?></p><?php endif; ?>

  <div class="grid">
    <form method="post">
      <h2><?= $dataEdit ? 'Update Film' : 'Tambah Film' ?></h2>
      <input type="hidden" name="aksi" value="<?= $dataEdit ? 'update' : 'tambah' ?>">
      <label>ID Film <input type="number" min="1" name="id" required <?= $dataEdit ? 'readonly' : '' ?> value="<?= e($dataEdit ? $dataEdit->getId() : '') ?>"></label>
      <label>Judul <input name="judul" required value="<?= e($dataEdit ? $dataEdit->getJudul() : '') ?>"></label>
      <label>Genre <input name="genre" required value="<?= e($dataEdit ? $dataEdit->getGenre() : '') ?>"></label>
      <label>Durasi (menit) <input type="number" min="1" name="durasi" required value="<?= e($dataEdit ? $dataEdit->getDurasi() : '') ?>"></label>
      <label>Studio <input name="studio" required value="<?= e($dataEdit ? $dataEdit->getStudio() : '') ?>"></label>
      <label>Path gambar lokal <input name="gambar" placeholder="images/film.jpg" required value="<?= e($dataEdit ? $dataEdit->getGambar() : '') ?>"></label>
      <small>Contoh: <code>images/poster-film.jpg</code></small><br>
      <button type="submit"><?= $dataEdit ? 'Simpan Perubahan' : 'Tambah Data' ?></button>
    </form>

    <div>
      <form method="post" class="hasil">
        <h2>Cari Film</h2>
        <input type="hidden" name="aksi" value="cari">
        <label>ID Film <input type="number" min="1" name="id" required></label>
        <button type="submit">Cari Data</button>
        <?php if ($hasilCari): ?>
          <p><strong><?= e($hasilCari->getJudul()) ?></strong> — <?= e($hasilCari->getGenre()) ?>, <?= e($hasilCari->getDurasi()) ?> menit, Studio <?= e($hasilCari->getStudio()) ?></p>
        <?php endif; ?>
      </form>

      <h2>Daftar Film</h2>
      <table>
        <tr><th>ID</th><th>Gambar</th><th>Judul</th><th>Genre</th><th>Durasi</th><th>Studio</th><th>Aksi</th></tr>
        <?php if (count($_SESSION['daftarFilm']) === 0): ?>
          <tr><td colspan="7">Belum ada data film.</td></tr>
        <?php else: foreach ($_SESSION['daftarFilm'] as $film): ?>
          <tr>
            <td><?= e($film->getId()) ?></td>
            <td><img src="<?= e($film->getGambar()) ?>" alt="Poster <?= e($film->getJudul()) ?>"></td>
            <td><?= e($film->getJudul()) ?></td><td><?= e($film->getGenre()) ?></td>
            <td><?= e($film->getDurasi()) ?> menit</td><td><?= e($film->getStudio()) ?></td>
            <td>
              <form method="post" class="aksi"><input type="hidden" name="aksi" value="ambilEdit"><input type="hidden" name="id" value="<?= e($film->getId()) ?>"><button>Edit</button></form>
              <form method="post" class="aksi"><input type="hidden" name="aksi" value="hapus"><input type="hidden" name="id" value="<?= e($film->getId()) ?>"><button onclick="return confirm('Hapus data ini?')">Hapus</button></form>
            </td>
          </tr>
        <?php endforeach; endif; ?>
      </table>
    </div>
  </div>
</body>
</html>
