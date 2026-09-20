<?php
class Film {
    private $id;
    private $judul;
    private $genre;
    private $durasi;
    private $studio;
    private $gambar;

    // constructor (initializer)
    public function __construct() {
        $this->id = 0;
        $this->judul = "";
        $this->genre = "";
        $this->studio = "";
        $this->durasi = 0;
        $this->gambar = "";
    }

    // Getter methods
    public function getId() { return $this->id; }
    public function getJudul() { return $this->judul; }
    public function getGenre() { return $this->genre; }
    public function getDurasi() { return $this->durasi; }
    public function getStudio() { return $this->studio; }
    public function getGambar() { return $this->gambar; }

    // Setter methods
    public function setId($nilai) { $this->id = $nilai; }
    public function setJudul($nilai) { $this->judul = $nilai; }
    public function setGenre($nilai) { $this->genre = $nilai; }
    public function setDurasi($nilai) { $this->durasi = $nilai; }
    public function setStudio($nilai) { $this->studio = $nilai; }
    public function setGambar($nilai) { $this->gambar = $nilai; }
}