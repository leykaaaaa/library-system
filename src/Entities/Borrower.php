<?php
namespace App\Entities;

class Borrower {
    private $name;
    private $id;
    private $yearLevel;

    public function __construct($name, $id, $yearLevel) {
        $this->name = $name;
        $this->id = $id;
        $this->yearLevel = $yearLevel;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getYearLevel() {
        return $this->yearLevel;
    }

    public function __toString() {
        return "{$this->name} ({$this->yearLevel}) - ID: {$this->id}";
    }
}
