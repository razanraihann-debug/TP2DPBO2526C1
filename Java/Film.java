// class cinema film
public class Film {
    private int id;
    private String judul;
    private String genre;

    // Constructor default
    public Film() {
        this.id = 0;
        this.judul = "";
        this.genre = "";
    }

    // Constructor berparameter
    public Film(int id, String judul, String genre) {
        this.id = id;
        this.judul = judul;
        this.genre = genre;
    }

    // Getter
    public int getId() { return id; }
    public String getJudul() { return judul; }
    public String getGenre() { return genre; }

    // Setter
    public void setId(int id) { this.id = id; }
    public void setJudul(String judul) { this.judul = judul; }
    public void setGenre(String genre) { this.genre = genre; }
}