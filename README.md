# Hidden Store – PHP & MySQL E‑Commerce Website

A simple e‑commerce site built with plain PHP (PDO) and MySQL, styled with Bootstrap 4.

## Features
- Product listing with search, category and brand filters
- Product detail page ("View more")
- Session-based shopping cart (add, update quantity, remove) with live total in the navbar
- User registration / login / logout (bcrypt password hashing)
- Checkout (Cash on Delivery) and "My Orders" page
- Admin panel: add/delete products with image upload, manage order status
- Contact form (saved to the database)
- Security basics: prepared statements, CSRF tokens, output escaping, validated image uploads

## Requirements
PHP 7.4+ (PDO MySQL + fileinfo extensions), MySQL/MariaDB, Apache (XAMPP / WAMP / LAMP).

## Setup (XAMPP)
1. Copy this folder to `htdocs/HiddenStore`.
2. Start Apache and MySQL, then open **phpMyAdmin**.
3. Import `database/hiddenstore.sql` (it creates the `hiddenstore` database).
4. Edit DB credentials in `includes/config.php` if needed.
5. Visit **http://localhost/HiddenStore/**

## Default admin login
- Email: `admin@hiddenstore.com`
- Password: `admin123`

**Change this immediately**: register a new user, set `is_admin = 1` for it in phpMyAdmin, then delete the default admin.

## Project structure
```
HiddenStore/
├── index.php          # Home / product listing
├── product.php        # Product details
├── cart.php           # Cart
├── checkout.php       # Place order
├── orders.php         # User's orders
├── login.php, register.php, logout.php, contact.php
├── admin/             # Admin product + order management
├── includes/          # config, helpers, header, footer
├── assets/            # CSS, placeholder image
├── uploads/           # Uploaded product images
└── database/hiddenstore.sql
```

## Notes
- Don't commit real database passwords to a public repository.
- Product images are optional; a placeholder is shown when none is uploaded.
