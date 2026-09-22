#include <string>

#include "FilmBioskop.cpp"

using namespace std;

class FilmTayang : public FilmBioskop {
private:
    int hargaTiket;
    string jamTayang;
    string statusTayang;

public:
    // Constructor default
    FilmTayang() : FilmBioskop() {
        hargaTiket = 0;
        jamTayang = "";
        statusTayang = "";
    }

    // Constructor berparameter
    FilmTayang(
        int id,
        string judul,
        string genre,
        int durasi,
        string studio,
        string klasifikasiUsia,
        int hargaTiket,
        string jamTayang,
        string statusTayang
    ) : FilmBioskop(
        id,
        judul,
        genre,
        durasi,
        studio,
        klasifikasiUsia
    ) {

        this->hargaTiket = hargaTiket;
        this->jamTayang = jamTayang;
        this->statusTayang = statusTayang;
    }

    // Getter
    int getHargaTiket() const {
        return hargaTiket;
    }

    string getJamTayang() const {
        return jamTayang;
    }

    string getStatusTayang() const {
        return statusTayang;
    }

    // Setter
    void setHargaTiket(int hargaTiket) {
        this->hargaTiket = hargaTiket;
    }

    void setJamTayang(string jamTayang) {
        this->jamTayang = jamTayang;
    }

    void setStatusTayang(string statusTayang) {
        this->statusTayang = statusTayang;
    }

    // Destructor
    ~FilmTayang() override {
    }
};