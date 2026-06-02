# PetFinder_ubaid  

A simple PHP web application that lets users browse, book, and manage pet adoption listings. Administrators can add, edit, and delete pet information, handle bookings, and communicate with users through a built‑in reply system.

---  

## Overview  

PetFinder_ubaid provides a clean, responsive interface for:

* **Visitors** – Browse available pets, view details, and submit adoption bookings.  
* **Registered Users** – Manage personal bookings and contact support.  
* **Admins** – Add/edit pet profiles, view and respond to bookings, and maintain site content.

The project is built with core PHP, MySQL, and vanilla CSS, making it easy to host on any standard LAMP stack.

---  

## Features  

| Category | Feature |
|----------|---------|
| **User‑Facing** | • Browse pet listings (`home.php`, `index.php`) <br>• View pet details (`view_pets.php`) <br>• Book a pet (`booking.php`) <br>• Payment integration (`payment.php`, `charge.php`) <br>• View personal bookings (`my_bookings.php`) |
| **Authentication** | • User registration (`register.php`) <br>• Login / logout (`login.php`, `logout.php`) <br>• Admin login (`admin/admin_login.php`) |
| **Admin Panel** | • Dashboard (`admin/admin_home.php`) <br>• Add / edit pet info (`admin/add_pet.php`, `admin/edit_pet.php`) <br>• Add / edit site info (`admin/add_info.php`, `admin/edit_info.php`) <br>• View bookings (`admin/view_bookings.php`) <br>• Reply to users (`admin/admin_reply.php`) |
| **Support** | • Contact form (`contact_support.php`) |
| **Styling** | • Central stylesheet (`css/style.css`) |
| **Utilities** | • Config files (`config.php`, `admin/config.php`) <br>• Reusable navigation (`navbar.php`, `admin/admin_navbar.php`) |

---  

## Tech Stack  

| Layer | Technology |
|-------|------------|
| **Backend** | PHP 7.4+ |
| **Database** | MySQL (schema in `Database/petfinder_db.sql`) |
| **Frontend** | HTML5, CSS3 |
| **Payment** | Stripe (or any compatible gateway – replace keys with `YOUR_OWN_API_KEY`) |
| **Server** | Apache / Nginx (LAMP stack) |

---  

## Installation  

### 1. Prerequisites  

* PHP 7.4 or newer  
* MySQL 5.7+  
* Web server (Apache/Nginx) with `mod_rewrite` enabled  
* Composer (optional, only if you add third‑party libraries)  

### 2. Clone the repository  

```bash
git clone https://github.com/yourusername/PetFinder_ubaid.git
cd PetFinder_ubaid
```

### 3. Set up the database  

1. Create a new MySQL database, e.g. `petfinder`.  
2. Import the schema:  

```bash
mysql -u your_user -p petfinder < Database/petfinder_db.sql
```

### 4. Configure the application  

Edit the two config files and replace placeholder values with your own credentials:

* `config.php` – site‑wide settings (DB credentials, base URL, Stripe keys).  
* `admin/config.php` – admin‑specific settings (same DB credentials, optional admin API keys).

```php
// Example snippet (do NOT commit real credentials)
define('DB_HOST', 'localhost');
define('DB_NAME', 'petfinder');
define('DB_USER', 'YOUR_DB_USER');
define('DB_PASS', 'YOUR_DB_PASSWORD');

define('STRIPE_PUBLIC_KEY', 'YOUR_OWN_API_KEY');
define('STRIPE_SECRET