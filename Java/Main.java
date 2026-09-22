import java.util.ArrayList;
import java.util.List;
import java.util.Scanner;

public class Main {

    // FUNGSI MENCARI ID FILM
    static boolean idSudahAda(List<FilmTayang> daftarFilm, int id) {
        for (FilmTayang film : daftarFilm) {
            if (film.getId() == id) return true;
        }
        return false;
    }

    // FUNGSI MENGHITUNG LEBAR KOLOM
    static int[] hitungLebar(List<FilmTayang> daftarFilm) {
        int lebarId     = 2;
        int lebarJudul  = 10;
        int lebarGenre  = 5;
        int lebarDurasi = 6;
        int lebarStudio = 6;
        int lebarUsia   = 10;
        int lebarHarga  = 11;
        int lebarJam    = 8;
        int lebarStatus = 10;

        for (FilmTayang film : daftarFilm) {
            lebarId     = Math.max(lebarId,     String.valueOf(film.getId()).length());
            lebarJudul  = Math.max(lebarJudul,  film.getJudul().length());
            lebarGenre  = Math.max(lebarGenre,  film.getGenre().length());
            lebarDurasi = Math.max(lebarDurasi, String.valueOf(film.getDurasi()).length());
            lebarStudio = Math.max(lebarStudio, film.getStudio().length());
            lebarUsia   = Math.max(lebarUsia,   film.getKlasifikasiUsia().length());
            lebarHarga  = Math.max(lebarHarga,  String.valueOf(film.getHargaTiket()).length());
            lebarJam    = Math.max(lebarJam,    film.getJamTayang().length());
            lebarStatus = Math.max(lebarStatus, film.getStatusTayang().length());
        }

        return new int[]{lebarId, lebarJudul, lebarGenre, lebarDurasi, lebarStudio, lebarUsia, lebarHarga, lebarJam, lebarStatus};
    }

    // FUNGSI MEMBUAT GARIS TABEL
    static void garis(int[] w) {
        System.out.print("+");
        for (int i = 0; i < w.length; i++) {
            System.out.print("-".repeat(w[i] + 2) + "+");
        }
        System.out.println();
    }

    // FUNGSI FORMAT STRING (left/right align)
    static String padRight(String s, int n) {
        return String.format("%-" + n + "s", s);
    }

    static String padLeft(String s, int n) {
        return String.format("%" + n + "s", s);
    }

    // FUNGSI HEADER TABEL
    static void cetakHeader(int[] w) {
        garis(w);
        System.out.printf("| %s | %s | %s | %s | %s | %s | %s | %s | %s |%n",
            padRight("ID",     w[0]),
            padRight("JUDUL",  w[1]),
            padRight("GENRE",  w[2]),
            padRight("MENIT",  w[3]),
            padRight("STUDIO", w[4]),
            padRight("USIA",   w[5]),
            padRight("HARGA",  w[6]),
            padRight("JAM",    w[7]),
            padRight("STATUS", w[8])
        );
        garis(w);
    }

    // FUNGSI MENCETAK SATU BARIS FILM
    static void cetakBaris(FilmTayang film, int[] w) {
        System.out.printf("| %s | %s | %s | %s | %s | %s | %s | %s | %s |%n",
            padLeft(String.valueOf(film.getId()),            w[0]),
            padRight(film.getJudul(),                        w[1]),
            padRight(film.getGenre(),                        w[2]),
            padLeft(String.valueOf(film.getDurasi()),        w[3]),
            padRight(film.getStudio(),                       w[4]),
            padRight(film.getKlasifikasiUsia(),              w[5]),
            padLeft(String.valueOf(film.getHargaTiket()),    w[6]),
            padRight(film.getJamTayang(),                    w[7]),
            padRight(film.getStatusTayang(),                 w[8])
        );
    }

    // FUNGSI MENAMPILKAN SEMUA FILM
    static void tampilkanSemua(List<FilmTayang> daftarFilm) {
        if (daftarFilm.isEmpty()) {
            System.out.println("Belum ada film yang tersimpan.\n");
            return;
        }

        int[] w = hitungLebar(daftarFilm);
        cetakHeader(w);

        for (FilmTayang film : daftarFilm) {
            cetakBaris(film, w);
        }

        garis(w);
        System.out.println(daftarFilm.size() + " film ditampilkan.\n");
    }

    // FUNGSI PANDUAN
    static void panduan() {
        System.out.println("\n+==========================================================+");
        System.out.println("|                   PUSAT BANTUAN BIOSKOP                  |");
        System.out.println("+==========================================================+");
        System.out.println("| Teks wajib diapit tanda petik.                           |");
        System.out.println("| 1. INSERT <id> \"judul\" \"genre\" <durasi> \"studio\"         |");
        System.out.println("|    \"usia\" <harga> \"jam\" \"status\"                         |");
        System.out.println("| 2. SHOW                                                  |");
        System.out.println("| 3. HELP                                                  |");
        System.out.println("| 4. EXIT                                                  |");
        System.out.println("|                                                          |");
        System.out.println("| Contoh:                                                  |");
        System.out.println("| INSERT 106 \"Avengers\" \"Action\" 143 \"Studio 1\"            |");
        System.out.println("| \"13+\" 50000 \"19:00\" \"Tersedia\"                           |");
        System.out.println("+==========================================================+\n");
    }

    // FUNGSI DELAY
    static void delay() {
        try { Thread.sleep(300); } catch (InterruptedException e) { Thread.currentThread().interrupt(); }
    }

    // FUNGSI OUTRO
    static void outro() {
        System.out.println("\n+---------------------------------------------+");
        delay();
        System.out.println("|      Terima kasih telah memakai layanan     |");
        delay();
        System.out.println("|           pendataan film bioskop.           |");
        delay();
        System.out.println("|         Sampai bertemu di pemutaran         |");
        delay();
        System.out.println("|               film berikutnya!              |");
        delay();
        System.out.println("+---------------------------------------------+");
    }

    // FUNGSI MEMBACA TEKS DI DALAM TANDA PETIK dari string
    static String bacaTeks(Scanner sc) {
        String token = sc.next();
        if (!token.startsWith("\"")) return null;

        StringBuilder sb = new StringBuilder(token.substring(1));
        if (sb.toString().endsWith("\"")) {
            // kata tunggal dalam petik
            return sb.substring(0, sb.length() - 1);
        }

        // baca lanjutan sampai ketemu penutup petik
        while (sc.hasNext()) {
            String next = sc.next();
            sb.append(" ").append(next);
            if (next.endsWith("\"")) {
                return sb.substring(0, sb.length() - 1);
            }
        }
        return null;
    }

    // FUNGSI UTAMA
    public static void main(String[] args) {
        List<FilmTayang> daftarFilm = new ArrayList<>();

        // 5 OBJEK AWAL SEBELUM INPUT USER
        daftarFilm.add(new FilmTayang(101, "Laskar Pelangi",   "Drama",   125, "Studio 1", "SU",  40000, "13:00", "Tersedia"));
        daftarFilm.add(new FilmTayang(102, "Avengers",          "Action",  143, "Studio 2", "13+", 50000, "15:30", "Tersedia"));
        daftarFilm.add(new FilmTayang(103, "KKN Desa Penari",  "Horror",  116, "Studio 3", "17+", 45000, "19:00", "Tersedia"));
        daftarFilm.add(new FilmTayang(104, "Inside Out 2",     "Animasi",  96, "Studio 1", "SU",  35000, "10:00", "Tersedia"));
        daftarFilm.add(new FilmTayang(105, "The Batman",       "Action",  176, "Studio 4", "13+", 55000, "20:00", "Penuh"));

        System.out.println("==========================================");
        System.out.println("      SISTEM PENDATAAN FILM BIOSKOP       ");
        System.out.println("==========================================");
        System.out.println("5 film awal telah dimasukkan ke sistem.");
        System.out.println("Ketik HELP untuk melihat format perintah.\n");

        Scanner sc = new Scanner(System.in);
        String perintah = "";

        do {
            System.out.print("bioskop> ");
            if (!sc.hasNextLine()) break;

            String baris = sc.nextLine().trim();
            if (baris.isEmpty()) continue;

            // ambil perintah pertama
            String[] parts = baris.split("\\s+", 2);
            perintah = parts[0].toUpperCase();

            // PERINTAH INSERT
            if (perintah.equals("INSERT")) {
                if (parts.length < 2) {
                    System.out.println("Format tambah belum sesuai. Ketik HELP.\n");
                    continue;
                }

                try {
                    Scanner lineSc = new Scanner(parts[1]);

                    int id = lineSc.nextInt();
                    String judul = bacaTeks(lineSc);
                    String genre = bacaTeks(lineSc);
                    int durasi = lineSc.nextInt();
                    String studio = bacaTeks(lineSc);
                    String klasifikasiUsia = bacaTeks(lineSc);
                    int hargaTiket = lineSc.nextInt();
                    String jamTayang = bacaTeks(lineSc);
                    String statusTayang = bacaTeks(lineSc);

                    if (judul == null || genre == null || studio == null
                            || klasifikasiUsia == null || jamTayang == null || statusTayang == null) {
                        System.out.println("Format tambah belum sesuai. Ketik HELP.\n");
                        continue;
                    }

                    if (idSudahAda(daftarFilm, id)) {
                        System.out.println("ID " + id + " sudah terpakai.\n");
                        continue;
                    }

                    daftarFilm.add(new FilmTayang(id, judul, genre, durasi, studio, klasifikasiUsia, hargaTiket, jamTayang, statusTayang));
                    System.out.println("Film \"" + judul + "\" berhasil ditambahkan.\n");

                } catch (Exception e) {
                    System.out.println("Format tambah belum sesuai. Ketik HELP.\n");
                }

            // PERINTAH SHOW
            } else if (perintah.equals("SHOW")) {
                tampilkanSemua(daftarFilm);

            // PERINTAH HELP
            } else if (perintah.equals("HELP")) {
                panduan();

            // PERINTAH EXIT
            } else if (!perintah.equals("EXIT")) {
                System.out.println("Perintah tidak dikenali. Ketik HELP.\n");
            }

        } while (!perintah.equals("EXIT"));

        outro();
        sc.close();
    }
}
