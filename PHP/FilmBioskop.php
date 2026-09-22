<?php

require_once "Film.php";

class FilmBioskop extends Film {
    private int $durasi;
    private string $studio;
    private string $klasifikasiUsia;

    // Constructor default / berparameter
    public function __construct(
        int $id = 0,
        string $judul = "",
        string $genre = "",
        int $durasi = 0,
        string $studio = "",
        string $klasifikasiUsia = ""
    ) {
        parent::__construct($id, $judul, $genre);
        $this->durasi          = $durasi;
        $this->studio          = $studio;
        $this->klasifikasiUsia = $klasifikasiUsia;
    }

    // Getter
    public function getDurasi(): int             { return $this->durasi; }
    public function getStudio(): string          { return $this->studio; }
    public function getKlasifikasiUsia(): string { return $this->klasifikasiUsia; }

    // Setter
    public function setDurasi(int $durasi): void                        { $this->durasi = $durasi; }
    public function setStudio(string $studio): void                     { $this->studio = $studio; }
    public function setKlasifikasiUsia(string $klasifikasiUsia): void   { $this->klasifikasiUsia = $klasifikasiUsia; }
}
