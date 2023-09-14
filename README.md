# Pet-Shop API

A Laravel Application for managing a Pet-Shop.

This repository is functionality complete. PRs (Pull Requests) and issues are welcome!

---

## 🚀 Getting Started

### 🛠 Prerequisites

- **Docker Installed**
- **Code editor of choice** (Vscode recommended, as it was used during development)

---
### 📦 Installation & Setup

1. **Clone the Repository**
    ```
    git clone git@github.com:akandeBolaji/pet-shop.git
    ```

2. **Navigate to the Project Directory**
    Switch to the repo folder
    ```
    cd pet-shop
    ```

3. **Setup Environment Variables**
    Copy the example environment file and make any required configuration changes in the .env file.
    ```
    cp .env.example .env
    ```
    🚨 Ensure the database connection in `.env` matches the database config in `docker-compose.yml`. The `.env.example` already contains the correct db config.

4. **Build and Start the Application with Docker**
    ```
    docker-compose up -d --build
    ```

5. **Generate a New Application Key**
`php artisan` command can be run directly from the project terminal using ./laravel-docker.sh. Please ensure to give proper permissions to run the script. e.g for Mac users just run `chmod +x ./laravel-docker.sh`

    ```
    ./laravel-docker.sh key:generate
    ```
    
6. **Run Database Migrations**
    ```
    ./laravel-docker.sh migrate
    ```

7. **Access the Server**
    You can now navigate to http://localhost:8080/api/v1.


8. **Link Storage with Public Folder**
    ```
    ./laravel-docker.sh storage:link
    ```

9. **Provide Necessary Permissions**
    You might need to give access to storage folder
    ```
    docker exec -it laravel_app chown -R www-data:www-data /var/www/storage
    ```
---

## 🌱 Database Seeding
Populate the database with 50 orders for 10 different users, each having a random amount of products. Orders will be randomly assigned an order status. If the order status is "paid" or "shipped", it will be assigned a payment method.
```
./laravel-docker.sh db:seed
```
🚨 Note: Before seeding, it's recommended to have a clean database. You can refresh your migrations to clear the database:
```
./laravel-docker.sh migrate:refresh
```
---
## 📘 Documentation
Access the API documentation at http://localhost:8080/api/documentation.

Default Admin Credentials (after seeding):

Email: test_admin@example.com
Password: admin
---
## 🛠 Other Useful Commands

To run unit and feature tests
`./laravel-docker.sh test` 

Generate swagger docs
`./laravel-docker.sh l5-swagger:generate`

For PHPInsights
`./laravel-docker.sh insights`

For larastan
`./vendor/bin/phpstan analyse --memory-limit=2G`

To drop docker container, rebuild and start
`docker-compose down`
`docker-compose up -d --build`