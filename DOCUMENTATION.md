# E-Commerce Backend API Documentation

## Table of Contents

1. [Project Overview](#project-overview)
2. [Technical Stack](#technical-stack)
3. [Project Structure](#project-structure)
4. [Database Schema](#database-schema)
5. [API Endpoints](#api-endpoints)
6. [Authentication](#authentication)
7. [Installation and Setup](#installation-and-setup)
8. [Configuration](#configuration)
9. [Data Models](#data-models)
10. [API Response Format](#api-response-format)
11. [Error Handling](#error-handling)
12. [Development Guidelines](#development-guidelines)

---

## Project Overview

**Project Name:** E-Commerce Backend Laravel  
**Version:** 1.0.0  
**Type:** RESTful API Backend  
**Purpose:** Complete e-commerce platform backend providing product management, user authentication, shopping cart, order processing, and payment integration.

This is a comprehensive Laravel-based REST API designed to support a full-featured e-commerce platform with user authentication, product catalog management, shopping cart functionality, order management, and payment processing capabilities.

---

## Technical Stack

### Core Framework
- **Laravel Framework:** 12.0
- **PHP Version:** 8.2+
- **Package Manager:** Composer

### Authentication & Security
- **Laravel Sanctum:** 4.3 - Token-based API authentication
- **Password Hashing:** Laravel's built-in Bcrypt implementation

### Database
- **ORM:** Eloquent ORM (Laravel)
- **Migrations:** Database versioning system
- **Relationships:** Full relational model support

### Development Tools
- **Code Style:** Laravel Pint (PHP code formatter)
- **Testing:** PHPUnit 11.5+
- **Debugging:** Laravel Debugbar 4.2
- **Database Seeding:** Faker library for test data
- **Container Management:** Docker (Laravel Sail)

### Build Tools
- **Frontend Bundling:** Vite
- **Package Management:** npm

---

## Project Structure

```
e_comerce_backend_laravel/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/
│   │   │   │   └── V1/
│   │   │   │       ├── Auth/
│   │   │   │       │   ├── LoginController.php
│   │   │   │       │   ├── LogoutController.php
│   │   │   │       │   ├── MeController.php
│   │   │   │       │   └── RegisterController.php
│   │   │   │       ├── CartController.php
│   │   │   │       ├── OrderController.php
│   │   │   │       ├── PaymentController.php
│   │   │   │       └── ProductController.php
│   │   │   └── Controller.php
│   │   ├── Requests/
│   │   │   └── Api/
│   │   │       └── V1/
│   │   │           └── Auth/
│   │   │               └── RegisterRequest.php
│   │   └── Resources/
│   │       └── UserResource.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Product.php
│   │   ├── Order.php
│   │   ├── Cart.php
│   │   ├── Payment.php
│   │   ├── Category.php
│   │   ├── ProductVariant.php
│   │   ├── Inventory.php
│   │   ├── Shipment.php
│   │   └── ... (additional models)
│   ├── Repositories/
│   │   └── ProductRepository.php
│   ├── Services/
│   │   └── Auth/
│   ├── Providers/
│   └── ... (additional application files)
├── routes/
│   ├── api.php
│   ├── web.php
│   └── console.php
├── database/
│   ├── migrations/
│   ├── factories/
│   └── seeders/
├── config/
│   ├── app.php
│   ├── auth.php
│   ├── database.php
│   ├── cache.php
│   ├── session.php
│   ├── mail.php
│   ├── logging.php
│   ├── filesystems.php
│   ├── queue.php
│   └── services.php
├── tests/
│   ├── Unit/
│   ├── Feature/
│   └── TestCase.php
├── public/
├── resources/
├── storage/
├── bootstrap/
├── vendor/
├── composer.json
├── composer.lock
├── package.json
├── phpunit.xml
├── vite.config.js
├── .env.example
└── README.md
```

---

## Database Schema

### Core Tables

#### Users Table
```
users
├── id (Primary Key)
├── name (String)
├── email (String, Unique)
├── email_verified_at (Timestamp, Nullable)
├── password (String)
├── remember_token (String, Nullable)
├── created_at (Timestamp)
└── updated_at (Timestamp)
```

#### Roles Table
```
roles
├── id (Primary Key)
├── name (String)
├── slug (String)
├── created_at (Timestamp)
└── updated_at (Timestamp)
```

#### Permissions Table
```
permissions
├── id (Primary Key)
├── name (String)
├── slug (String)
├── created_at (Timestamp)
└── updated_at (Timestamp)
```

#### Categories Table
```
categories
├── id (Primary Key)
├── name (String)
├── slug (String)
├── description (Text, Nullable)
├── is_active (Boolean)
├── created_at (Timestamp)
└── updated_at (Timestamp)
```

#### Products Table
```
products
├── id (Primary Key)
├── category_id (Foreign Key → categories)
├── name (String)
├── slug (String)
├── description (Text, Nullable)
├── price (Decimal 8,2)
├── sku (String, Unique)
├── is_active (Boolean)
├── created_at (Timestamp)
└── updated_at (Timestamp)
```

#### Product Images Table
```
product_images
├── id (Primary Key)
├── product_id (Foreign Key → products)
├── image_url (String)
├── alt_text (String, Nullable)
├── is_primary (Boolean)
├── created_at (Timestamp)
└── updated_at (Timestamp)
```

#### Product Variants Table
```
product_variants
├── id (Primary Key)
├── product_id (Foreign Key → products)
├── name (String)
├── sku (String)
├── price (Decimal 8,2, Nullable)
├── created_at (Timestamp)
└── updated_at (Timestamp)
```

#### Inventories Table
```
inventories
├── id (Primary Key)
├── product_id (Foreign Key → products)
├── quantity (Integer)
├── reserved_quantity (Integer)
├── created_at (Timestamp)
└── updated_at (Timestamp)
```

#### Stock Movements Table
```
stock_movements
├── id (Primary Key)
├── inventory_id (Foreign Key → inventories)
├── movement_type (Enum: IN, OUT)
├── quantity (Integer)
├── reference_type (String)
├── reference_id (Integer)
├── notes (Text, Nullable)
├── created_at (Timestamp)
└── updated_at (Timestamp)
```

#### Carts Table
```
carts
├── id (Primary Key)
├── user_id (Foreign Key → users)
├── created_at (Timestamp)
└── updated_at (Timestamp)
```

#### Cart Items Table
```
cart_items
├── id (Primary Key)
├── cart_id (Foreign Key → carts)
├── product_id (Foreign Key → products)
├── quantity (Integer)
├── price (Decimal 8,2)
├── created_at (Timestamp)
└── updated_at (Timestamp)
```

#### Order Statuses Table
```
order_statuses
├── id (Primary Key)
├── name (String)
├── slug (String)
├── created_at (Timestamp)
└── updated_at (Timestamp)
```

#### Orders Table
```
orders
├── id (Primary Key)
├── user_id (Foreign Key → users)
├── order_status_id (Foreign Key → order_statuses)
├── total (Decimal 10,2)
├── created_at (Timestamp)
└── updated_at (Timestamp)
```

#### Order Items Table
```
order_items
├── id (Primary Key)
├── order_id (Foreign Key → orders)
├── product_id (Foreign Key → products)
├── quantity (Integer)
├── price (Decimal 8,2)
├── created_at (Timestamp)
└── updated_at (Timestamp)
```

#### Payment Methods Table
```
payment_methods
├── id (Primary Key)
├── name (String)
├── code (String)
├── is_active (Boolean)
├── created_at (Timestamp)
└── updated_at (Timestamp)
```

#### Payments Table
```
payments
├── id (Primary Key)
├── order_id (Foreign Key → orders)
├── payment_method_id (Foreign Key → payment_methods)
├── amount (Decimal 10,2)
├── status (Enum: PENDING, COMPLETED, FAILED)
├── transaction_id (String, Nullable)
├── reference_code (String)
├── created_at (Timestamp)
└── updated_at (Timestamp)
```

#### Shipment Statuses Table
```
shipment_statuses
├── id (Primary Key)
├── name (String)
├── slug (String)
├── created_at (Timestamp)
└── updated_at (Timestamp)
```

#### Shipments Table
```
shipments
├── id (Primary Key)
├── order_id (Foreign Key → orders)
├── shipment_status_id (Foreign Key → shipment_statuses)
├── tracking_number (String, Nullable)
├── shipped_date (Timestamp, Nullable)
├── delivery_date (Timestamp, Nullable)
├── created_at (Timestamp)
└── updated_at (Timestamp)
```

#### Pivot Tables
- **role_user:** Links users with roles (Many-to-Many)
- **permission_role:** Links roles with permissions (Many-to-Many)
- **permission_user:** Links users with permissions (Many-to-Many)
- **product_variant_value_pivot:** Links product variants with variant values

---

## API Endpoints

### Base URL
```
http://localhost:8000/api/v1
```

### Authentication Endpoints

#### Register User
```
POST /auth/register
Content-Type: application/json

Request:
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "device_name": "web"
}

Response (201):
{
  "success": true,
  "message": "Registration successful.",
  "data": {
    "token": "1|Bearer...",
    "token_type": "Bearer",
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "roles": []
    }
  }
}
```

#### Login User
```
POST /auth/login
Content-Type: application/json

Request:
{
  "email": "john@example.com",
  "password": "password123",
  "device_name": "web"
}

Response (200):
{
  "success": true,
  "message": "Login successful.",
  "data": {
    "token": "1|Bearer...",
    "token_type": "Bearer",
    "user": { ... }
  }
}
```

#### Logout User
```
POST /auth/logout
Authorization: Bearer {token}

Response (200):
{
  "success": true,
  "message": "Logout successful."
}
```

#### Get Current User
```
GET /auth/me
Authorization: Bearer {token}

Response (200):
{
  "id": 1,
  "name": "John Doe",
  "email": "john@example.com",
  "roles": [],
  "permissions": []
}
```

### Product Endpoints

#### List Products
```
GET /products?category_id=1&q=search&limit=20
Authorization: Optional (Bearer {token})

Response (200):
{
  "data": [...],
  "links": { "first": "", "last": "", "prev": null, "next": null },
  "meta": { "current_page": 1, "from": 1, "path": "", "per_page": 20, "to": 20, "total": 100 }
}
```

#### Get Product Details
```
GET /products/{id}
Authorization: Optional

Response (200):
{
  "id": 1,
  "category_id": 1,
  "name": "Product Name",
  "slug": "product-name",
  "description": "Product description",
  "price": "99.99",
  "sku": "SKU123",
  "is_active": true,
  "images": [...],
  "variants": [...]
}
```

#### Create Product
```
POST /products
Authorization: Bearer {token}
Content-Type: application/json

Request:
{
  "category_id": 1,
  "name": "New Product",
  "slug": "new-product",
  "description": "Product description",
  "price": 99.99,
  "sku": "SKU123",
  "is_active": true
}

Response (201):
{ ... product data ... }
```

#### Update Product
```
PUT /products/{id}
Authorization: Bearer {token}
Content-Type: application/json

Request:
{ ... (same fields as create, partial update allowed) ... }

Response (200):
{ ... updated product data ... }
```

#### Delete Product
```
DELETE /products/{id}
Authorization: Bearer {token}

Response (204): No Content
```

### Cart Endpoints

#### Get Cart
```
GET /cart
Authorization: Bearer {token}

Response (200):
{
  "id": 1,
  "user_id": 1,
  "items": [
    {
      "id": 1,
      "product_id": 1,
      "quantity": 2,
      "price": "99.99"
    }
  ]
}
```

#### Add to Cart
```
POST /cart/items
Authorization: Bearer {token}
Content-Type: application/json

Request:
{
  "product_id": 1,
  "quantity": 2
}

Response (201):
{ ... cart item data ... }
```

### Order Endpoints

#### List Orders
```
GET /orders
Authorization: Bearer {token}

Response (200):
[
  {
    "id": 1,
    "user_id": 1,
    "order_status_id": 1,
    "total": "299.99",
    "items": [...],
    "status": { ... }
  }
]
```

#### Create Order
```
POST /orders
Authorization: Bearer {token}
Content-Type: application/json

Request:
{
  "items": [
    {
      "product_id": 1,
      "quantity": 2
    }
  ]
}

Response (201):
{ ... order data ... }
```

#### Get Order Details
```
GET /orders/{id}
Authorization: Bearer {token}

Response (200):
{ ... order data with items and shipments ... }
```

#### Cancel Order
```
PATCH /orders/{id}/cancel
Authorization: Bearer {token}

Response (200):
{
  "success": true,
  "message": "Order cancelled successfully.",
  "data": { ... updated order ... }
}
```

### Payment Endpoints

#### Initiate Payment
```
POST /payments/initiate
Authorization: Bearer {token}
Content-Type: application/json

Request:
{
  "order_id": 1,
  "payment_method_id": 1,
  "amount": 299.99
}

Response (200):
{
  "success": true,
  "data": {
    "payment_id": 1,
    "status": "PENDING",
    "redirect_url": "https://payment-provider.com/..."
  }
}
```

#### Confirm Payment
```
POST /payments/confirm
Authorization: Bearer {token}
Content-Type: application/json

Request:
{
  "payment_id": 1,
  "transaction_id": "txn_123456"
}

Response (200):
{
  "success": true,
  "message": "Payment confirmed successfully.",
  "data": { ... payment data ... }
}
```

#### Payment Webhook
```
POST /payments/webhook
Content-Type: application/json

Request:
{
  "event": "payment.completed",
  "payment_id": 1,
  "transaction_id": "txn_123456"
}

Response (200):
{
  "success": true,
  "message": "Webhook processed successfully."
}
```

---

## Authentication

### Method: Laravel Sanctum Token Authentication

#### How It Works
1. User registers or logs in
2. Server generates an API token
3. Client includes token in Authorization header: `Authorization: Bearer {token}`
4. Server validates token for protected routes

#### Protected Routes
All routes under `middleware('auth:sanctum')` require valid authentication token.

#### Token Format
- Type: Bearer Token
- Generated by: Laravel Sanctum
- Device Name: Optional identifier for token source (web, mobile, etc.)

#### Security Measures
- Tokens can be revoked per device
- Password hashing using Bcrypt
- CSRF protection on state-changing operations
- Rate limiting on login endpoints (throttle:login)

---

## Installation and Setup

### Prerequisites
- PHP 8.2 or higher
- Composer
- Database (MySQL, PostgreSQL, SQLite)
- Node.js and npm

### Installation Steps

1. **Clone Repository**
```bash
git clone https://github.com/VATSU-tech/e_comerce_backend_laravel.git
cd e_comerce_backend_laravel
```

2. **Install PHP Dependencies**
```bash
composer install
```

3. **Install JavaScript Dependencies**
```bash
npm install
```

4. **Setup Environment**
```bash
cp .env.example .env
php artisan key:generate
```

5. **Configure Database**
Edit `.env` file with database credentials:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecommerce
DB_USERNAME=root
DB_PASSWORD=
```

6. **Run Migrations**
```bash
php artisan migrate
```

7. **Seed Database (Optional)**
```bash
php artisan db:seed
```

8. **Start Development Server**
```bash
php artisan serve
```

Server runs at: `http://localhost:8000`

### Using Docker (Laravel Sail)

```bash
./vendor/bin/sail up
./vendor/bin/sail artisan migrate
```

---

## Configuration

### Key Configuration Files

#### app.php
- Application name and environment
- Debug mode settings
- Timezone configuration
- Service providers
- Aliases

#### auth.php
- Authentication guards (sanctum)
- Password reset timeout
- Authentication providers

#### database.php
- Database connection settings
- Multiple connection support
- Migration repository configuration

#### cache.php
- Default cache driver
- Cache store configurations
- Cache invalidation settings

#### session.php
- Session driver (file, cookie, database)
- Session lifetime
- Cookie settings

#### logging.php
- Log channels configuration
- Error handling configuration
- Debug logging levels

### Environment Variables

```
APP_NAME=E-Commerce
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecommerce
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_FROM_ADDRESS=

QUEUE_CONNECTION=sync
SESSION_DRIVER=file
CACHE_DRIVER=file
```

---

## Data Models

### User Model
**Namespace:** `App\Models\User`

**Attributes:**
- id (Primary Key)
- name (String)
- email (String, Unique)
- email_verified_at (Timestamp)
- password (String, Hashed)
- remember_token (String)

**Relations:**
- `roles()` - Many-to-Many with Role
- `addresses()` - One-to-Many with Address
- `carts()` - One-to-Many with Cart
- `permissions()` - Many-to-Many with Permission

**Traits:**
- HasFactory - Factory support
- Notifiable - Notification support
- HasApiTokens - Sanctum API tokens

---

### Product Model
**Namespace:** `App\Models\Product`

**Attributes:**
- id (Primary Key)
- category_id (Foreign Key)
- name (String)
- slug (String)
- description (Text)
- price (Decimal)
- sku (String, Unique)
- is_active (Boolean)

**Relations:**
- `category()` - Belongs-To Category
- `images()` - One-to-Many with ProductImage
- `variants()` - One-to-Many with ProductVariant

**Casts:**
- is_active → Boolean
- price → Decimal(2)

---

### Order Model
**Namespace:** `App\Models\Order`

**Attributes:**
- id (Primary Key)
- user_id (Foreign Key)
- order_status_id (Foreign Key)
- total (Decimal)

**Relations:**
- `user()` - Belongs-To User
- `status()` - Belongs-To OrderStatus
- `items()` - One-to-Many with OrderItem

**Casts:**
- total → Decimal(2)

---

### Cart Model
**Namespace:** `App\Models\Cart`

**Attributes:**
- id (Primary Key)
- user_id (Foreign Key)

**Relations:**
- `user()` - Belongs-To User
- `items()` - One-to-Many with CartItem

---

### Additional Models
- **Category** - Product categories and organization
- **Payment** - Order payment records
- **Shipment** - Order shipment tracking
- **Inventory** - Product stock management
- **Role** - User roles for permission system
- **Permission** - System permissions
- **Address** - User delivery addresses
- **ProductVariant** - Product variations (size, color, etc.)
- **OrderItem** - Individual items in orders
- **CartItem** - Individual items in shopping carts

---

## API Response Format

### Success Response
```json
{
  "success": true,
  "message": "Operation successful.",
  "data": { ... }
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error message here",
  "errors": {
    "field_name": ["Error description"]
  }
}
```

### Paginated Response
```json
{
  "data": [...],
  "links": {
    "first": "http://localhost/api/v1/products?page=1",
    "last": "http://localhost/api/v1/products?page=5",
    "prev": null,
    "next": "http://localhost/api/v1/products?page=2"
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 5,
    "path": "http://localhost/api/v1/products",
    "per_page": 20,
    "to": 20,
    "total": 100
  }
}
```

### HTTP Status Codes
- **200 OK** - Successful GET, PUT, PATCH request
- **201 Created** - Successful POST request (resource created)
- **204 No Content** - Successful DELETE request
- **400 Bad Request** - Invalid request parameters
- **401 Unauthorized** - Authentication required
- **403 Forbidden** - Insufficient permissions
- **404 Not Found** - Resource not found
- **422 Unprocessable Entity** - Validation errors
- **500 Internal Server Error** - Server error

---

## Error Handling

### Validation Errors
```json
{
  "success": false,
  "message": "The given data was invalid.",
  "errors": {
    "email": ["The email field is required."],
    "password": ["The password must be at least 8 characters."]
  }
}
```

### Authentication Errors
```json
{
  "success": false,
  "message": "Unauthenticated.",
  "errors": {}
}
```

### Not Found Errors
```json
{
  "success": false,
  "message": "Resource not found.",
  "errors": {}
}
```

### Server Errors
```json
{
  "success": false,
  "message": "Server error occurred. Please try again later.",
  "errors": {}
}
```

---

## Development Guidelines

### Code Standards

#### PSR-4 Autoloading
- `App\` namespace maps to `app/` directory
- Controllers in `App\Http\Controllers\`
- Models in `App\Models\`
- Repositories in `App\Repositories\`
- Services in `App\Services\`

#### Naming Conventions
- **Classes:** PascalCase (e.g., ProductController)
- **Methods:** camelCase (e.g., getProducts())
- **Variables:** camelCase (e.g., $productId)
- **Database Tables:** snake_case, plural (e.g., products)
- **Database Columns:** snake_case (e.g., product_id)

#### Code Quality
- Use PHP 8.2+ features (typed properties, union types)
- Type hint all method parameters and returns
- Use strict type checking (`declare(strict_types=1)`)
- Follow PSR-12 coding standards
- Use Laravel Pint for code formatting

### Repository Pattern
The codebase uses Repository pattern for data access:
- Repositories abstract database queries
- Controllers use repositories for CRUD operations
- Example: `ProductRepository` in `App\Repositories\`

### Service Layer
Business logic is organized in Service classes:
- Located in `App\Services\` directory
- Handles domain logic and operations
- Used by controllers and repositories

### Testing
- Unit tests in `tests/Unit/`
- Feature tests in `tests/Feature/`
- Run tests: `php artisan test` or `./vendor/bin/phpunit`
- Use Factory pattern for test data

### Database Migrations
- Migrations in `database/migrations/`
- Create migrations: `php artisan make:migration create_table_name`
- Run: `php artisan migrate`
- Rollback: `php artisan migrate:rollback`

### Best Practices
1. Always validate user input in requests
2. Use form request classes for validation
3. Implement proper error handling
4. Use transaction for multi-step operations
5. Keep controllers thin, push logic to repositories/services
6. Document public APIs with comments
7. Use eager loading to prevent N+1 queries
8. Implement proper authorization checks
9. Log important operations
10. Use queue for long-running tasks

### Git Workflow
1. Create feature branch: `git checkout -b feature/feature-name`
2. Make changes and test
3. Commit with clear messages: `git commit -m "Add feature description"`
4. Push to remote: `git push origin feature/feature-name`
5. Create Pull Request for review

---

## Summary

This E-Commerce Backend API provides a complete, scalable solution for managing e-commerce operations. It features:

- Secure user authentication and authorization
- Comprehensive product catalog management
- Shopping cart functionality
- Order processing and management
- Payment integration capabilities
- Inventory and stock management
- Shipment tracking
- Role-based access control

The application follows Laravel best practices and is structured for maintainability, scalability, and ease of extension.

---

**Last Updated:** May 24, 2026  
**Repository:** https://github.com/VATSU-tech/e_comerce_backend_laravel
