import re
import sys
import os
import time

sys.path.insert(0, os.path.abspath(os.path.dirname(__file__)))
from FilmTayang import FilmTayang


# FUNGSI MENCARI ID FILM
def id_sudah_ada(daftar_film, id):
    for film in daftar_film:
        if film.getId() == id:
            return True
    return False


# FUNGSI MENGHITUNG LEBAR KOLOM
def hitung_lebar(daftar_film):
    lebar = {
        "id":     2,
        "judul":  10,
        "genre":  5,
        "durasi": 6,
        "studio": 6,
        "usia":   10,
        "harga":  11,
        "jam":    8,
        "status": 10,
    }

    for film in daftar_film:
        lebar["id"]     = max(lebar["id"],     len(str(film.getId())))
        lebar["judul"]  = max(lebar["judul"],   len(film.getJudul()))
        lebar["genre"]  = max(lebar["genre"],   len(film.getGenre()))
        lebar["durasi"] = max(lebar["durasi"],  len(str(film.getDurasi())))
        lebar["studio"] = max(lebar["studio"],  len(film.getStudio()))
        lebar["usia"]   = max(lebar["usia"],    len(film.getKlasifikasiUsia()))
        lebar["harga"]  = max(lebar["harga"],   len(str(film.getHargaTiket())))
        lebar["jam"]    = max(lebar["jam"],     len(film.getJamTayang()))
        lebar["status"] = max(lebar["status"],  len(film.getStatusTayang()))

    return lebar


# FUNGSI MEMBUAT GARIS TABEL
def garis(w):
    cols = ["id", "judul", "genre", "durasi", "studio", "usia", "harga", "jam", "status"]
    print("+" + "".join("-" * (w[c] + 2) + "+" for c in cols))


# FUNGSI HEADER TABEL
def cetak_header(w):
    garis(w)
    print(
        f"| {'ID':<{w['id']}} "
        f"| {'JUDUL':<{w['judul']}} "
        f"| {'GENRE':<{w['genre']}} "
        f"| {'MENIT':<{w['durasi']}} "
        f"| {'STUDIO':<{w['studio']}} "
        f"| {'USIA':<{w['usia']}} "
        f"| {'HARGA':<{w['harga']}} "
        f"| {'JAM':<{w['jam']}} "
        f"| {'STATUS':<{w['status']}} |"
    )
    garis(w)


# FUNGSI MENCETAK SATU BARIS FILM
def cetak_baris(film, w):
    print(
        f"| {film.getId():>{w['id']}} "
        f"| {film.getJudul():<{w['judul']}} "
        f"| {film.getGenre():<{w['genre']}} "
        f"| {film.getDurasi():>{w['durasi']}} "
        f"| {film.getStudio():<{w['studio']}} "
        f"| {film.getKlasifikasiUsia():<{w['usia']}} "
        f"| {film.getHargaTiket():>{w['harga']}} "
        f"| {film.getJamTayang():<{w['jam']}} "
        f"| {film.getStatusTayang():<{w['status']}} |"
    )


# FUNGSI MENAMPILKAN SEMUA FILM
def tampilkan_semua(daftar_film):
    if not daftar_film:
        print("Belum ada film yang tersimpan.\n")
        return

    w = hitung_lebar(daftar_film)
    cetak_header(w)
    for film in daftar_film:
        cetak_baris(film, w)
    garis(w)
    print(f"{len(daftar_film)} film ditampilkan.\n")


# FUNGSI PANDUAN
def panduan():
    print("\n+==========================================================+")
    print("|                   PUSAT BANTUAN BIOSKOP                  |")
    print("+==========================================================+")
    print("| Teks wajib diapit tanda petik.                           |")
    print("| 1. INSERT <id> \"judul\" \"genre\" <durasi> \"studio\"         |")
    print("|    \"usia\" <harga> \"jam\" \"status\"                         |")
    print("| 2. SHOW                                                  |")
    print("| 3. HELP                                                  |")
    print("| 4. EXIT                                                  |")
    print("|                                                          |")
    print("| Contoh:                                                  |")
    print("| INSERT 106 \"Avengers\" \"Action\" 143 \"Studio 1\"            |")
    print("| \"13+\" 50000 \"19:00\" \"Tersedia\"                           |")
    print("+==========================================================+\n")


# FUNGSI DELAY
def delay():
    time.sleep(0.3)


# FUNGSI OUTRO
def outro():
    print("\n+---------------------------------------------+")
    delay()
    print("|      Terima kasih telah memakai layanan     |")
    delay()
    print("|           pendataan film bioskop.           |")
    delay()
    print("|         Sampai bertemu di pemutaran         |")
    delay()
    print("|               film berikutnya!              |")
    delay()
    print("+---------------------------------------------+")


# FUNGSI MEMBACA TEKS DI DALAM TANDA PETIK
def baca_teks_dari_token(tokens, idx):
    """Membaca token bertanda petik, mengembalikan (teks, idx_baru) atau (None, idx) jika gagal."""
    if idx >= len(tokens):
        return None, idx

    token = tokens[idx]
    if not token.startswith('"'):
        return None, idx

    # kata tunggal: "kata"
    if token.endswith('"') and len(token) > 1:
        return token[1:-1], idx + 1

    # multi-kata: "kata kata kata"
    parts = [token[1:]]
    idx += 1
    while idx < len(tokens):
        part = tokens[idx]
        idx += 1
        if part.endswith('"'):
            parts.append(part[:-1])
            return " ".join(parts), idx
        parts.append(part)

    return None, idx


# FUNGSI PARSE INPUT INSERT
def parse_insert(sisa):
    """Parse argumen INSERT dan kembalikan dict data film atau None jika gagal."""
    # Tokenisasi dengan mempertahankan string dalam petik
    tokens = re.findall(r'"[^"]*"|\S+', sisa)
    idx = 0

    try:
        id_film = int(tokens[idx]); idx += 1
        judul, idx = baca_teks_dari_token(tokens, idx)
        genre, idx = baca_teks_dari_token(tokens, idx)
        durasi = int(tokens[idx]); idx += 1
        studio, idx = baca_teks_dari_token(tokens, idx)
        klasifikasi_usia, idx = baca_teks_dari_token(tokens, idx)
        harga_tiket = int(tokens[idx]); idx += 1
        jam_tayang, idx = baca_teks_dari_token(tokens, idx)
        status_tayang, idx = baca_teks_dari_token(tokens, idx)
    except (ValueError, IndexError):
        return None

    if None in (judul, genre, studio, klasifikasi_usia, jam_tayang, status_tayang):
        return None

    return {
        "id": id_film, "judul": judul, "genre": genre,
        "durasi": durasi, "studio": studio, "klasifikasiUsia": klasifikasi_usia,
        "hargaTiket": harga_tiket, "jamTayang": jam_tayang, "statusTayang": status_tayang,
    }


# FUNGSI UTAMA
def main():
    daftar_film = [
        FilmTayang(101, "Laskar Pelangi",  "Drama",   125, "Studio 1", "SU",  40000, "13:00", "Tersedia"),
        FilmTayang(102, "Avengers",         "Action",  143, "Studio 2", "13+", 50000, "15:30", "Tersedia"),
        FilmTayang(103, "KKN Desa Penari", "Horror",  116, "Studio 3", "17+", 45000, "19:00", "Tersedia"),
        FilmTayang(104, "Inside Out 2",    "Animasi",  96, "Studio 1", "SU",  35000, "10:00", "Tersedia"),
        FilmTayang(105, "The Batman",      "Action",  176, "Studio 4", "13+", 55000, "20:00", "Penuh"),
    ]

    print("==========================================")
    print("      SISTEM PENDATAAN FILM BIOSKOP       ")
    print("==========================================")
    print("5 film awal telah dimasukkan ke sistem.")
    print("Ketik HELP untuk melihat format perintah.\n")

    perintah = ""

    while True:
        try:
            baris = input("bioskop> ").strip()
        except EOFError:
            break

        if not baris:
            continue

        parts = baris.split(None, 1)
        perintah = parts[0].upper()

        # PERINTAH INSERT
        if perintah == "INSERT":
            sisa = parts[1] if len(parts) > 1 else ""
            data = parse_insert(sisa)

            if data is None:
                print("Format tambah belum sesuai. Ketik HELP.\n")
                continue

            if id_sudah_ada(daftar_film, data["id"]):
                print(f"ID {data['id']} sudah terpakai.\n")
                continue

            daftar_film.append(FilmTayang(
                data["id"], data["judul"], data["genre"], data["durasi"],
                data["studio"], data["klasifikasiUsia"], data["hargaTiket"],
                data["jamTayang"], data["statusTayang"]
            ))
            print(f"Film \"{data['judul']}\" berhasil ditambahkan.\n")

        # PERINTAH SHOW
        elif perintah == "SHOW":
            tampilkan_semua(daftar_film)

        # PERINTAH HELP
        elif perintah == "HELP":
            panduan()

        # PERINTAH EXIT
        elif perintah == "EXIT":
            break

        else:
            print("Perintah tidak dikenali. Ketik HELP.\n")

    outro()


if __name__ == "__main__":
    main()