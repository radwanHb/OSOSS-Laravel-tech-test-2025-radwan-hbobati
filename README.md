
# Product Management System - Technical Test - Radwan Hbobati OSOSS 2025 

## Project Overview

A Laravel 12 application with Filament admin panel that demonstrates:

- **Product and price list management**
- **REST API with complex query parameter handling**
- **Redis caching implementation**
- **SQLite database**


## 🚀 Installation

### 1. Clone the Repository

Clone the repository to your local machine:

```bash
git clone https://github.com/radwanHb/OSOSS-Laravel-tech-test-2025-radwan-hbobati
cd project
```

### 2. Configure Environment Variables

Copy the example environment file and update any necessary settings:

```bash
cp .env.example .env
```

### 3. Install Dependencies

Run Composer to install the required PHP dependencies:

```bash
composer install
```

### 4. Install Filament Admin Panel

Filament is used to manage resources and the admin interface. Install it by running:

```bash
php artisan filament:install --panel
```

### 5. Run Database Migrations

Run the database migrations to set up the necessary tables in your SQLite database:

```bash
php artisan migrate
```

### 6. Create Filament Admin User

Create an admin user for the Filament panel with the following command:

```bash
php artisan make:filament-user
```

Follow the prompts to set up your admin credentials.

### 7. Access the Filament Dashboard

After creating the user, access the Filament dashboard at:

[http://localhost:8000/admin](http://localhost:8000/admin)

Log in with the credentials you just created. From the dashboard, you can manage:

- **Products**: Add, update, or delete products
- **Price Lists**: Add price lists and associate them with products

## 🌐 API Endpoints

### 1. List Products

```http
GET /api/products
```

**Required Query Parameters**:

- `country_code` (string, ISO)
- `currency_code` (string, ISO)
- `date` (YYYY-MM-DD)
- `e.g.` (?country_code=US&currency_code=USD&date=2023-11-15)

This endpoint returns a list of products based on the provided query parameters.

### 2. Show Product Details

```http
GET /api/products/{product}
```

**Required Query Parameters**:

- `country_code` (string, ISO)
- `currency_code` (string, ISO)
- `date` (YYYY-MM-DD)
- `e.g.` (?country_code=US&currency_code=USD&date=2023-11-15)

This endpoint returns detailed information about a specific product.

## ⚡ Caching

Redis is used for caching in this project. Make sure Redis is installed and running:

```bash
redis-server
```

To clear the cache, use the following Artisan command:

```bash
php artisan cache:clear
```

## 🖥 Running the Application

Start the Laravel development server:

```bash
php artisan serve
```

The application will be accessible at `http://localhost:8000`.

## 📝 Conclusion

This project is designed to demonstrate basic CRUD operations using Filament, handle complex API queries, and implement Redis caching for optimized performance for OSOSS technical test.


