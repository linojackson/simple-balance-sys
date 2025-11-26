# Simple Balance System

A lightweight, stateless REST API for handling financial account transactions (deposits, withdrawals, and transfers). Built with **Laravel 12** and **Pest**, designed to be simple, testable, and strictly adherent to the provided technical specifications.

## 📋 Project Overview

This project implements a JSON API that manages account balances without a persistent database engine (SQL), relying on a file-based cache system to simulate storage. It focuses on:

* **Simplicity:** No unnecessary dependencies or database migrations.
* **Clean Architecture:** Separation of concerns using Services, Repositories, and DTOs.
* **Reliability:** Fully covered by automated Feature Tests using Pest.

## 🚀 Technologies

* **PHP 8.2+**
* **Laravel 12** (Framework)
* **Pest** (Testing Framework)
* **Git Flow** (Branching Strategy)

## 🏗 Architecture Decisions

Since "Durability is not a requirement" and "Keep it simple" were the main constraints, the following architectural choices were made:

1.  **Persistence:** Instead of a relational database (MySQL/Postgres), the project uses Laravel's **File Cache Driver**. This fulfills the requirement for non-durable storage while keeping the setup zero-config.
2.  **No Eloquent Models:** To avoid overhead, simple **DTOs (Data Transfer Objects)** and a custom **Repository** pattern were used instead of Eloquent Models.
3.  **Atomic Transactions:** Transfer logic is encapsulated within the Service layer to ensure data integrity during read/write operations in the cache.
4.  **API Structure:** Routes are defined in `routes/web.php` with CSRF protection disabled for specific endpoints to allow stateless API access.

## 🛠️ Installation & Setup

Follow these steps to get the project running locally:

1.  **Clone the repository:**
    ```bash
    git clone https://github.com/linojackson/simple-balance-sys.git
    cd simple-balance-sys
    ```

2.  **Install dependencies:**
    ```bash
    composer install
    ```

3.  **Configure Environment:**
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
    *Note: The `.env` is pre-configured to use `CACHE_STORE=file` and `SESSION_DRIVER=file`.*

4.  **Clear Config Cache (Important):**
    Ensure the application uses the correct file driver settings:
    ```bash
    php artisan config:clear
    ```

## 🏃‍♂️ Running the API

Start the local development server:

```bash
php artisan serve
```
