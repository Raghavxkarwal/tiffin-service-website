# Ghar Ka Tiffin — PHP & MySQL

A full-stack tiffin ordering website built with HTML5, CSS3, JavaScript, PHP and MySQL.

## Features
- Responsive home page and meal menu
- JavaScript live menu search
- Customer registration and login (password hashing)
- PHP session-based shopping cart
- Checkout and order history
- Admin dashboard, menu availability and order status management
- MySQL relational database with prepared statements
- CSRF token checks on POST forms

## Requirements
- XAMPP (Apache + PHP + MySQL) or equivalent
- PHP 8.0+ with PDO MySQL enabled
- MySQL / MariaDB
- Web browser

## Run using XAMPP
1. Copy the `tiffin-php-mysql` folder into `C:/xampp/htdocs/`.
2. Start **Apache** and **MySQL** in the XAMPP Control Panel.
3. Open `http://localhost/phpmyadmin`.
4. Choose **Import**, select `database/tiffin.sql`, and import it. The script creates the `tiffin_service` database and sample menu.
5. Check `config/database.php`. The default XAMPP username is often `root` with an empty password. Change it if your setup differs.
6. Open `http://localhost/tiffin-php-mysql/`.

## Create an admin account
1. Register a normal account on the website.
2. In phpMyAdmin, select the `tiffin_service` database and run this query, replacing the email:
```sql
UPDATE users SET role='admin' WHERE email='your-email@example.com';
```
3. Log out and log in again. The Admin link will appear.

## GitHub
Upload the project files to a GitHub repository. Do not upload database passwords, private customer data or production configuration secrets.

**Important:** GitHub Pages cannot run PHP or MySQL. GitHub can store the source code, but to make the PHP website live you need PHP/MySQL hosting (or a VPS). XAMPP is for local development.

## Before real-world launch
Add HTTPS, stronger rate limiting, email/phone verification, privacy/terms pages, backups and secure production configuration. Add payment integration only through a trusted payment provider; never store card details yourself.
