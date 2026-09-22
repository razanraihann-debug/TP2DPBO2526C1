public class FilmBioskop extends Film {
    private int durasi;
    private String studio;
    private String klasifikasiUsia;

    // Constructor default
    public FilmBioskop() {
        super();
        this.durasi = 0;
        this.studio = "";
        this.klasifikasiUsia = "";
    }

    // Constructor berparameter
    public FilmBioskop(
        int id,
        String judul,
        String genre,
        int durasi,
        String studio,
        String klasifikasiUsia
    ) {
        super(id, judul, genre);
        this.durasi = durasi;
        this.studio = studio;
        this.klasifikasiUsia = klasifikasiUsia;
    }

    // Getter
    public int getDurasi() { return durasi; }
    public String getStudio() { return studio; }
    public String getKlasifikasiUsia() { return klasifikasiUsia; }

    // Setter
    public void setDurasi(int durasi) { this.durasi = durasi; }
    public void setStudio(String studio) { this.studio = studio; }
    public void setKlasifikasiUsia(String klasifikasiUsia) { this.klasifikasiUsia = klasifikasiUsia; }
}
