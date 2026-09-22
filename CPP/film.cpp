#include <string>

using namespace std;

//class cinema film
class Film {
private:
    int id;
    string judul;
    string genre;

public:
    Film() {
        id = 0;
        judul = "";
        genre = "";
    }

    Film(int id, string judul, string genre) {
        this->id = id;
        this->judul = judul;
        this->genre = genre;
    }

    int getId() const { return id; }
    string getJudul() const { return judul; }
    string getGenre() const { return genre; }

    void setId(int id) { this->id = id; }
    void setJudul(string judul) { this->judul = judul; }
    void setGenre(string genre) { this->genre = genre; }

    virtual ~Film() {
    }
};