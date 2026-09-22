import sys, os
sys.path.insert(0, os.path.abspath(os.path.dirname(__file__)))
from FilmBioskop import FilmBioskop


class FilmTayang(FilmBioskop):
    def __init__(self, id=0, judul="", genre="", durasi=0, studio="",
                 klasifikasiUsia="", hargaTiket=0, jamTayang="", statusTayang=""):
        super().__init__(id, judul, genre, durasi, studio, klasifikasiUsia)
        self.__hargaTiket = hargaTiket
        self.__jamTayang = jamTayang
        self.__statusTayang = statusTayang

    # Getter
    def getHargaTiket(self):
        return self.__hargaTiket

    def getJamTayang(self):
        return self.__jamTayang

    def getStatusTayang(self):
        return self.__statusTayang

    # Setter
    def setHargaTiket(self, hargaTiket):
        self.__hargaTiket = hargaTiket

    def setJamTayang(self, jamTayang):
        self.__jamTayang = jamTayang

    def setStatusTayang(self, statusTayang):
        self.__statusTayang = statusTayang
