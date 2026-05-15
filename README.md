# Booksdungeon – Setup Guide

## Folder Structure

```
Booksdungeon/
├── index.php           ← Homepage (Featured, Bestsellers, New Arrivals)
├── booklogin.php       ← Login + Register (tabbed)
├── bookwishlist.php    ← Wishlist page
├── bookcart.php        ← Cart page with qty controls + order summary
├── config.php          ← DB connection + session_start()
├── addtocart.php       ← POST handler — adds book to cart
├── addtowishlist.php   ← POST handler — adds book to wishlist
├── removecart.php      ← GET handler — removes from cart
├── removewishlist.php  ← GET handler — removes from wishlist
├── updatecart.php      ← POST handler — increase/decrease qty
├── logout.php          ← Destroys session, redirects home
├── database.sql        ← Full DB schema + seed data
├── bookstyle.css       ← Your existing CSS (unchanged)
└── bookstoreimages/    ← Your existing images folder
```

## Step 1 — Create the Database

1. Open **phpMyAdmin** (or MySQL CLI)
2. Run the contents of `database.sql`
   - Creates the `bookstore` database
   - Creates all 4 tables: `bookstorelogin`, `books`, `wishlist`, `cart`
   - Seeds all 10 books with prices, images, descriptions

## Step 2 — Configure DB Connection

Open `config.php` and update if needed:

```php
$conn = new mysqli("localhost", "root", "", "bookstore");
//                  ↑ host      ↑ user  ↑ pass  ↑ db name
```

If your MySQL has a password, add it in the third argument.

## Step 3 — Place Files in XAMPP/WAMP

Copy the entire `Booksdungeon/` folder into:
- **XAMPP**: `C:/xampp/htdocs/Booksdungeon/`
- **WAMP**: `C:/wamp64/www/Booksdungeon/`

## Step 4 — Run

Open your browser and go to:
```
http://localhost/Booksdungeon/index.php
```

## How the Flow Works

```
Visit index.php
     ↓
Click Login → booklogin.php
     ↓
Session stores: $_SESSION['user_id'] + $_SESSION['fullname']
     ↓
Back on index.php — wishlist hearts + Add to Cart use POST forms
     ↓
Add to Cart → addtocart.php → inserts into cart table → back to index
Add to Wishlist → addtowishlist.php → inserts into wishlist table → back to index
     ↓
bookcart.php  → shows items, qty controls, subtotals, grand total
bookwishlist.php → shows saved books, Move to Cart / Remove buttons
     ↓
Logout → logout.php → destroys session → back to index
```

## Key Decisions Made

| Issue | Fix Applied |
|---|---|
| `<a><button>` nesting | Replaced with `<form><button type="submit">` |
| JS-only wishlist toggle | POST form to `addtowishlist.php` |
| Hardcoded cart badge "3" | Dynamic count from DB |
| `.html` links in nav | Changed to `.php` |
| No duplicate protection | `INSERT IGNORE` for wishlist, `ON DUPLICATE KEY UPDATE` for cart |
| No login redirect | All action files check `$_SESSION['user_id']` |
| Passwords stored as plain text | `password_hash()` on register, `password_verify()` on login |