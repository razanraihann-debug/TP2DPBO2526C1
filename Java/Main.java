import java.util.ArrayList;
import java.util.List;
import java.util.Scanner;

public class Main {

    // fungsi untuk mencari indeks film berdasarkan ID
    static int cariIndex(List<Film> daftarFilm, int id) {
        for (int i = 0; i < daftarFilm.size(); i++) {
            if (daftarFilm.get(i).getId() == id) {
                return i;
            }
        }
        return -1;
    }

    // fungsi untuk mencetak garis horizontal
    static void garis(int id, int judul, int genre, int durasi, int studio) {
        System.out.println(
            "+" + "-".repeat(id + 2) +
            "+" + "-".repeat(judul + 2) +
            "+" + "-".repeat(genre + 2) +
            "+" + "-".repeat(durasi + 2) +
            "+" + "-".repeat(studio + 2) + "+"
        );
    }

    // fungsi untuk mencetak header tabel
    static void cetakHeader(
        int id,
        int judul,
        int genre,
        int durasi,
        int studio
    ) {
        garis(id, judul, genre, durasi, studio);

        System.out.printf(
            "| %-" + id + "s | " +
            "%-" + judul + "s | " +
            "%-" + genre + "s | " +
            "%" + durasi + "s | " +
            "%-" + studio + "s |\n",
            "ID",
            "JUDUL FILM",
            "GENRE",
            "MENIT",
            "STUDIO"
        );

        garis(id, judul, genre, durasi, studio);
    }

    // fungsi untuk mencetak satu baris data film
    static void cetakBaris(
        Film film,
        int id,
        int judul,
        int genre,
        int durasi,
        int studio
    ) {
        System.out.printf(
            "| %" + id + "d | " +
            "%-" + judul + "s | " +
            "%-" + genre + "s | " +
            "%" + durasi + "d | " +
            "%-" + studio + "s |\n",
            film.getId(),
            film.getJudul(),
            film.getGenre(),
            film.getDurasi(),
            film.getStudio()
        );
    }

    // fungsi untuk menghitung lebar kolom
    static int[] hitungLebar(List<Film> daftarFilm) {
        int id = 2;
        int judul = 10;
        int genre = 5;
        int durasi = 5;
        int studio = 6;

        for (Film film : daftarFilm) {
            id = Math.max(
                id,
                String.valueOf(film.getId()).length()
            );

            judul = Math.max(
                judul,
                film.getJudul().length()
            );

            genre = Math.max(
                genre,
                film.getGenre().length()
            );

            durasi = Math.max(
                durasi,
                String.valueOf(film.getDurasi()).length()
            );

            studio = Math.max(
                studio,
                film.getStudio().length()
            );
        }

        return new int[]{id, judul, genre, durasi, studio};
    }

    // fungsi untuk menampilkan semua film
    static void tampilkanSemua(List<Film> daftarFilm) {
        if (daftarFilm.isEmpty()) {
            System.out.println("Belum ada film yang tersimpan.\n");
            return;
        }

        int[] lebar = hitungLebar(daftarFilm);

        cetakHeader(
            lebar[0],
            lebar[1],
            lebar[2],
            lebar[3],
            lebar[4]
        );

        for (Film film : daftarFilm) {
            cetakBaris(
                film,
                lebar[0],
                lebar[1],
                lebar[2],
                lebar[3],
                lebar[4]
            );
        }

        garis(
            lebar[0],
            lebar[1],
            lebar[2],
            lebar[3],
            lebar[4]
        );

        System.out.println(
            daftarFilm.size() + " film ditampilkan.\n"
        );
    }

    // fungsi untuk menampilkan satu film
    static void tampilkanSatu(Film film) {
        List<Film> hasil = new ArrayList<>();
        hasil.add(film);

        int[] lebar = hitungLebar(hasil);

        cetakHeader(
            lebar[0],
            lebar[1],
            lebar[2],
            lebar[3],
            lebar[4]
        );

        cetakBaris(
            film,
            lebar[0],
            lebar[1],
            lebar[2],
            lebar[3],
            lebar[4]
        );

        garis(
            lebar[0],
            lebar[1],
            lebar[2],
            lebar[3],
            lebar[4]
        );
    }

    // fungsi panduan
    static void panduan() {
        System.out.println(
            "\n+================== PUSAT BANTUAN BIOSKOP ==================+\n" +
            "|Teks judul, genre, dan studio wajib diapit tanda petik.    |\n" +
            "|                                                           |\n" +
            "|1. TAMBAH <id> \"judul\" \"genre\" <durasi> \"studio\"           |\n" +
            "|2. UBAH <id> \"judul\" \"genre\" <durasi> \"studio\"             |\n" +
            "|3. HAPUS <id>                                              |\n" +
            "|4. CARI <id>                                               |\n" +
            "|5. DAFTAR                                                  |\n" +
            "|6. BANTUAN                                                 |\n" +
            "|7. KELUAR                                                  |\n" +
            "|                                                           |\n" +
            "|Contoh: TAMBAH 101 \"Laskar Pelangi\" \"Drama\" 125 \"Studio 2\" |\n" +
            "+===========================================================+\n"
        );
    }

    // fungsi delay
    static void delay() {
        try {
            Thread.sleep(400);
        } catch (InterruptedException e) {
            Thread.currentThread().interrupt();
        }
    }

    // fungsi outro
    static void outro() {
        System.out.println("\n+------------------------------------------+");
        delay();
        System.out.println("|  Terima kasih telah memakai layanan     |");
        delay();
        System.out.println("|  pendataan film bioskop.                |");
        delay();
        System.out.println("|       Sampai bertemu di pemutaran       |");
        delay();
        System.out.println("|             film berikutnya!            |");
        delay();
        System.out.println("+------------------------------------------+");
    }

    public static void main(String[] args) {

        List<Film> daftarFilm = new ArrayList<>();
        Scanner input = new Scanner(System.in);

        System.out.println(
            "==========================================\n" +
            "      SISTEM PENDATAAN FILM BIOSKOP       \n" +
            "==========================================\n" +
            "Ketik BANTUAN untuk melihat format perintah.\n"
        );

        String perintah = "";

        do {
            System.out.print("bioskop> ");

            String baris = input.nextLine().trim();

            if (baris.isEmpty()) {
                continue;
            }

            String[] bagian = baris.split(" ", 2);
            perintah = bagian[0].toUpperCase();

            try {

                if (perintah.equals("TAMBAH")) {

                    String data = bagian.length > 1 ? bagian[1] : "";

                    String[] hasil = data.split(
                        "\"([^\"]*)\""
                    );

                    // parsing menggunakan regex
                    java.util.regex.Matcher matcher =
                        java.util.regex.Pattern.compile(
                            "^(\\d+)\\s+\"([^\"]*)\"\\s+\"([^\"]*)\"\\s+(\\d+)\\s+\"([^\"]*)\"$"
                        ).matcher(data);

                    if (!matcher.matches()) {
                        System.out.println(
                            "Format tambah belum sesuai. Ketik BANTUAN.\n"
                        );
                        continue;
                    }

                    int id = Integer.parseInt(matcher.group(1));
                    String judul = matcher.group(2);
                    String genre = matcher.group(3);
                    int durasi = Integer.parseInt(matcher.group(4));
                    String studio = matcher.group(5);

                    if (cariIndex(daftarFilm, id) != -1) {
                        System.out.println(
                            "ID " + id + " sudah terpakai.\n"
                        );
                    } else {
                        Film film = new Film();

                        film.setId(id);
                        film.setJudul(judul);
                        film.setGenre(genre);
                        film.setDurasi(durasi);
                        film.setStudio(studio);

                        daftarFilm.add(film);

                        System.out.println(
                            "Film \"" + judul +
                            "\" berhasil dicatat.\n"
                        );
                    }

                } else if (perintah.equals("UBAH")) {

                    String data = bagian.length > 1 ? bagian[1] : "";

                    java.util.regex.Matcher matcher =
                        java.util.regex.Pattern.compile(
                            "^(\\d+)\\s+\"([^\"]*)\"\\s+\"([^\"]*)\"\\s+(\\d+)\\s+\"([^\"]*)\"$"
                        ).matcher(data);

                    if (!matcher.matches()) {
                        System.out.println(
                            "Format ubah belum sesuai. Ketik BANTUAN.\n"
                        );
                        continue;
                    }

                    int id = Integer.parseInt(matcher.group(1));
                    String judul = matcher.group(2);
                    String genre = matcher.group(3);
                    int durasi = Integer.parseInt(matcher.group(4));
                    String studio = matcher.group(5);

                    int index = cariIndex(daftarFilm, id);

                    if (index == -1) {
                        System.out.println(
                            "Tidak ada film dengan ID " +
                            id + ".\n"
                        );
                    } else {
                        Film film = daftarFilm.get(index);

                        film.setJudul(judul);
                        film.setGenre(genre);
                        film.setDurasi(durasi);
                        film.setStudio(studio);

                        System.out.println(
                            "Data film ID " + id +
                            " telah diperbarui.\n"
                        );
                    }

                } else if (perintah.equals("HAPUS")) {

                    if (bagian.length < 2) {
                        System.out.println(
                            "Masukkan ID yang valid.\n"
                        );
                        continue;
                    }

                    int id = Integer.parseInt(
                        bagian[1].trim()
                    );

                    int index = cariIndex(daftarFilm, id);

                    if (index == -1) {
                        System.out.println(
                            "Tidak ada film dengan ID " +
                            id + ".\n"
                        );
                    } else {
                        daftarFilm.remove(index);

                        System.out.println(
                            "Data film ID " + id +
                            " telah dihapus.\n"
                        );
                    }

                } else if (perintah.equals("CARI")) {

                    if (bagian.length < 2) {
                        System.out.println(
                            "Masukkan ID yang valid.\n"
                        );
                        continue;
                    }

                    int id = Integer.parseInt(
                        bagian[1].trim()
                    );

                    int index = cariIndex(daftarFilm, id);

                    if (index == -1) {
                        System.out.println(
                            "Film dengan ID " + id +
                            " tidak ditemukan.\n"
                        );
                    } else {
                        tampilkanSatu(
                            daftarFilm.get(index)
                        );

                        System.out.println();
                    }

                } else if (perintah.equals("DAFTAR")) {

                    tampilkanSemua(daftarFilm);

                } else if (perintah.equals("BANTUAN")) {

                    panduan();

                } else if (!perintah.equals("KELUAR")) {

                    System.out.println(
                        "Perintah tidak dikenali. Ketik BANTUAN untuk melihat pilihan.\n"
                    );
                }

            } catch (Exception e) {

                System.out.println(
                    "Format input belum sesuai. Ketik BANTUAN.\n"
                );
            }

        } while (!perintah.equals("KELUAR"));

        input.close();

        outro();
    }
}
