#include <string>

#include "Film.cpp"

using namespace std;

class FilmBioskop : public Film {
private:
    int durasi;
    string studio;
    string klasifikasiUsia;

public:
    // Constructor default
    FilmBioskop() : Film() {
        durasi = 0;
        studio = "";
        klasifikasiUsia = "";
    }

    // Constructor berparameter
    FilmBioskop(
        int id,
        string judul,
        string genre,
        int durasi,
        string studio,
        string klasifikasiUsia
    ) : Film(id, judul, genre) {

        this->durasi = durasi;
        this->studio = studio;
        this->klasifikasiUsia = klasifikasiUsia;
    }

    // Getter
    int getDurasi() const {
        return durasi;
    }

    string getStudio() const {
        return studio;
    }

    string getKlasifikasiUsia() const {
        return klasifikasiUsia;
    }

    // Setter
    void setDurasi(int durasi) {
        this->durasi = durasi;
    }

    void setStudio(string studio) {
        this->studio = studio;
    }

    void setKlasifikasiUsia(string klasifikasiUsia) {
        this->klasifikasiUsia = klasifikasiUsia;
    }

    // Destructor
    ~FilmBioskop() override {
    }
};