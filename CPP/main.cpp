#include <algorithm>
#include <cctype>
#include <chrono>
#include <thread>
#include <iomanip>
#include <iostream>
#include <limits>
#include <string>
#include <vector>
#include "film.cpp"
using namespace std;

// fungsi untuk mencari indeks film berdasarkan ID menggunakan vector<Film>
int cariIndex(vector<Film>& daftarFilm, int id) {
    for (int i = 0; i < static_cast<int>(daftarFilm.size()); i++) {
        if (daftarFilm[i].getId() == id) return i;
    }
    return -1;
}

// fungsi untuk membaca teks yang diapit tanda petik
bool bacaTeks(string& teks) {
    cin >> ws;
    if (cin.peek() != '"') return false;
    cin.get();
    getline(cin, teks, '"');
    return true;
}

// fungsi untuk mencetak garis horizontal
void garis(int id, int judul, int genre, int durasi, int studio) {
    cout << '+' << string(id + 2, '-')
         << '+' << string(judul + 2, '-')
         << '+' << string(genre + 2, '-')
         << '+' << string(durasi + 2, '-')
         << '+' << string(studio + 2, '-') << "+\n";
}

// fungsi untuk mencetak header tabel
void cetakHeader(int id, int judul, int genre, int durasi, int studio) {
    garis(id, judul, genre, durasi, studio);
    cout << "| " << left << setw(id) << "ID"
         << " | " << setw(judul) << "JUDUL FILM"
         << " | " << setw(genre) << "GENRE"
         << " | " << setw(durasi) << "MENIT"
         << " | " << setw(studio) << "STUDIO" << " |\n";
    garis(id, judul, genre, durasi, studio);
}

// fungsi untuk mencetak satu baris data film
void cetakBaris(Film film, int id, int judul, int genre, int durasi, int studio) {
    cout << "| " << right << setw(id) << film.getId()
         << " | " << left << setw(judul) << film.getJudul()
         << " | " << setw(genre) << film.getGenre()
         << " | " << right << setw(durasi) << film.getDurasi()
         << " | " << left << setw(studio) << film.getStudio() << " |\n";
}

// fungsi untuk menghitung lebar kolom berdasarkan data film
void hitungLebar(vector<Film>& daftarFilm, int& id, int& judul, int& genre, int& durasi, int& studio) {
    id = 2; judul = 10; genre = 5; durasi = 5; studio = 6;
    for (Film& film : daftarFilm) {
        id = max(id, static_cast<int>(to_string(film.getId()).length()));
        judul = max(judul, static_cast<int>(film.getJudul().length()));
        genre = max(genre, static_cast<int>(film.getGenre().length()));
        durasi = max(durasi, static_cast<int>(to_string(film.getDurasi()).length()));
        studio = max(studio, static_cast<int>(film.getStudio().length()));
    }
}

// fungsi untuk menampilkan semua film dalam daftar
void tampilkanSemua(vector<Film>& daftarFilm) {
    if (daftarFilm.empty()) {
        cout << "Belum ada film yang tersimpan.\n\n";
        return;
    }

    int lebarId, lebarJudul, lebarGenre, lebarDurasi, lebarStudio;
    hitungLebar(daftarFilm, lebarId, lebarJudul, lebarGenre, lebarDurasi, lebarStudio);
    cetakHeader(lebarId, lebarJudul, lebarGenre, lebarDurasi, lebarStudio);
    for (const Film& film : daftarFilm) {
        cetakBaris(film, lebarId, lebarJudul, lebarGenre, lebarDurasi, lebarStudio);
    }
    garis(lebarId, lebarJudul, lebarGenre, lebarDurasi, lebarStudio);
    cout << daftarFilm.size() << " film ditampilkan.\n\n";
}

// fungsi untuk menampilkan satu film
void tampilkanSatu(const Film& film) {
    vector<Film> hasil = {film};
    int lebarId, lebarJudul, lebarGenre, lebarDurasi, lebarStudio;
    hitungLebar(hasil, lebarId, lebarJudul, lebarGenre, lebarDurasi, lebarStudio);
    cetakHeader(lebarId, lebarJudul, lebarGenre, lebarDurasi, lebarStudio);
    cetakBaris(film, lebarId, lebarJudul, lebarGenre, lebarDurasi, lebarStudio);
    garis(lebarId, lebarJudul, lebarGenre, lebarDurasi, lebarStudio);
}

// fungsi untuk menampilkan panduan penggunaan
void panduan() {
    cout << "\n+================== PUSAT BANTUAN BIOSKOP ==================+\n"
         << "|Teks judul, genre, dan studio wajib diapit tanda petik.    |\n"
         << "|                                                           |\n"
         << "|1. TAMBAH <id> \"judul\" \"genre\" <durasi> \"studio\"           |\n"
         << "|2. UBAH <id> \"judul\" \"genre\" <durasi> \"studio\"             |\n"
         << "|3. HAPUS <id>                                              |\n"
         << "|4. CARI <id>                                               |\n"
         << "|5. DAFTAR                                                  |\n"
         << "|6. BANTUAN                                                 |\n"
         << "|7. KELUAR                                                  |\n"
         << "|                                                           |\n"
         << "|Contoh: TAMBAH 101 \"Laskar Pelangi\" \"Drama\" 125 \"Studio 2\" |\n"
         << "+===========================================================+\n\n";
}

//fungsi delay
void delay() {
    this_thread::sleep_for(chrono::milliseconds(400));
}

//fungsi untuk menampilkan tampilan keluar dari program
void outro() {
    cout << "\n+------------------------------------------+\n";
    delay();
    cout << "|  Terima kasih telah memakai layanan     |\n";
    delay();
    cout << "|  pendataan film bioskop.                |\n";
    delay();
    cout << "|       Sampai bertemu di pemutaran       |\n";
    delay();
    cout << "|             film berikutnya!            |\n";
    delay();
    cout << "+------------------------------------------+\n";
}

// fungsi utama
int main() {
    // deklarasi variabel
    vector<Film> daftarFilm;
    string perintah;

    // menampilkan judul program
    cout << "==========================================\n"
         << "      SISTEM PENDATAAN FILM BIOSKOP       \n"
         << "==========================================\n"
         << "Ketik BANTUAN untuk melihat format perintah.\n\n";

    // loop utama untuk menerima perintah dari pengguna sampai perintah KELUAR diterima
    do {
        cout << "bioskop> ";
        cin >> perintah;

        // jika input gagal, keluar dari loop
        if (cin.fail()) break;

        // mengubah perintah menjadi huruf besar untuk memudahkan perbandingan
        transform(perintah.begin(), perintah.end(), perintah.begin(), [](unsigned char c) { return toupper(c); });

        if (perintah == "TAMBAH") { // perintah untuk menambah film baru
            int id, durasi;
            string judul, genre, studio;
            cin >> id;
            bool judulValid = bacaTeks(judul);
            bool genreValid = bacaTeks(genre);
            cin >> durasi;
            bool studioValid = bacaTeks(studio);

            if (cin.fail() || !judulValid || !genreValid || !studioValid) {
                cout << "Format tambah belum sesuai. Ketik BANTUAN.\n\n";
                cin.clear(); cin.ignore(numeric_limits<streamsize>::max(), '\n');
            } else if (cariIndex(daftarFilm, id) != -1) {
                cout << "ID " << id << " sudah terpakai.\n\n";
            } else {
                Film film;
                film.setId(id);
                film.setJudul(judul);
                film.setGenre(genre);
                film.setDurasi(durasi);
                film.setStudio(studio);
                daftarFilm.push_back(film);
                cout << "Film \"" << judul << "\" berhasil dicatat.\n\n";
            }
        } else if (perintah == "UBAH") { // perintah untuk mengubah data film yang sudah ada
            int id, durasi;
            string judul, genre, studio;
            cin >> id;
            bool judulValid = bacaTeks(judul);
            bool genreValid = bacaTeks(genre);
            cin >> durasi;
            bool studioValid = bacaTeks(studio);

            if (cin.fail() || !judulValid || !genreValid || !studioValid) {
                cout << "Format ubah belum sesuai. Ketik BANTUAN.\n\n";
                cin.clear(); cin.ignore(numeric_limits<streamsize>::max(), '\n');
            } else {
                int index = cariIndex(daftarFilm, id);
                if (index == -1) cout << "Tidak ada film dengan ID " << id << ".\n\n";
                else {
                    daftarFilm[index].setJudul(judul);
                    daftarFilm[index].setGenre(genre);
                    daftarFilm[index].setDurasi(durasi);
                    daftarFilm[index].setStudio(studio);
                    cout << "Data film ID " << id << " telah diperbarui.\n\n";
                }
            }
        } else if (perintah == "HAPUS") { // perintah untuk menghapus data film
            int id;
            cin >> id;
            if (cin.fail()) {
                cout << "Masukkan ID yang valid.\n\n";
                cin.clear(); cin.ignore(numeric_limits<streamsize>::max(), '\n');
            } else {
                int index = cariIndex(daftarFilm, id);
                if (index == -1) cout << "Tidak ada film dengan ID " << id << ".\n\n";
                else {
                    daftarFilm.erase(daftarFilm.begin() + index);
                    cout << "Data film ID " << id << " telah dihapus.\n\n";
                }
            }
        } else if (perintah == "CARI") { // perintah untuk mencari film berdasarkan ID
            int id;
            cin >> id;
            if (cin.fail()) {
                cout << "Masukkan ID yang valid.\n\n";
                cin.clear(); cin.ignore(numeric_limits<streamsize>::max(), '\n');
            } else {
                int index = cariIndex(daftarFilm, id);
                if (index == -1) cout << "Film dengan ID " << id << " tidak ditemukan.\n\n";
                else { tampilkanSatu(daftarFilm[index]); cout << '\n'; }
            }
        } else if (perintah == "DAFTAR") { // perintah untuk menampilkan semua film yang tersimpan
            tampilkanSemua(daftarFilm);
        } else if (perintah == "BANTUAN") { // perintah untuk menampilkan panduan penggunaan
            panduan();
        } else if (perintah != "KELUAR") { // perintah tidak dikenali
            cout << "Perintah tidak dikenali. Ketik BANTUAN untuk melihat pilihan.\n\n";
            cin.ignore(numeric_limits<streamsize>::max(), '\n');
        }
    } while (perintah != "KELUAR");

    outro();
    return 0;
}