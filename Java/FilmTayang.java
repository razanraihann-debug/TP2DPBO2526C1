public class FilmTayang extends FilmBioskop {
    private int hargaTiket;
    private String jamTayang;
    private String statusTayang;

    // Constructor default
    public FilmTayang() {
        super();
        this.hargaTiket = 0;
        this.jamTayang = "";
        this.statusTayang = "";
    }

    // Constructor berparameter
    public FilmTayang(
        int id,
        String judul,
        String genre,
        int durasi,
        String studio,
        String klasifikasiUsia,
        int hargaTiket,
        String jamTayang,
        String statusTayang
    ) {
        super(id, judul, genre, durasi, studio, klasifikasiUsia);
        this.hargaTiket = hargaTiket;
        this.jamTayang = jamTayang;
        this.statusTayang = statusTayang;
    }

    // Getter
    public int getHargaTiket() { return hargaTiket; }
    public String getJamTayang() { return jamTayang; }
    public String getStatusTayang() { return statusTayang; }

    // Setter
    public void setHargaTiket(int hargaTiket) { this.hargaTiket = hargaTiket; }
    public void setJamTayang(String jamTayang) { this.jamTayang = jamTayang; }
    public void setStatusTayang(String statusTayang) { this.statusTayang = statusTayang; }
}
