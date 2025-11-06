<?php
namespace App\Entities;

class Transaction {
    private $bookTitle;
    private $borrowerName;
    private $borrowerId;
    private $yearLevel;
    private $action;
    private $date;

    public function __construct($bookTitle, $borrowerName, $action, $borrowerId, $yearLevel) {
        $this->bookTitle = $bookTitle;
        $this->borrowerName = $borrowerName;
        $this->borrowerId = $borrowerId;
        $this->yearLevel = $yearLevel;
        $this->action = $action;
        $this->date = date("Y-m-d H:i:s");
    }

    public function __toString() {
        return "[{$this->date}] {$this->borrowerName} ({$this->yearLevel}) - ID: {$this->borrowerId} {$this->action} '{$this->bookTitle}'";
    }
}
