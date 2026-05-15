-- ============================================================
-- Booksdungeon Database Setup
-- Run this in phpMyAdmin or MySQL CLI
-- ============================================================

CREATE DATABASE IF NOT EXISTS bookstore;
USE bookstore;

-- Users table
CREATE TABLE IF NOT EXISTS bookstorelogin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Books table
CREATE TABLE IF NOT EXISTS books (
    book_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    author VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    original_price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    description TEXT,
    category VARCHAR(100),
    rating DECIMAL(2,1) DEFAULT 4.0,
    review_count INT DEFAULT 0
);

-- Wishlist table
CREATE TABLE IF NOT EXISTS wishlist (
    wishlist_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    book_id INT NOT NULL,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_wishlist (user_id, book_id),
    FOREIGN KEY (user_id) REFERENCES bookstorelogin(id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES books(book_id) ON DELETE CASCADE
);

-- Cart table
CREATE TABLE IF NOT EXISTS cart (
    cart_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    book_id INT NOT NULL,
    quantity INT DEFAULT 1,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_cart (user_id, book_id),
    FOREIGN KEY (user_id) REFERENCES bookstorelogin(id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES books(book_id) ON DELETE CASCADE
);

-- ============================================================
-- Seed Books Data
-- ============================================================

INSERT INTO books (book_id, title, author, price, original_price, image, description, category, rating, review_count) VALUES
(1,  'The Tattooist of Auschwitz', 'Heather Morris',   285, 599,  'https://covers.openlibrary.org/b/isbn/9781250301697-M.jpg', 'Based on the true story of Lale and Gita Sokolov — two Slovakian Jews who survived Auschwitz and built a new life in Australia.', 'Fiction',   4.9, 4821),
(2,  'Atomic Habits',              'James Clear',       349, 699,  'https://covers.openlibrary.org/b/isbn/9780735211292-M.jpg', 'An easy and proven way to build good habits and break bad ones. Tiny changes, remarkable results.',                                'Self-Help', 4.9, 9314),
(3,  'The Alchemist',              'Paulo Coelho',      199, 399,  'https://covers.openlibrary.org/b/isbn/9780062315007-M.jpg', 'A magical fable about following your dream. A young shepherd travels from Spain to Egypt in search of treasure buried near the Pyramids.', 'Fiction', 4.4, 6208),
(4,  'Rich Dad Poor Dad',          'Robert T. Kiyosaki',249, 499,  'https://covers.openlibrary.org/b/isbn/9781612680194-M.jpg', 'What the rich teach their kids about money that the poor and middle class do not.',                                               'Business',  4.4, 7102),
(5,  'The Power of Now',           'Eckhart Tolle',     299, 550,  'https://covers.openlibrary.org/b/isbn/9781577314806-M.jpg', 'A guide to spiritual enlightenment that has transformed millions of lives.',                                                       'Self-Help', 4.8, 5430),
(6,  'Ikigai',                     'Héctor García',     225, 450,  'https://covers.openlibrary.org/b/isbn/9780143130727-M.jpg', 'The Japanese secret to a long and happy life. Finding your reason for being.',                                                     'Self-Help', 4.7, 3985),
(7,  'Sapiens: A Brief History',   'Yuval Noah Harari', 399, 799,  'https://covers.openlibrary.org/b/isbn/9780062316097-M.jpg', 'A bold, wide-ranging and provocative history of humankind from the Stone Age to the twenty-first century.',                         'History',   4.8, 8721),
(8,  'The Midnight Library',       'Matt Haig',         315, 599,  'https://covers.openlibrary.org/b/isbn/9780525559474-M.jpg', 'Between life and death there is a library, and within that library the shelves go on forever.',                                    'Fiction',   4.3, 6540),
(9,  '1984',                       'George Orwell',     150, 299,  'https://covers.openlibrary.org/b/isbn/9780451524935-M.jpg', 'A dystopian social science fiction novel and cautionary tale about the dangers of totalitarianism.',                               'Classics',  4.9, 11203),
(10, 'Think and Grow Rich',        'Napoleon Hill',     189, 350,  'https://covers.openlibrary.org/b/isbn/9781585424337-M.jpg', 'The landmark bestseller now revised and updated for the 21st century.',                                                           'Business',  4.3, 4120);