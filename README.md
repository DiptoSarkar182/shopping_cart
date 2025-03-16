<h1>Simple Cart Application</h1>

This is a simple e-commerce-like web application built with Laravel, Blade templates, 
and vanilla JavaScript. It implements a product listing, shopping cart functionality, 
discount logic, and optional product search, all without a database (using hardcoded 
product data). The project adheres to the specified requirements, including dynamic 
updates using JavaScript and a clean UI styled with Bootstrap.

<h2>Project Overview</h2>

This project is a demonstration of a basic shopping cart application built with Laravel. 
It allows users to browse a list of products, add/remove items to/from a cart, adjust 
quantities, and apply discounts dynamically. The application uses session storage to 
persist the cart data and vanilla JavaScript for dynamic updates without page reloads. 
The UI is styled using Bootstrap for a clean and professional look.

<h2>Features</h2>

**Product Listing:** 
* Displays a list of products with name, price (in USD), and an "Add to Cart" button.
* Dynamically updates the button to "Remove from Cart" if the product is in the cart.

**Shopping Cart:** 
* Users can add/remove products to/from their cart.
* Supports increasing/decreasing quantities directly in the cart.
* Uses Laravel session storage to persist the cart.
* Displays the cart total dynamically using JavaScript.

**Discount Logic:** 
* Applies a 10% discount if the cart contains 3 or more items (based on total quantity).
* Discount is calculated and updated dynamically using JavaScript.

**Product Search:**
* Includes a search bar to filter products by name (server-side filtering).
**Basic Styling:**
* Uses Bootstrap 5 for a clean and professional UI design.

<h2>Setup Instructions</h2>
Follow these steps to set up and run the project locally.

**Prerequisites**

* PHP 8.2
* Composer (latest version)
* Node.js and npm
* Git

**Installation**

1. Clone the repository to your local machine:
   ```bash
   git clone https://github.com/DiptoSarkar182/shopping_cart.git
   ```
2. Navigate to the project directory:
   ```bash
   cd simple-cart
   ```
3. Install Dependencies: Install PHP and JavaScript dependencies using Composer and npm:
   ```bash
   composer install
   npm install
   ```
4. Environment Configuration: Copy the `.env.example` file to `.env`:
   ```bash
    cp .env.example .env
    ```
5. Generate Application Key: Generate a new application key:
   ```bash
   php artisan key:generate
   ```
6. Run the Application: Start the Laravel development server:
   ```bash
   composer dev
   ```
7. Access the Application: Open your browser and visit `http://localhost:8000` to view the application.
