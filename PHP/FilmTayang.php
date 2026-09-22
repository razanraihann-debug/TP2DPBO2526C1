<?php

require_once "FilmBioskop.php";

class FilmTayang extends FilmBioskop {
    private int $hargaTiket;
    private string $jamTayang;
    private string $statusTayang;

    // Constructor default / berparameter
    public function __construct(
        int $id = 0,
        string $judul = "",
        string $genre = "",
        int $durasi = 0,
        string $studio = "",
        string $klasifikasiUsia = "",
        int $hargaTiket = 0,
        string $jamTayang = "",
        string $statusTayang = "",
        string $gambar = ""
    ) {
        parent::__construct($id, $judul, $genre, $durasi, $studio, $klasifikasiUsia, $gambar);
        $this->hargaTiket   = $hargaTiket;
        $this->jamTayang    = $jamTayang;
        $this->statusTayang = $statusTayang;
    }

    // Getter
    public function getHargaTiket(): int       { return $this->hargaTiket; }
    public function getJamTayang(): string     { return $this->jamTayang; }
    public function getStatusTayang(): string  { return $this->statusTayang; }

    // Setter
    public function setHargaTiket(int $hargaTiket): void        { $this->hargaTiket = $hargaTiket; }
    public function setJamTayang(string $jamTayang): void       { $this->jamTayang = $jamTayang; }
    public function setStatusTayang(string $statusTayang): void { $this->statusTayang = $statusTayang; }
}
