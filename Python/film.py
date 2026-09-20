class Film:
    # constructor
    def __init__(self):
        self.__id = 0
        self.__judul = ""
        self.__genre = ""
        self.__studio = ""
        self.__durasi = 0

    # getter
    def getId(self):
        return self.__id

    def getJudul(self):
        return self.__judul

    def getGenre(self):
        return self.__genre

    def getDurasi(self):
        return self.__durasi

    def getStudio(self):
        return self.__studio

    # setter
    def setId(self, nilai):
        self.__id = nilai

    def setJudul(self, nilai):
        self.__judul = nilai

    def setGenre(self, nilai):
        self.__genre = nilai

    def setDurasi(self, nilai):
        self.__durasi = nilai

    def setStudio(self, nilai):
        self.__studio = nilai