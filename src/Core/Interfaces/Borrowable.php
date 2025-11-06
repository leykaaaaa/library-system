<?php
namespace App\Core\Interfaces;

use App\Entities\Borrower;

interface Borrowable {
    public function borrowBook(string $bookTitle, Borrower $borrower): void;
    public function returnBook(string $bookTitle, string $borrowerId): void;
}
