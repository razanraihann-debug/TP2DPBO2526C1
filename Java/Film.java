public class Film {
    private int id;
    private String judul;
    private String genre;
    private String studio;
    private int durasi;

    // constructor
    public Film() {
        id = 0;
        judul = "";
        genre = "";
        studio = "";
        durasi = 0;
    }

    // getter
    public int getId() {
        return id;
    }

    public String getJudul() {
        return judul;
    }

    public String getGenre() {
        return genre;
    }

    public int getDurasi() {
        return durasi;
    }

    public String getStudio() {
        return studio;
    }

    // setter
    public void setId(int nilai) {
        id = nilai;
    }

    public void setJudul(String nilai) {
        judul = nilai;
    }

    public void setGenre(String nilai) {
        genre = nilai;
    }

    public void setDurasi(int nilai) {
        durasi = nilai;
    }

    public void setStudio(String nilai) {
        studio = nilai;
    }
}