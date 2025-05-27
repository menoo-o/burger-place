Burger Bonanza
Burger Bonanza is a web application for ordering chicken burgers, built with PHP, MySQL, and XAMPP. It features a user-friendly interface with a menu, persistent shopping cart, separate checkout page, and order history, styled in an orange (#FF6200) and snow-white (#FFF6F0) theme. The project uses a MySQL database (burger_place) to store menu items, orders, and cart data, with prices in Pakistani Rupees (Rs.).
Features

Menu Display: Shows chicken burgers (e.g., Zinger Burger, Crunch Burger) with images, descriptions, and prices, fetched from the menu_items table.
Persistent Cart: Saves cart items to the cart_items table on every action (add, update, remove), ensuring persistence across page refreshes using PHP sessions.
Separate Checkout Page: Users proceed to checkout.php to enter name, address, and select cash on delivery, with orders saved to orders and order_items tables.
Order History: Displays past orders with details (ID, date, items, total) from the database.
Responsive Theme: Non-responsive design with a vibrant orange and snow-white color scheme, including a hero banner and menu image (images/chicken_burgers.jpg).
Cart Popup: Auto-opens for 2.5 seconds with a fade-out animation when items are added.

Prerequisites

XAMPP: Version with PHP 8.x, Apache, MySQL, and phpMyAdmin (e.g., XAMPP 8.2).
Web Browser: Chrome, Firefox, or similar.
Operating System: Windows (tested on Windows 10/11; adaptable to macOS/Linux with XAMPP).
Disk Space: ~10 MB for project files and database.

Project Structure
burger_place/
├── index.php              # Main page (menu, cart popup, order history)
├── checkout.php           # Checkout page (cart summary, customer form)
├── styles.css             # CSS for orange/snow-white theme
├── get_menu_items.php     # Fetches all menu items
├── get_menu_item.php      # Fetches single menu item
├── process_order.php      # Handles order submission
├── get_order_history.php  # Fetches order history
├── save_cart.php          # Saves cart to database
├── get_cart.php           # Retrieves cart from database
├── clear_cart.php         # Clears cart after checkout
├── README.md              # This documentation
├── includes/              # Shared components
│   ├── navbar.php         # Navigation bar
│   ├── hero.php           # Hero banner
│   ├── main-content.php   # Menu, cart popup, order history
├── images/                # Local images
│   ├── chicken_burgers.jpg # Menu banner image
├── database/              # Database export
│   ├── burger_place.sql   # SQL file with schema and data

Setup Instructions
Follow these steps to run Burger Bonanza on your local XAMPP setup.
1. Extract the Project

Unzip burger_place.zip to your XAMPP htdocs directory, e.g., C:\xampp\htdocs\burger_place.
Ensure the folder contains all files listed in the Project Structure.

2. Set Up the Database

Start XAMPP:
Open XAMPP Control Panel and start Apache and MySQL.
Access http://localhost/phpmyadmin in your browser.


Create Database:
Click New → Enter burger_place → Click Create.


Import Database:
Select the burger_place database → Import tab.
Choose database/burger_place.sql from the project folder.
Click Go to import tables (menu_items, orders, order_items, cart_items) and data (e.g., Zinger Burger, sample orders).


Verify:
Check that tables exist and contain data (e.g., menu_items has Zinger Burger with price=5.99).



3. Configure the Project

Folder Path: Confirm burger_place is in C:\xampp\htdocs\burger_place (adjust if your XAMPP uses a different htdocs path).
Database Credentials: The project uses root with no password (default XAMPP). If your MySQL has different credentials, update the connection settings in PHP files (e.g., process_order.php):$servername = "localhost";
$username = "root";
$password = "";
$dbname = "burger_place";


Permissions: Ensure C:\xampp\htdocs\burger_place has read/write permissions for Apache.

4. Run the Website

Open a browser and navigate to http://localhost/burger_place/index.php.
If XAMPP uses a non-standard port (e.g., 8080), use http://localhost:8080/burger_place/index.php.
Test the following features:
Menu: View Zinger/Crunch Burger with prices in Rs..
Cart: Add items, refresh page, and verify persistence.
Checkout: Go to checkout.php, enter name/address, submit order.
Order History: Click “View Order History” to see past orders.



Database Details
The burger_place database includes four tables:

menu_items:

Stores burgers (e.g., Zinger Burger, Crunch Burger).
Columns: id, name, description, price, image.
Sample data: id=1, name="Zinger Burger", price=5.99.


orders:

Stores order details (customer, total, date).
Columns: id, customer_id, customer_name, customer_address, total_amount, created_at.
Sample data: customer_name="John Doe", total_amount=12.48.


order_items:

Stores items per order.
Columns: id, order_id, menu_item_id, quantity, price.
Sample data: order_id=1, menu_item_id=1, quantity=1.


cart_items:

Stores cart items for persistence.
Columns: id, session_id, menu_item_id, quantity, price, created_at.
Linked to PHP session_id for anonymous users.



The database/burger_place.sql file includes both schema (CREATE TABLE) and data (INSERT INTO) for these tables.
Usage

Browse Menu:

On index.php, view chicken burgers with images from Unsplash and a local banner (images/chicken_burgers.jpg).
Prices are in Rs. (e.g., Zinger Burger = Rs.5.99).


Add to Cart:

Click “Add to Cart” on a burger.
Cart popup opens for 2.5 seconds with a fade-out animation.
Cart items are saved to cart_items and persist across refreshes.


Checkout:

Click “Proceed to Checkout” to go to checkout.php.
View cart summary, enter name and address, select cash on delivery.
Submit to save order in orders and order_items, then clear cart_items.


View Order History:

On index.php, click “View Order History” to see past orders with details.



Notes

Currency: All prices use Rs. (Pakistani Rupees).
Cart Persistence: Uses session_id for anonymous users. Add user authentication for cross-device carts.
Images: Menu item images are Unsplash URLs (require internet). chicken_burgers.jpg is local.
Non-Responsive: Designed for desktop; mobile support requires CSS updates.
Security: Uses root with no password (XAMPP default). Do not use in production without securing MySQL.
Dependencies: No external libraries; runs on standard XAMPP PHP/MySQL.

Troubleshooting

Page Not Loading:
Ensure Apache and MySQL are running in XAMPP.
Check the URL (http://localhost/burger_place/index.php).


Database Errors:
Verify burger_place.sql imported correctly.
Check table data in phpMyAdmin.


Cart Not Persisting:
Ensure PHP sessions are enabled (php.ini).
Check save_cart.php and cart_items table.


Images Missing:
Confirm images/chicken_burgers.jpg exists.
Ensure internet for Unsplash URLs.


Contact: For issues, email [your-email@example.com].

Future Enhancements

Add user authentication for personalized carts and order tracking.
Support multiple payment methods (e.g., credit card).
Make the design responsive for mobile devices.
Add order status (e.g., pending, delivered).
Include email notifications for orders.

License
This project is for educational purposes and not licensed for commercial use.

Created by DezzDev, May 2025
