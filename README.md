# Library Management System

##  Title Page

**System Title:** Library Management System  
**Course:** ITP03 – Object-Oriented Programming  
**Language:** PHP  
**Date:** November 2025  
**Group:** Group 6  

**Developers:**  
- TULAGAN, ANGELICA G.  
- PRESTOZA, CHRISTIAN LLOYD J.  
- NABONG, LUIS CHRISTIAN M.  
- ABELLERA, SAMUEL ARPER T.  
- PERALTA, GABRIEL MATTHEW V.  

---

## System Description

The **Library Management System** is a PHP-based console application that allows users to borrow and return books efficiently.  
It records all transactions in a JSON file, ensuring data persistence between sessions.  
The system implements object-oriented programming principles, such as **encapsulation**, **abstraction**, and **interface usage**.  
Borrowers are tracked using their **name, ID, and year level**, allowing accurate return validation.

---

## Features

Add and list available books  
Borrow a book with borrower details (**Name, ID, Year Level**)  
Return a book using only the borrower’s ID  
View complete transaction history  
Automatically save and load records from a JSON file  

---

## How to Run

1. Open the project folder in your terminal.
2. Run the main program:
   ```bash
   php main.php
3.Follow the on-screen menu to:

View books
Borrow a book
Return a book
View transactions

---
## Project Structure

```bash
library-system/
│
├── main.php
├── transactions.json
│
└── src/
    ├── Core/
    │   ├── Library.php
    │   └── Interfaces/
    │       └── Borrowable.php
    │
    └── Entities/
        ├── Book.php
        ├── Borrower.php
        └── Transaction.php


  

