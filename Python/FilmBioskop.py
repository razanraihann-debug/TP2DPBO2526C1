import sys, os
sys.path.insert(0, os.path.abspath(os.path.dirname(__file__)))
from Film import Film


class FilmBioskop(Film):
    def __init__(self, id=0, judul="", genre="", durasi=0, studio="", klasifikasiUsia=""):
        super().__init__(id, judul, genre)
        self.__durasi = durasi
        self.__studio = studio
        self.__klasifikasiUsia = klasifikasiUsia

    # Getter
    def getDurasi(self):
        return self.__durasi

    def getStudio(self):
        return self.__studio

    def getKlasifikasiUsia(self):
        return self.__klasifikasiUsia

    # Setter
    def setDurasi(self, durasi):
        self.__durasi = durasi

    def setStudio(self, studio):
        self.__studio = studio

    def setKlasifikasiUsia(self, klasifikasiUsia):
        self.__klasifikasiUsia = klasifikasiUsia
