import time
import re

from film import Film


# fungsi untuk mencari indeks film berdasarkan ID
def cariIndex(daftarFilm, id):
    for i in range(len(daftarFilm)):
        if daftarFilm[i].getId() == id:
            return i

    return -1


# fungsi untuk membaca teks yang diapit tanda petik
def bacaTeks(teks):
    if len(teks) < 2:
        return False, ""

    if teks[0] != '"' or teks[-1] != '"':
        return False, ""

    return True, teks[1:-1]


# fungsi untuk mencetak garis horizontal
def garis(id, judul, genre, durasi, studio):
    print(
        "+" + "-" * (id + 2) +
        "+" + "-" * (judul + 2) +
        "+" + "-" * (genre + 2) +
        "+" + "-" * (durasi + 2) +
        "+" + "-" * (studio + 2) +
        "+"
    )


# fungsi untuk mencetak header tabel
def cetakHeader(id, judul, genre, durasi, studio):
    garis(id, judul, genre, durasi, studio)

    print(
        "| " + "ID".ljust(id) +
        " | " + "JUDUL FILM".ljust(judul) +
        " | " + "GENRE".ljust(genre) +
        " | " + "MENIT".rjust(durasi) +
        " | " + "STUDIO".ljust(studio) +
        " |"
    )

    garis(id, judul, genre, durasi, studio)


# fungsi untuk mencetak satu baris data film
def cetakBaris(film, id, judul, genre, durasi, studio):
    print(
        "| " + str(film.getId()).rjust(id) +
        " | " + film.getJudul().ljust(judul) +
        " | " + film.getGenre().ljust(genre) +
        " | " + str(film.getDurasi()).rjust(durasi) +
        " | " + film.getStudio().ljust(studio) +
        " |"
    )


# fungsi untuk menghitung lebar kolom
def hitungLebar(daftarFilm):
    id = 2
    judul = 10
    genre = 5
    durasi = 5
    studio = 6

    for film in daftarFilm:
        id = max(
            id,
            len(str(film.getId()))
        )

        judul = max(
            judul,
            len(film.getJudul())
        )

        genre = max(
            genre,
            len(film.getGenre())
        )

        durasi = max(
            durasi,
            len(str(film.getDurasi()))
        )

        studio = max(
            studio,
            len(film.getStudio())
        )

    return id, judul, genre, durasi, studio


# fungsi untuk menampilkan semua film
def tampilkanSemua(daftarFilm):
    if len(daftarFilm) == 0:
        print("Belum ada film yang tersimpan.\n")
        return

    lebarId, lebarJudul, lebarGenre, lebarDurasi, lebarStudio = \
        hitungLebar(daftarFilm)

    cetakHeader(
        lebarId,
        lebarJudul,
        lebarGenre,
        lebarDurasi,
        lebarStudio
    )

    for film in daftarFilm:
        cetakBaris(
            film,
            lebarId,
            lebarJudul,
            lebarGenre,
            lebarDurasi,
            lebarStudio
        )

    garis(
        lebarId,
        lebarJudul,
        lebarGenre,
        lebarDurasi,
        lebarStudio
    )

    print(
        len(daftarFilm),
        "film ditampilkan.\n"
    )


# fungsi untuk menampilkan satu film
def tampilkanSatu(film):
    hasil = [film]

    lebarId, lebarJudul, lebarGenre, lebarDurasi, lebarStudio = \
        hitungLebar(hasil)

    cetakHeader(
        lebarId,
        lebarJudul,
        lebarGenre,
        lebarDurasi,
        lebarStudio
    )

    cetakBaris(
        film,
        lebarId,
        lebarJudul,
        lebarGenre,
        lebarDurasi,
        lebarStudio
    )

    garis(
        lebarId,
        lebarJudul,
        lebarGenre,
        lebarDurasi,
        lebarStudio
    )


# fungsi untuk menampilkan panduan
def panduan():
    print(
        "\n+================== PUSAT BANTUAN BIOSKOP ==================+\n"
        "|Teks judul, genre, dan studio wajib diapit tanda petik.    |\n"
        "|                                                           |\n"
        '|1. TAMBAH <id> "judul" "genre" <durasi> "studio"           |\n'
        '|2. UBAH <id> "judul" "genre" <durasi> "studio"             |\n'
        "|3. HAPUS <id>                                              |\n"
        "|4. CARI <id>                                               |\n"
        "|5. DAFTAR                                                  |\n"
        "|6. BANTUAN                                                 |\n"
        "|7. KELUAR                                                  |\n"
        "|                                                           |\n"
        '|Contoh: TAMBAH 101 "Laskar Pelangi" "Drama" 125 "Studio 2" |\n'
        "+===========================================================+\n"
    )


# fungsi delay
def delay():
    time.sleep(0.4)


# fungsi outro
def outro():
    print("\n+------------------------------------------+")
    delay()
    print("|  Terima kasih telah memakai layanan     |")
    delay()
    print("|  pendataan film bioskop.                |")
    delay()
    print("|       Sampai bertemu di pemutaran       |")
    delay()
    print("|             film berikutnya!            |")
    delay()
    print("+------------------------------------------+")


# fungsi utama
def main():

    daftarFilm = []
    perintah = ""

    print(
        "==========================================\n"
        "      SISTEM PENDATAAN FILM BIOSKOP       \n"
        "==========================================\n"
        "Ketik BANTUAN untuk melihat format perintah.\n"
    )

    while True:

        print("bioskop> ", end="")
        input_user = input().strip()

        if input_user == "":
            continue

        bagian = input_user.split(maxsplit=1)
        perintah = bagian[0].upper()

        if perintah == "TAMBAH":

            if len(bagian) < 2:
                print(
                    "Format tambah belum sesuai. Ketik BANTUAN.\n"
                )
                continue

            data = bagian[1]

            pola = r'^(\d+)\s+"([^"]*)"\s+"([^"]*)"\s+(\d+)\s+"([^"]*)"$'
            hasil = re.match(pola, data)

            if hasil is None:
                print(
                    "Format tambah belum sesuai. Ketik BANTUAN.\n"
                )
                continue

            id = int(hasil.group(1))
            judul = hasil.group(2)
            genre = hasil.group(3)
            durasi = int(hasil.group(4))
            studio = hasil.group(5)

            if cariIndex(daftarFilm, id) != -1:
                print(
                    "ID",
                    id,
                    "sudah terpakai.\n"
                )

            else:
                film = Film()

                film.setId(id)
                film.setJudul(judul)
                film.setGenre(genre)
                film.setDurasi(durasi)
                film.setStudio(studio)

                daftarFilm.append(film)

                print(
                    'Film "' +
                    judul +
                    '" berhasil dicatat.\n'
                )

        elif perintah == "UBAH":

            if len(bagian) < 2:
                print(
                    "Format ubah belum sesuai. Ketik BANTUAN.\n"
                )
                continue

            data = bagian[1]

            pola = r'^(\d+)\s+"([^"]*)"\s+"([^"]*)"\s+(\d+)\s+"([^"]*)"$'
            hasil = re.match(pola, data)

            if hasil is None:
                print(
                    "Format ubah belum sesuai. Ketik BANTUAN.\n"
                )
                continue

            id = int(hasil.group(1))
            judul = hasil.group(2)
            genre = hasil.group(3)
            durasi = int(hasil.group(4))
            studio = hasil.group(5)

            index = cariIndex(daftarFilm, id)

            if index == -1:
                print(
                    "Tidak ada film dengan ID",
                    id,
                    ".\n"
                )

            else:
                daftarFilm[index].setJudul(judul)
                daftarFilm[index].setGenre(genre)
                daftarFilm[index].setDurasi(durasi)
                daftarFilm[index].setStudio(studio)

                print(
                    "Data film ID",
                    id,
                    "telah diperbarui.\n"
                )

        elif perintah == "HAPUS":

            if len(bagian) < 2:
                print("Masukkan ID yang valid.\n")
                continue

            try:
                id = int(bagian[1].strip())
            except ValueError:
                print("Masukkan ID yang valid.\n")
                continue

            index = cariIndex(daftarFilm, id)

            if index == -1:
                print(
                    "Tidak ada film dengan ID",
                    id,
                    ".\n"
                )

            else:
                daftarFilm.pop(index)

                print(
                    "Data film ID",
                    id,
                    "telah dihapus.\n"
                )

        elif perintah == "CARI":

            if len(bagian) < 2:
                print("Masukkan ID yang valid.\n")
                continue

            try:
                id = int(bagian[1].strip())
            except ValueError:
                print("Masukkan ID yang valid.\n")
                continue

            index = cariIndex(daftarFilm, id)

            if index == -1:
                print(
                    "Film dengan ID",
                    id,
                    "tidak ditemukan.\n"
                )

            else:
                tampilkanSatu(daftarFilm[index])
                print()

        elif perintah == "DAFTAR":

            tampilkanSemua(daftarFilm)

        elif perintah == "BANTUAN":

            panduan()

        elif perintah == "KELUAR":

            break

        else:

            print(
                "Perintah tidak dikenali. Ketik BANTUAN untuk melihat pilihan.\n"
            )

    outro()


if __name__ == "__main__":
    main()