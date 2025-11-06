<?php
namespace App\Entities;

class Book {
    private $title;
    private $author;
    private $available = true;

    public function __construct($title, $author) {
        $this->title = $title;
        $this->author = $author;
    }

    // Encapsulation with Magic Getter
    public function __get($property) {
        return $this->$property ?? null;
    }

    public function borrow() {
        $this->available = false;
    }

    public function returnBook() {
        $this->available = true;
    }

    public function isAvailable() {
        return $this->available;
    }

    public function __toString() {
        $status = $this->available ? "Available" : "Borrowed";
        return "{$this->title} by {$this->author} ({$status})";
    }
}
