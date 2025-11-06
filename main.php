<?php
require_once __DIR__ . '/src/Autoloader.php';

use App\Core\Library;
use App\Entities\{Book, Borrower};
use App\Core\Exceptions\{BookNotFoundException, BookUnavailableException};

$library = new Library();

// Preloaded Books
$library->addBook(new Book("The Alchemist", "Paulo Coelho"));
$library->addBook(new Book("1984", "George Orwell"));
$library->addBook(new Book("To Kill a Mockingbird", "Harper Lee"));

echo "=== LIBRARY MANAGEMENT SYSTEM ===\n";

while (true) {
    echo "\n1. View Books\n";
    echo "2. Borrow Book\n";
    echo "3. Return Book\n";
    echo "4. View Transactions\n";
    echo "5. Exit\n";
    echo "Choose an option: ";
    $choice = intval(fgets(STDIN));

    switch ($choice) {
        case 1:
            $library->listBooks();
            break;

        case 2:
            echo "Enter your name: ";
            $name = trim(fgets(STDIN));
            echo "Enter your ID: ";
            $id = trim(fgets(STDIN));
            echo "Enter your year level: ";
            $yearLevel = trim(fgets(STDIN));
            echo "Enter book title to borrow: ";
            $bookTitle = trim(fgets(STDIN));

            try {
                $borrower = new Borrower($name, $id, $yearLevel);
                $library->borrowBook($bookTitle, $borrower);
            } catch (BookNotFoundException | BookUnavailableException $e) {
                echo "Error: " . $e->getMessage() . "\n";
            }
            break;

        case 3:
            echo "Enter your ID: ";
            $id = trim(fgets(STDIN));
            echo "Enter book title to return: ";
            $bookTitle = trim(fgets(STDIN));

            try {
                $library->returnBook($bookTitle, $id);
            } catch (BookNotFoundException $e) {
                echo "Error: " . $e->getMessage() . "\n";
            }
            break;

        case 4:
            $library->showTransactions();
            break;

        case 5:
            echo "Goodbye!\n";
            exit;

        default:
            echo "Invalid choice.\n";
    }
}
