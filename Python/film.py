# class cinema film
class Film:
    def __init__(self, id=0, judul="", genre=""):
        self.__id = id
        self.__judul = judul
        self.__genre = genre

    # Getter
    def getId(self):
        return self.__id

    def getJudul(self):
        return self.__judul

    def getGenre(self):
        return self.__genre

    # Setter
    def setId(self, id):
        self.__id = id

    def setJudul(self, judul):
        self.__judul = judul

    def setGenre(self, genre):
        self.__genre = genre