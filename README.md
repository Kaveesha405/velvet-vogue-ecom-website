# Velvet Vogue - E-Commerce Website

A branded, multi-page e-commerce website built for **Velvet Vogue**, a contemporary fashion clothing brand. Developed as part of an HND Web Design and Development assignment, covering server infrastructure, front-end and back-end development, database design, testing, and QA evaluation.

***

## Features

- Home page with hero slider, category tiles, New Arrivals, and Best Sellers sections
- Product catalog with category browsing (Men, Women, Kids, Accessories, Casual, Formal, Footwear)
- Product detail pages with size, colour, and quantity selection
- Shopping cart with add, update, remove, and real-time total calculation
- Checkout for both guest and registered users
- Keyword-based product search
- User registration, login, logout, and profile page
- Contact form that stores customer inquiries for admin review
- Admin dashboard with product, order, user, and feedback management

***

## Tech Stack

| Layer           | Technology                        |
|-----------------|-----------------------------------|
| Front-end       | HTML5, CSS3, JavaScript           |
| Back-end        | PHP                               |
| Database        | MySQL                             |
| Design          | Canva                             |
| Code Editor     | Visual Studio Code                |
| Version Control | Git & GitHub                      |
| Testing         | Chrome DevTools, Lighthouse, WAVE |

***

## Database

The site uses a single MySQL database named `velvetvogue`. It contains the following tables:

- `adminusers` - admin account credentials for dashboard access
- `users` - registered customer login and address information
- `categories` - product category definitions
- `products` - product records including name, description, price, rating, sizes, and colours
- `product_categories` - many-to-many junction table linking products to categories
- `cart` - items added to cart by users, including size and colour choices
- `orders` - completed checkout records for both registered and guest users
- `order_items` - individual product lines per order with quantity, size, colour, and final price
- `inquiries` - customer messages submitted via the Contact page, with status tracking (new, read, replied)

***

## Pages

**Customer-facing:**
1. Home Page (`HomePage.php`)
2. Men's Category (`men.php`)
3. Women's Category (`women.php`)
4. Kids' Category (`kids.php`)
5. Accessories (`accessories.php`)
6. Casual (`casual.php`)
7. Formal (`formal.php`)
8. Footwear (`footwear.php`)
9. Product Detail Page (`product-details.php`)
10. Shopping Cart (`cart.php`)
11. Checkout (`checkout.php`)
12. Search Results (`search.php`)
13. New Arrivals (`newArrival.php`)
14. Best Sellers (`bestSellers.php`)
15. Login (`login.php` / `loginprocess.php`)
16. Register (`RegisterPage.php` / `registerprocess.php`)
17. Profile (`profile.php`)
18. Contact (`contact.php`)

**Admin:**
1. Admin Login (`adminlogin.php`)
2. Admin Dashboard (`admindashboard.php`)
3. Product Management (`adminproducts.php`)
4. Order Management (`adminorders.php`)
5. User Management (`adminusers.php`)
6. Feedback & Inquiries (`adminfeedback.php`)

***

## Getting Started

### Prerequisites

- PHP 7.4 or later
- MySQL 5.7 or later
- A local server environment (XAMPP or WAMP)

### Steps

1. Clone the repository
   ```bash
   git clone https://github.com/your-username/velvet-vogue-ecom-website.git
   ```

2. Import the database
   - Open phpMyAdmin and import `velvet_vogue.sql` to create the `velvetvogue` database

3. Configure the database connection
   - Update `dbconnect.php` with your MySQL host, username, and password

4. Start your local server
   - Place the project folder in `htdocs` (XAMPP) or `www` (WAMP)
   - Access the site at `http://localhost/velvet-vogue-ecom-website`

***

## Project Structure

```
velvet-vogue-ecom-website/
├── assets/
│   ├── css/
│   ├── images/
├── admin/
│   ├── admindashboard.php
│   ├── adminproducts.php
│   ├── adminorders.php
│   ├── adminusers.php
│   ├── adminfeedback.php
│   └── adminlogin.php
├── includes/
│   ├── header.php
│   ├── footer.php
│   ├── dbconnect.php
│   ├── getproducts.php
│   └── colormapping.php
├── HomePage.php
├── men.php
├── women.php
├── kids.php
├── accessories.php
├── casual.php
├── formal.php
├── footwear.php
├── product-details.php
├── cart.php
├── addtocart.php
├── checkout.php
├── search.php
├── newArrival.php
├── bestSellers.php
├── login.php
├── loginprocess.php
├── RegisterPage.php
├── registerprocess.php
├── logout.php
├── profile.php
├── contact.php
├── script.js
├── database/
│   └── velvet_vogue.sql
└── README.md
```

***

## Author

**Kaveesha Amiru** | Student ID: 00272845  
HND in Computing - Web Design and Development Module  

> This project was developed for academic purposes.
