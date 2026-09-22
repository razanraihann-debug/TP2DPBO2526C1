#include <algorithm>
#include <chrono>
#include <iomanip>
#include <iostream>
#include <limits>
#include <string>
#include <thread>
#include <vector>

#include "FilmTayang.cpp"

using namespace std;

// FUNGSI MEMBACA TEKS DI DALAM TANDA PETIK
bool bacaTeks(string& teks) {
    cin >> ws;

    if (cin.peek() != '"') {
        return false;
    }

    cin.get();
    getline(cin, teks, '"');

    return true;
}


// FUNGSI MENCARI ID FILM
bool idSudahAda(const vector<FilmTayang>& daftarFilm, int id) {
    for (const FilmTayang& film : daftarFilm) {
        if (film.getId() == id) {
            return true;
        }
    }

    return false;
}


// FUNGSI MEMBACA SATU DATA FILM DARI INPUT
bool bacaDataFilm(int& id, string& judul, string& genre, int& durasi, string& studio, string& klasifikasiUsia, int& hargaTiket, string& jamTayang, string& statusTayang) {
    if (!(cin >> id)) {
        return false;
    }

    if (!bacaTeks(judul)) return false;
    if (!bacaTeks(genre)) return false;

    if (!(cin >> durasi)) return false;

    if (!bacaTeks(studio)) return false;
    if (!bacaTeks(klasifikasiUsia)) return false;

    if (!(cin >> hargaTiket)) return false;

    if (!bacaTeks(jamTayang)) return false;
    if (!bacaTeks(statusTayang)) return false;

    return true;
}

// FUNGSI MENGHITUNG LEBAR KOLOM
void hitungLebar(const vector<FilmTayang>& daftarFilm,int& lebarId,int& lebarJudul,int& lebarGenre,int& lebarDurasi,int& lebarStudio,int& lebarUsia,int& lebarHarga,int& lebarJam,int& lebarStatus) {
    lebarId = 2;
    lebarJudul = 10;
    lebarGenre = 5;
    lebarDurasi = 6;
    lebarStudio = 6;
    lebarUsia = 10;
    lebarHarga = 11;
    lebarJam = 8;
    lebarStatus = 10;

    for (const FilmTayang& film : daftarFilm) {
        lebarId = max(
            lebarId,
            static_cast<int>(
                to_string(film.getId()).length()
            )
        );

        lebarJudul = max(
            lebarJudul,
            static_cast<int>(film.getJudul().length())
        );

        lebarGenre = max(
            lebarGenre,
            static_cast<int>(film.getGenre().length())
        );

        lebarDurasi = max(
            lebarDurasi,
            static_cast<int>(
                to_string(film.getDurasi()).length()
            )
        );

        lebarStudio = max(
            lebarStudio,
            static_cast<int>(film.getStudio().length())
        );

        lebarUsia = max(
            lebarUsia,
            static_cast<int>(
                film.getKlasifikasiUsia().length()
            )
        );

        lebarHarga = max(
            lebarHarga,
            static_cast<int>(
                to_string(film.getHargaTiket()).length()
            )
        );

        lebarJam = max(
            lebarJam,
            static_cast<int>(film.getJamTayang().length())
        );

        lebarStatus = max(
            lebarStatus,
            static_cast<int>(film.getStatusTayang().length())
        );
    }
}

// FUNGSI MEMBUAT GARIS TABEL
void garis(int id,int judul,int genre,int durasi,int studio,int usia,int harga,int jam,int status) {
    cout << '+'
         << string(id + 2, '-') << '+'
         << string(judul + 2, '-') << '+'
         << string(genre + 2, '-') << '+'
         << string(durasi + 2, '-') << '+'
         << string(studio + 2, '-') << '+'
         << string(usia + 2, '-') << '+'
         << string(harga + 2, '-') << '+'
         << string(jam + 2, '-') << '+'
         << string(status + 2, '-') << "+\n";
}

// FUNGSI HEADER TABEL
void cetakHeader(int id,int judul,int genre,int durasi,int studio,int usia,int harga,int jam,int status) {
    garis(id, judul, genre, durasi, studio, usia, harga, jam, status);

    cout << "| " << left << setw(id) << "ID"
         << " | " << setw(judul) << "JUDUL"
         << " | " << setw(genre) << "GENRE"
         << " | " << setw(durasi) << "MENIT"
         << " | " << setw(studio) << "STUDIO"
         << " | " << setw(usia) << "USIA"
         << " | " << setw(harga) << "HARGA"
         << " | " << setw(jam) << "JAM"
         << " | " << setw(status) << "STATUS"
         << " |\n";

    garis(id, judul, genre, durasi, studio, usia, harga, jam, status);
}

// FUNGSI MENCETAK SATU BARIS FILM
void cetakBaris(const FilmTayang& film,int id,int judul,int genre,int durasi,int studio,int usia,int harga,int jam,int status) {
    cout << "| " << right << setw(id)
         << film.getId()

         << " | " << left << setw(judul)
         << film.getJudul()

         << " | " << setw(genre)
         << film.getGenre()

         << " | " << right << setw(durasi)
         << film.getDurasi()

         << " | " << left << setw(studio)
         << film.getStudio()

         << " | " << setw(usia)
         << film.getKlasifikasiUsia()

         << " | " << right << setw(harga)
         << film.getHargaTiket()

         << " | " << left << setw(jam)
         << film.getJamTayang()

         << " | " << setw(status)
         << film.getStatusTayang()

         << " |\n";
}

// FUNGSI MENAMPILKAN SEMUA FILM
void tampilkanSemua(const vector<FilmTayang>& daftarFilm) {
    if (daftarFilm.empty()) {
        cout << "Belum ada film yang tersimpan.\n\n";
        return;
    }

    int id, judul, genre, durasi;
    int studio, usia, harga, jam, status;

    hitungLebar(daftarFilm,id, judul, genre, durasi,studio, usia, harga, jam, status);

    cetakHeader(id, judul, genre, durasi,studio, usia, harga, jam, status);

    for (const FilmTayang& film : daftarFilm) {
        cetakBaris(film,id, judul, genre, durasi,studio, usia, harga, jam, status);
    }

    garis(id, judul, genre, durasi,studio, usia, harga, jam, status);

    cout << daftarFilm.size() << " film ditampilkan.\n\n";
}

// FUNGSI PANDUAN
void panduan() {
    cout << "\n+==========================================================+\n"
         << "|                   PUSAT BANTUAN BIOSKOP                  |\n"
         << "+==========================================================+\n"
         << "| Teks wajib diapit tanda petik.                           |\n"
         << "| 1. INSERT <id> \"judul\" \"genre\" <durasi> \"studio\"         |\n"
         << "|    \"usia\" <harga> \"jam\" \"status\"                         |\n"
         << "| 2. SHOW                                                  |\n"
         << "| 3. HELP                                                  |\n"
         << "| 4. EXIT                                                  |\n"
         << "|                                                          |\n"
         << "| Contoh:                                                  |\n"
         << "| INSERT 106 \"Avengers\" \"Action\" 143 \"Studio 1\"            |\n"
         << "| \"13+\" 50000 \"19:00\" \"Tersedia\"                           |\n"
         << "+==========================================================+\n\n";
}

// FUNGSI DELAY DAN OUTRO
void delay() {
    this_thread::sleep_for(chrono::milliseconds(300));
}

void outro() {
    cout << "\n+---------------------------------------------+\n";
    delay();
    cout << "|      Terima kasih telah memakai layanan     |\n";
    delay();
    cout << "|           pendataan film bioskop.           |\n";
    delay();
    cout << "|         Sampai bertemu di pemutaran         |\n";
    delay();
    cout << "|               film berikutnya!              |\n";
    delay();
    cout << "+---------------------------------------------+\n";
}

// FUNGSI UTAMA
int main() {
    vector<FilmTayang> daftarFilm;

    string perintah;

    // 5 OBJEK AWAL SEBELUM INPUT USER
    FilmTayang film1(
        101,
        "Laskar Pelangi",
        "Drama",
        125,
        "Studio 1",
        "SU",
        40000,
        "13:00",
        "Tersedia"
    );

    FilmTayang film2(
        102,
        "Avengers",
        "Action",
        143,
        "Studio 2",
        "13+",
        50000,
        "15:30",
        "Tersedia"
    );

    FilmTayang film3(
        103,
        "KKN Desa Penari",
        "Horror",
        116,
        "Studio 3",
        "17+",
        45000,
        "19:00",
        "Tersedia"
    );

    FilmTayang film4(
        104,
        "Inside Out 2",
        "Animasi",
        96,
        "Studio 1",
        "SU",
        35000,
        "10:00",
        "Tersedia"
    );

    FilmTayang film5(
        105,
        "The Batman",
        "Action",
        176,
        "Studio 4",
        "13+",
        55000,
        "20:00",
        "Penuh"
    );

    // Memasukkan 5 objek ke vector
    daftarFilm.push_back(film1);
    daftarFilm.push_back(film2);
    daftarFilm.push_back(film3);
    daftarFilm.push_back(film4);
    daftarFilm.push_back(film5);

    // TAMPILAN AWAL
    cout << "==========================================\n"
         << "      SISTEM PENDATAAN FILM BIOSKOP       \n"
         << "==========================================\n"
         << "5 film awal telah dimasukkan ke sistem.\n"
         << "Ketik HELP untuk melihat format perintah.\n\n";


    // LOOP PROGRAM
    do {
        cout << "bioskop> ";
        cin >> perintah;

        if (cin.fail()) {
            break;
        }

        transform(perintah.begin(),perintah.end(),perintah.begin(),[](unsigned char c){return toupper(c);});

        // PERINTAH INSERT
        if (perintah == "INSERT") {
            int id, durasi, hargaTiket;

            string judul;
            string genre;
            string studio;
            string klasifikasiUsia;
            string jamTayang;
            string statusTayang;

            bool valid = bacaDataFilm(id,judul,genre,durasi,studio,klasifikasiUsia,hargaTiket,jamTayang,statusTayang);

            if (!valid || cin.fail()) {
                cout << "Format tambah belum sesuai. " << "Ketik HELP.\n\n";

                cin.clear();
                cin.ignore(numeric_limits<streamsize>::max(),'\n');
            }
            else if (idSudahAda(daftarFilm, id)) {
                cout << "ID " << id << " sudah terpakai.\n\n";
            }
            else {
                FilmTayang filmBaru(id,judul,genre,durasi,studio,klasifikasiUsia,hargaTiket,jamTayang,statusTayang);

                daftarFilm.push_back(filmBaru);

                cout << "Film \"" << judul << "\" berhasil ditambahkan.\n\n";
            }
        }

        // PERINTAH SHOW
        else if (perintah == "SHOW") {
            tampilkanSemua(daftarFilm);
        }

        // PERINTAH HELP
        else if (perintah == "HELP") {
            panduan();
        }

        // PERINTAH EXIT
        else if (perintah != "EXIT") {
            cout << "Perintah tidak dikenali. " << "Ketik HELP.\n\n";

            cin.ignore(numeric_limits<streamsize>::max(),'\n');
        }

    } while (perintah != "EXIT");

    outro();

    return 0;
}