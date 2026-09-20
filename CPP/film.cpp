#include <iostream>
#include <string>

using namespace std;

//class cinema film
class Film {
    private:
        int id;
        string judul; 
        string genre; 
        string studio;
        int durasi;

    public:
        //constructor (initializer)
        Film(){
            id = 0; 
            judul = ""; 
            genre = "";
            studio = ""; 
            durasi = 0;
        };

        //getter
        int getId() { return id; }
        string getJudul() { return judul; }
        string getGenre() { return genre; }
        int getDurasi() { return durasi; }
        string getStudio() { return studio; }

        //setter
        void setId(int nilai) { id = nilai; }
        void setJudul(string nilai) { judul = nilai; }
        void setGenre(string nilai) { genre = nilai; }
        void setDurasi(int nilai) { durasi = nilai; }
        void setStudio(string nilai) { studio = nilai; }

        //destructor
        ~Film() {}
};