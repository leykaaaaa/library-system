<?php
namespace App\Core;

use App\Core\Interfaces\Borrowable;
use App\Core\Exceptions\{BookNotFoundException, BookUnavailableException};
use App\Entities\{Book, Borrower, Transaction};

class Library implements Borrowable {
    private array $books = [];
    private array $transactions = [];
    private array $borrowers = []; // Stores borrower info by ID
    private string $transactionFile;

    public function __construct() {
        // Use consistent absolute path
        $this->transactionFile = __DIR__ . '/../../transactions.json';
        $this->loadTransactions();
    }

    /** Add a new book to the library */
    public function addBook(Book $book): void {
        $this->books[] = $book;
    }

    /** List all books */
    public function listBooks(): void {
        echo "\nAvailable Books:\n";
        foreach ($this->books as $i => $book) {
            echo "[" . ($i + 1) . "] {$book}\n";
        }
    }

    /** Borrow a book (with full borrower details) */
    public function borrowBook(string $bookTitle, Borrower $borrower): void {
        foreach ($this->books as $book) {
            if (strcasecmp($bookTitle, $book->__get('title')) === 0) {
                if (!$book->isAvailable()) {
                    throw new BookUnavailableException("The book '{$bookTitle}' is already borrowed.");
                }

                $book->borrow();

                // Save borrower record
                $this->borrowers[$borrower->getId()] = $borrower;

                // Record transaction
                $transaction = new Transaction(
                    $bookTitle,
                    $borrower->getName(),
                    "borrowed",
                    $borrower->getId(),
                    $borrower->getYearLevel()
                );
                $this->transactions[] = $transaction;
                $this->saveTransactions();

                echo "{$borrower->getName()} successfully borrowed '{$bookTitle}'.\n";
                return;
            }
        }
        throw new BookNotFoundException("The book '{$bookTitle}' was not found.");
    }

    /** Return a book using borrower ID lookup */
    public function returnBook(string $bookTitle, string $borrowerId): void {
        if (!isset($this->borrowers[$borrowerId])) {
            echo "No record found for ID: {$borrowerId}\n";
            return;
        }

        $borrower = $this->borrowers[$borrowerId];

        foreach ($this->books as $book) {
            if (strcasecmp($bookTitle, $book->__get('title')) === 0) {
                if ($book->isAvailable()) {
                    echo "'{$bookTitle}' is already marked as available.\n";
                    return;
                }

                $book->returnBook();

                $transaction = new Transaction(
                    $bookTitle,
                    $borrower->getName(),
                    "returned",
                    $borrower->getId(),
                    $borrower->getYearLevel()
                );
                $this->transactions[] = $transaction;
                $this->saveTransactions();

                echo "{$borrower->getName()} (ID: {$borrowerId}) successfully returned '{$bookTitle}'.\n";
                return;
            }
        }

        throw new BookNotFoundException("The book '{$bookTitle}' was not found.");
    }

    /** Display all transactions */
    public function showTransactions(): void {
        echo "\n===== TRANSACTION LOG =====\n";
        if (empty($this->transactions)) {
            echo "No transactions recorded yet.\n";
            return;
        }

        foreach ($this->transactions as $t) {
            echo $t . "\n";
        }
    }

    /** Save transactions to JSON file */
    private function saveTransactions(): void {
        $data = [];

        foreach ($this->transactions as $t) {
            $data[] = [
                'bookTitle'     => $this->getProperty($t, 'bookTitle'),
                'borrowerName'  => $this->getProperty($t, 'borrowerName'),
                'borrowerId'    => $this->getProperty($t, 'borrowerId'),
                'yearLevel'     => $this->getProperty($t, 'yearLevel'),
                'action'        => $this->getProperty($t, 'action'),
                'date'          => $this->getProperty($t, 'date'),
            ];
        }

        // Write formatted JSON safely
        file_put_contents($this->transactionFile, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    /** Load previous transactions from JSON file */
    private function loadTransactions(): void {
        if (!file_exists($this->transactionFile)) {
            return;
        }

        $data = json_decode(file_get_contents($this->transactionFile), true);
        if (!is_array($data)) {
            return;
        }

        foreach ($data as $record) {
            $transaction = new Transaction(
                $record['bookTitle'],
                $record['borrowerName'],
                $record['action'],
                $record['borrowerId'],
                $record['yearLevel']
            );
            $this->transactions[] = $transaction;

            // Keep borrower info in memory
            $this->borrowers[$record['borrowerId']] = new Borrower(
                $record['borrowerName'],
                $record['borrowerId'],
                $record['yearLevel']
            );
        }
    }

    /** Helper: access private property via reflection */
    private function getProperty(object $object, string $property): mixed {
        $ref = new \ReflectionClass($object);
        $prop = $ref->getProperty($property);
        $prop->setAccessible(true);
        return $prop->getValue($object);
    }
}
