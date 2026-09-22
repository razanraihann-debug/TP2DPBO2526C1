<?php

// class cinema film
class Film {
    private int $id;
    private string $judul;
    private string $genre;

    // Constructor default / berparameter
    public function __construct(int $id = 0, string $judul = "", string $genre = "") {
        $this->id    = $id;
        $this->judul = $judul;
        $this->genre = $genre;
    }

    // Getter
    public function getId(): int      { return $this->id; }
    public function getJudul(): string { return $this->judul; }
    public function getGenre(): string { return $this->genre; }

    // Setter
    public function setId(int $id): void           { $this->id = $id; }
    public function setJudul(string $judul): void  { $this->judul = $judul; }
    public function setGenre(string $genre): void  { $this->genre = $genre; }
}