<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Commerce Backend API Documentation</title>
    <style>
        :root {
            --color-primary: #2563eb;
            --color-primary-dark: #1d4ed8;
            --color-primary-light: #dbeafe;
            --color-secondary: #64748b;
            --color-success: #10b981;
            --color-warning: #f59e0b;
            --color-danger: #ef4444;
            --color-background: #ffffff;
            --color-surface: #f8fafc;
            --color-border: #e2e8f0;
            --color-text: #1e293b;
            --color-text-light: #64748b;
            --color-code-bg: #1e293b;
            --color-code-text: #e2e8f0;
            --spacing-unit: 1rem;
            --border-radius: 0.5rem;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --transition-speed: 0.2s;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: var(--color-text);
            background-color: var(--color-background);
        }

        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 var(--spacing-unit);
        }

        /* Header */
        header {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
            color: white;
            padding: calc(var(--spacing-unit) * 3) 0;
            box-shadow: var(--shadow-md);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: var(--spacing-unit);
        }

        .header-title h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: calc(var(--spacing-unit) * 0.5);
        }

        .header-subtitle {
            font-size: 1.1rem;
            color: var(--color-primary-light);
            opacity: 0.95;
            margin-bottom: calc(var(--spacing-unit) * 1.5);
        }

        .header-meta {
            display: flex;
            gap: calc(var(--spacing-unit) * 2);
            flex-wrap: wrap;
        }

        .header-meta-item {
            display: flex;
            flex-direction: column;
            gap: calc(var(--spacing-unit) * 0.25);
        }

        .header-meta-label {
            font-size: 0.875rem;
            opacity: 0.85;
        }

        .header-meta-value {
            font-weight: 600;
        }

        /* Navigation */
        nav {
            background-color: var(--color-surface);
            border-bottom: 1px solid var(--color-border);
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: var(--shadow-sm);
        }

        nav ul {
            list-style: none;
            display: flex;
            flex-wrap: wrap;
            gap: var(--spacing-unit);
            overflow-x: auto;
        }

        nav a {
            display: block;
            padding: calc(var(--spacing-unit) * 0.75) var(--spacing-unit);
            color: var(--color-text);
            text-decoration: none;
            font-weight: 500;
            transition: color var(--transition-speed);
            font-size: 0.95rem;
        }

        nav a:hover {
            color: var(--color-primary);
        }

        /* Main Content */
        .main-content {
            display: grid;
            grid-template-columns: 250px 1fr;
            gap: calc(var(--spacing-unit) * 2);
            padding: calc(var(--spacing-unit) * 2) 0;
        }

        /* Sidebar */
        .sidebar {
            position: sticky;
            top: 80px;
            max-height: calc(100vh - 100px);
            overflow-y: auto;
        }

        .sidebar-section {
            margin-bottom: calc(var(--spacing-unit) * 1.5);
        }

        .sidebar-title {
            font-weight: 700;
            font-size: 0.875rem;
            text-transform: uppercase;
            color: var(--color-secondary);
            margin-bottom: calc(var(--spacing-unit) * 0.75);
            padding-left: var(--spacing-unit);
        }

        .sidebar-link {
            display: block;
            padding: calc(var(--spacing-unit) * 0.5) calc(var(--spacing-unit) * 1);
            color: var(--color-text);
            text-decoration: none;
            font-size: 0.9rem;
            border-left: 2px solid transparent;
            transition: all var(--transition-speed);
            margin-left: 0;
        }

        .sidebar-link:hover {
            color: var(--color-primary);
            border-left-color: var(--color-primary);
            background-color: var(--color-primary-light);
        }

        .sidebar-link.active {
            color: var(--color-primary);
            border-left-color: var(--color-primary);
            background-color: var(--color-primary-light);
        }

        /* Content Area */
        .content {
            padding: 0;
        }

        section {
            margin-bottom: calc(var(--spacing-unit) * 4);
            scroll-margin-top: 100px;
        }

        h2 {
            font-size: 1.875rem;
            font-weight: 700;
            margin-bottom: var(--spacing-unit);
            color: var(--color-text);
            padding-bottom: var(--spacing-unit);
            border-bottom: 2px solid var(--color-primary);
        }

        h3 {
            font-size: 1.375rem;
            font-weight: 600;
            margin-top: calc(var(--spacing-unit) * 1.5);
            margin-bottom: var(--spacing-unit);
            color: var(--color-primary);
        }

        h4 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-top: var(--spacing-unit);
            margin-bottom: calc(var(--spacing-unit) * 0.75);
            color: var(--color-text);
        }

        p {
            margin-bottom: var(--spacing-unit);
            color: var(--color-text-light);
            line-height: 1.8;
        }

        a {
            color: var(--color-primary);
            text-decoration: none;
            transition: opacity var(--transition-speed);
        }

        a:hover {
            opacity: 0.8;
            text-decoration: underline;
        }

        /* Lists */
        ul, ol {
            margin-left: var(--spacing-unit);
            margin-bottom: var(--spacing-unit);
            line-height: 1.8;
        }

        li {
            margin-bottom: calc(var(--spacing-unit) * 0.5);
            color: var(--color-text-light);
        }

        /* Code */
        code {
            background-color: var(--color-surface);
            color: var(--color-primary);
            padding: calc(var(--spacing-unit) * 0.25) calc(var(--spacing-unit) * 0.5);
            border-radius: var(--border-radius);
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            font-size: 0.9em;
        }

        pre {
            background-color: var(--color-code-bg);
            color: var(--color-code-text);
            padding: var(--spacing-unit);
            border-radius: var(--border-radius);
            overflow-x: auto;
            margin-bottom: var(--spacing-unit);
            border: 1px solid var(--color-border);
            line-height: 1.5;
        }

        pre code {
            background-color: transparent;
            color: inherit;
            padding: 0;
        }

        /* Boxes */
        .info-box, .success-box, .warning-box, .code-box {
            padding: var(--spacing-unit);
            border-radius: var(--border-radius);
            margin-bottom: var(--spacing-unit);
            border-left: 4px solid;
        }

        .info-box {
            background-color: rgba(37, 99, 235, 0.05);
            border-left-color: var(--color-primary);
        }

        .success-box {
            background-color: rgba(16, 185, 129, 0.05);
            border-left-color: var(--color-success);
        }

        .warning-box {
            background-color: rgba(245, 158, 11, 0.05);
            border-left-color: var(--color-warning);
        }

        .code-box {
            background-color: var(--color-code-bg);
            border-left-color: var(--color-primary);
            color: var(--color-code-text);
        }

        .code-box pre {
            background-color: transparent;
            border: none;
            margin: 0;
            padding: 0;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: var(--spacing-unit);
        }

        th {
            background-color: var(--color-primary);
            color: white;
            padding: var(--spacing-unit);
            text-align: left;
            font-weight: 600;
        }

        td {
            padding: var(--spacing-unit);
            border-bottom: 1px solid var(--color-border);
        }

        tr:hover {
            background-color: var(--color-surface);
        }

        /* Footer */
        footer {
            background-color: var(--color-surface);
            border-top: 1px solid var(--color-border);
            padding: calc(var(--spacing-unit) * 2) 0;
            margin-top: calc(var(--spacing-unit) * 4);
            color: var(--color-text-light);
            text-align: center;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-content {
                grid-template-columns: 1fr;
            }

            .sidebar {
                position: relative;
                top: 0;
                max-height: none;
                margin-bottom: calc(var(--spacing-unit) * 2);
            }

            .header-content {
                flex-direction: column;
                text-align: center;
            }

            .header-title h1 {
                font-size: 2rem;
            }

            h2 {
                font-size: 1.5rem;
            }

            h3 {
                font-size: 1.2rem;
            }

            nav ul {
                flex-direction: column;
                gap: 0;
            }

            nav a {
                padding: calc(var(--spacing-unit) * 0.5) var(--spacing-unit);
            }
        }

        /* Scrollbar */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: var(--color-surface);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: var(--color-border);
            border-radius: var(--border-radius);
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: var(--color-secondary);
        }

        /* Anchor links */
        [id] {
            scroll-behavior: smooth;
        }

        .toc {
            background-color: var(--color-surface);
            padding: var(--spacing-unit);
            border-radius: var(--border-radius);
            margin-bottom: calc(var(--spacing-unit) * 2);
            border: 1px solid var(--color-border);
        }

        .toc ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .toc li {
            margin: 0;
        }

        .toc a {
            display: block;
            padding: calc(var(--spacing-unit) * 0.5) 0;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="header-title">
                    <h1>E-Commerce Backend API</h1>
                    <p class="header-subtitle">Complete REST API Documentation</p>
                </div>
                <div class="header-meta">
                    <div class="header-meta-item">
                        <span class="header-meta-label">Version</span>
                        <span class="header-meta-value">1.0.0</span>
                    </div>
                    <div class="header-meta-item">
                        <span class="header-meta-label">Type</span>
                        <span class="header-meta-value">RESTfull API</span>
                    </div>
                    <div class="header-meta-item">
                        <span class="header-meta-label">Framework</span>
                        <span class="header-meta-value">Laravel 12</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <nav class="sticky-nav">
        <div class="container">
            <ul>
                <li><a href="#project-overview">Overview</a></li>
                <li><a href="#technical-stack">Stack</a></li>
                <li><a href="#api-endpoints">Endpoints</a></li>
                <li><a href="#authentication">Auth</a></li>
                <li><a href="#database-schema">Database</a></li>
                <li><a href="#data-models">Models</a></li>
                <li><a href="#development-guidelines">Guidelines</a></li>
            </ul>
        </div>
    </nav>

    <main>
        <div class="container">
            <div class="main-content">
                <aside class="sidebar">
                    <div class="sidebar-section">
                        <div class="sidebar-title">Getting Started</div>
                        <a href="#project-overview" class="sidebar-link">Project Overview</a>
                        <a href="#technical-stack" class="sidebar-link">Technical Stack</a>
                        <a href="#installation-and-setup" class="sidebar-link">Installation</a>
                    </div>

                    <div class="sidebar-section">
                        <div class="sidebar-title">API Reference</div>
                        <a href="#api-endpoints" class="sidebar-link">Endpoints</a>
                        <a href="#authentication" class="sidebar-link">Authentication</a>
                        <a href="#api-response-format" class="sidebar-link">Responses</a>
                        <a href="#error-handling" class="sidebar-link">Errors</a>
                    </div>

                    <div class="sidebar-section">
                        <div class="sidebar-title">Architecture</div>
                        <a href="#project-structure" class="sidebar-link">Structure</a>
                        <a href="#database-schema" class="sidebar-link">Database</a>
                        <a href="#data-models" class="sidebar-link">Models</a>
                    </div>

                    <div class="sidebar-section">
                        <div class="sidebar-title">Development</div>
                        <a href="#configuration" class="sidebar-link">Configuration</a>
                        <a href="#development-guidelines" class="sidebar-link">Guidelines</a>
                    </div>
                </aside>

                <article class="content">
                    <!-- Project Overview -->
                    <section id="project-overview">
                        <h2>Project Overview</h2>
                        <p><strong>Project Name:</strong> E-Commerce Backend Laravel</p>
                        <p><strong>Version:</strong> 1.0.0</p>
                        <p><strong>Type:</strong> RESTfull API Backend</p>

                        <p>This is a comprehensive Laravel-based REST API designed to support a full-featured e-commerce platform with user authentication, product catalog management, shopping cart functionality, order management, and payment processing capabilities.</p>

                        <div class="info-box">
                            <strong>Key Features:</strong>
                            <ul>
                                <li>Secure user authentication and authorization</li>
                                <li>Comprehensive product catalog management</li>
                                <li>Shopping cart functionality</li>
                                <li>Order processing and management</li>
                                <li>Payment integration capabilities</li>
                                <li>Inventory and stock management</li>
                                <li>Shipment tracking</li>
                                <li>Role-based access control</li>
                            </ul>
                        </div>
                    </section>

                    <!-- Technical Stack -->
                    <section id="technical-stack">
                        <h2>Technical Stack</h2>

                        <h3>Core Framework</h3>
                        <ul>
                            <li><strong>Laravel Framework:</strong> 12.0</li>
                            <li><strong>PHP Version:</strong> 8.2+</li>
                            <li><strong>Package Manager:</strong> Composer</li>
                        </ul>

                        <h3>Authentication & Security</h3>
                        <ul>
                            <li><strong>Laravel Sanctum:</strong> 4.3 - Token-based API authentication</li>
                            <li><strong>Password Hashing:</strong> Bcrypt</li>
                        </ul>

                        <h3>Database & ORM</h3>
                        <ul>
                            <li><strong>ORM:</strong> Eloquent ORM</li>
                            <li><strong>Migrations:</strong> Database versioning system</li>
                            <li><strong>Relationships:</strong> Full relational model support</li>
                        </ul>

                        <h3>Development Tools</h3>
                        <ul>
                            <li><strong>Code Style:</strong> Laravel Pint</li>
                            <li><strong>Testing:</strong> PHPUnit 11.5+</li>
                            <li><strong>Debugging:</strong> Laravel Debugbar</li>
                            <li><strong>Database Seeding:</strong> Faker library</li>
                            <li><strong>Container:</strong> Docker (Laravel Sail)</li>
                        </ul>

                        <h3>Build Tools</h3>
                        <ul>
                            <li><strong>Frontend Bundling:</strong> Vite</li>
                            <li><strong>Package Management:</strong> npm</li>
                        </ul>
                    </section>

                    <!-- Project Structure -->
                    <section id="project-structure">
                        <h2>Project Structure</h2>

                        <div class="code-box">
                            <pre>
e_comerce_backend_laravel/
├── app/
│   ├── Http/
│   │   ├── Controllers/Api/V1/
│   │   │   ├── Auth/
│   │   │   │   ├── LoginController.php
│   │   │   │   ├── LogoutController.php
│   │   │   │   ├── MeController.php
│   │   │   │   └── RegisterController.php
│   │   │   ├── CartController.php
│   │   │   ├── OrderController.php
│   │   │   ├── PaymentController.php
│   │   │   └── ProductController.php
│   │   ├── Requests/
│   │   ├── Resources/
│   │   └── Middleware/
│   ├── Models/ (24 models)
│   ├── Repositories/
│   ├── Services/
│   └── Providers/
├── routes/
│   ├── api.php
│   ├── web.php
│   └── console.php
├── database/
│   ├── migrations/ (20 migrations)
│   ├── factories/
│   └── seeders/
├── config/
├── tests/
├── public/
├── resources/
├── storage/
├── vendor/
├── composer.json
├── .env.example
└── README.md
                            </pre>
                        </div>

                        <h3>Directory Descriptions</h3>
                        <ul>
                            <li><code>app/</code> - Application source code</li>
                            <li><code>app/Http/Controllers/</code> - API request handlers</li>
                            <li><code>app/Models/</code> - Eloquent data models (24 total)</li>
                            <li><code>app/Repositories/</code> - Data access layer</li>
                            <li><code>app/Services/</code> - Business logic layer</li>
                            <li><code>routes/</code> - Route definitions</li>
                            <li><code>database/</code> - Migrations, factories, and seeders</li>
                            <li><code>config/</code> - Application configuration files</li>
                            <li><code>tests/</code> - Unit and feature tests</li>
                        </ul>
                    </section>

                    <!-- Database Schema -->
                    <section id="database-schema">
                        <h2>Database Schema</h2>

                        <div class="info-box">
                            The database consists of 20 core tables with relationships organized around user management, product catalog, inventory, orders, payments, and shipments.
                        </div>

                        <h3>Core Tables</h3>
                        <table>
                            <thead>
                                <tr>
                                    <th>Table Name</th>
                                    <th>Purpose</th>
                                    <th>Key Relationships</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><code>users</code></td>
                                    <td>User accounts and authentication</td>
                                    <td>roles, addresses, carts</td>
                                </tr>
                                <tr>
                                    <td><code>roles</code></td>
                                    <td>User role definitions</td>
                                    <td>permissions, users</td>
                                </tr>
                                <tr>
                                    <td><code>permissions</code></td>
                                    <td>System permissions</td>
                                    <td>roles, users</td>
                                </tr>
                                <tr>
                                    <td><code>products</code></td>
                                    <td>Product catalog</td>
                                    <td>category, images, variants</td>
                                </tr>
                                <tr>
                                    <td><code>categories</code></td>
                                    <td>Product categories</td>
                                    <td>products</td>
                                </tr>
                                <tr>
                                    <td><code>orders</code></td>
                                    <td>Customer orders</td>
                                    <td>users, items, payments, shipments</td>
                                </tr>
                                <tr>
                                    <td><code>payments</code></td>
                                    <td>Order payments</td>
                                    <td>orders, payment_methods</td>
                                </tr>
                                <tr>
                                    <td><code>shipments</code></td>
                                    <td>Order shipments</td>
                                    <td>orders, shipment_statuses</td>
                                </tr>
                                <tr>
                                    <td><code>inventories</code></td>
                                    <td>Product stock levels</td>
                                    <td>products, stock_movements</td>
                                </tr>
                                <tr>
                                    <td><code>carts</code></td>
                                    <td>Shopping carts</td>
                                    <td>users, cart_items</td>
                                </tr>
                            </tbody>
                        </table>
                    </section>

                    <!-- API Endpoints -->
                    <section id="api-endpoints">
                        <h2>API Endpoints</h2>

                        <h3>Base URL</h3>
                        <div class="code-box">
                            <pre>http://localhost:8000/api/v1</pre>
                        </div>

                        <h3>Authentication Endpoints</h3>

                        <h4>Register User</h4>
                        <div class="code-box">
                            <pre>POST /auth/register
Content-Type: application/json

Request Body:
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "device_name": "web"
}</pre>
                        </div>

                        <h4>Login User</h4>
                        <div class="code-box">
                            <pre>POST /auth/login
Content-Type: application/json

Request Body:
{
  "email": "john@example.com",
  "password": "password123"
}</pre>
                        </div>

                        <h4>Logout User</h4>
                        <div class="code-box">
                            <pre>POST /auth/logout
Authorization: Bearer {token}</pre>
                        </div>

                        <h4>Get Current User</h4>
                        <div class="code-box">
                            <pre>GET /auth/me
Authorization: Bearer {token}</pre>
                        </div>

                        <h3>Product Endpoints</h3>

                        <h4>List Products</h4>
                        <div class="code-box">
                            <pre>GET /products?category_id=1&q=search&limit=20
Authorization: Optional</pre>
                        </div>

                        <h4>Get Product</h4>
                        <div class="code-box">
                            <pre>GET /products/{id}
Authorization: Optional</pre>
                        </div>

                        <h4>Create Product</h4>
                        <div class="code-box">
                            <pre>POST /products
Authorization: Bearer {token}

Request Body:
{
  "category_id": 1,
  "name": "New Product",
  "slug": "new-product",
  "price": 99.99,
  "sku": "SKU123"
}</pre>
                        </div>

                        <h4>Update Product</h4>
                        <div class="code-box">
                            <pre>PUT /products/{id}
Authorization: Bearer {token}</pre>
                        </div>

                        <h4>Delete Product</h4>
                        <div class="code-box">
                            <pre>DELETE /products/{id}
Authorization: Bearer {token}</pre>
                        </div>

                        <h3>Cart Endpoints</h3>

                        <h4>Get Cart</h4>
                        <div class="code-box">
                            <pre>GET /cart
Authorization: Bearer {token}</pre>
                        </div>

                        <h4>Add to Cart</h4>
                        <div class="code-box">
                            <pre>POST /cart/items
Authorization: Bearer {token}

Request Body:
{
  "product_id": 1,
  "quantity": 2
}</pre>
                        </div>

                        <h3>Order Endpoints</h3>

                        <h4>List Orders</h4>
                        <div class="code-box">
                            <pre>GET /orders
Authorization: Bearer {token}</pre>
                        </div>

                        <h4>Create Order</h4>
                        <div class="code-box">
                            <pre>POST /orders
Authorization: Bearer {token}</pre>
                        </div>

                        <h4>Get Order</h4>
                        <div class="code-box">
                            <pre>GET /orders/{id}
Authorization: Bearer {token}</pre>
                        </div>

                        <h4>Cancel Order</h4>
                        <div class="code-box">
                            <pre>PATCH /orders/{id}/cancel
Authorization: Bearer {token}</pre>
                        </div>

                        <h3>Payment Endpoints</h3>

                        <h4>Initiate Payment</h4>
                        <div class="code-box">
                            <pre>POST /payments/initiate
Authorization: Bearer {token}</pre>
                        </div>

                        <h4>Confirm Payment</h4>
                        <div class="code-box">
                            <pre>POST /payments/confirm
Authorization: Bearer {token}</pre>
                        </div>

                        <h4>Payment Webhook</h4>
                        <div class="code-box">
                            <pre>POST /payments/webhook
Content-Type: application/json</pre>
                        </div>
                    </section>

                    <!-- Authentication -->
                    <section id="authentication">
                        <h2>Authentication</h2>

                        <h3>Method: Laravel Sanctum Token Authentication</h3>

                        <p>The API uses token-based authentication via Laravel Sanctum for secure API access.</p>

                        <h3>How It Works</h3>
                        <ol>
                            <li>User registers or logs in</li>
                            <li>Server generates an API token</li>
                            <li>Client includes token in Authorization header: <code>Authorization: Bearer {token}</code></li>
                            <li>Server validates token for protected routes</li>
                        </ol>

                        <h3>Protected Routes</h3>
                        <p>All routes under <code>middleware('auth:sanctum')</code> require valid authentication token.</p>

                        <h3>Security Measures</h3>
                        <ul>
                            <li>Tokens can be revoked per device</li>
                            <li>Password hashing using Bcrypt</li>
                            <li>CSRF protection on state-changing operations</li>
                            <li>Rate limiting on login endpoints (throttle:login)</li>
                        </ul>

                        <div class="info-box">
                            <strong>Example Authorization Header:</strong>
                            <div class="code-box">
                                <pre>Authorization: Bearer 1|abcdef123456ghijklmnopqrstuvwxyz</pre>
                            </div>
                        </div>
                    </section>

                    <!-- Installation and Setup -->
                    <section id="installation-and-setup">
                        <h2>Installation and Setup</h2>

                        <h3>Prerequisites</h3>
                        <ul>
                            <li>PHP 8.2 or higher</li>
                            <li>Composer</li>
                            <li>Database (MySQL, PostgreSQL, SQLite)</li>
                            <li>Node.js and npm</li>
                        </ul>

                        <h3>Installation Steps</h3>

                        <h4>1. Clone Repository</h4>
                        <div class="code-box">
                            <pre>git clone https://github.com/VATSU-tech/e_comerce_backend_laravel.git
cd e_comerce_backend_laravel</pre>
                        </div>

                        <h4>2. Install Dependencies</h4>
                        <div class="code-box">
                            <pre>composer install
npm install</pre>
                        </div>

                        <h4>3. Setup Environment</h4>
                        <div class="code-box">
                            <pre>cp .env.example .env
php artisan key:generate</pre>
                        </div>

                        <h4>4. Configure Database</h4>
                        <p>Edit <code>.env</code> file with database credentials:</p>
                        <div class="code-box">
                            <pre>DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecommerce
DB_USERNAME=root
DB_PASSWORD=</pre>
                        </div>

                        <h4>5. Run Migrations</h4>
                        <div class="code-box">
                            <pre>php artisan migrate</pre>
                        </div>

                        <h4>6. Start Development Server</h4>
                        <div class="code-box">
                            <pre>php artisan serve</pre>
                        </div>

                        <div class="success-box">
                            Server runs at: <strong>http://localhost:8000</strong>
                        </div>
                    </section>

                    <!-- Configuration -->
                    <section id="configuration">
                        <h2>Configuration</h2>

                        <h3>Key Configuration Files</h3>

                        <h4>app.php</h4>
                        <ul>
                            <li>Application name and environment</li>
                            <li>Debug mode settings</li>
                            <li>Timezone configuration</li>
                            <li>Service providers and aliases</li>
                        </ul>

                        <h4>auth.php</h4>
                        <ul>
                            <li>Authentication guards (sanctum)</li>
                            <li>Password reset timeout</li>
                            <li>Authentication providers</li>
                        </ul>

                        <h4>database.php</h4>
                        <ul>
                            <li>Database connection settings</li>
                            <li>Multiple connection support</li>
                            <li>Migration repository configuration</li>
                        </ul>

                        <h3>Environment Variables</h3>
                        <div class="code-box">
                            <pre>APP_NAME=E-Commerce
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecommerce
DB_USERNAME=root
DB_PASSWORD=</pre>
                        </div>
                    </section>

                    <!-- API Response Format -->
                    <section id="api-response-format">
                        <h2>API Response Format</h2>

                        <h3>Success Response</h3>
                        <div class="code-box">
                            <pre>{
  "success": true,
  "message": "Operation successful.",
  "data": { ... }
}</pre>
                        </div>

                        <h3>Error Response</h3>
                        <div class="code-box">
                            <pre>{
  "success": false,
  "message": "Error message here",
  "errors": {
    "field_name": ["Error description"]
  }
}</pre>
                        </div>

                        <h3>HTTP Status Codes</h3>
                        <table>
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Meaning</th>
                                    <th>Usage</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>200</strong></td>
                                    <td>OK</td>
                                    <td>Successful request</td>
                                </tr>
                                <tr>
                                    <td><strong>201</strong></td>
                                    <td>Created</td>
                                    <td>Resource created</td>
                                </tr>
                                <tr>
                                    <td><strong>204</strong></td>
                                    <td>No Content</td>
                                    <td>Successful DELETE</td>
                                </tr>
                                <tr>
                                    <td><strong>400</strong></td>
                                    <td>Bad Request</td>
                                    <td>Invalid parameters</td>
                                </tr>
                                <tr>
                                    <td><strong>401</strong></td>
                                    <td>Unauthorized</td>
                                    <td>Authentication required</td>
                                </tr>
                                <tr>
                                    <td><strong>404</strong></td>
                                    <td>Not Found</td>
                                    <td>Resource not found</td>
                                </tr>
                                <tr>
                                    <td><strong>422</strong></td>
                                    <td>Unprocessable Entity</td>
                                    <td>Validation errors</td>
                                </tr>
                                <tr>
                                    <td><strong>500</strong></td>
                                    <td>Server Error</td>
                                    <td>Server error</td>
                                </tr>
                            </tbody>
                        </table>
                    </section>

                    <!-- Error Handling -->
                    <section id="error-handling">
                        <h2>Error Handling</h2>

                        <h3>Validation Errors</h3>
                        <div class="code-box">
                            <pre>{
  "success": false,
  "message": "The given data was invalid.",
  "errors": {
    "email": ["The email field is required."],
    "password": ["The password must be at least 8 characters."]
  }
}</pre>
                        </div>

                        <h3>Authentication Errors</h3>
                        <div class="code-box">
                            <pre>{
  "success": false,
  "message": "Unauthenticated.",
  "errors": {}
}</pre>
                        </div>

                        <h3>Not Found Errors</h3>
                        <div class="code-box">
                            <pre>{
  "success": false,
  "message": "Resource not found.",
  "errors": {}
}</pre>
                        </div>
                    </section>

                    <!-- Data Models -->
                    <section id="data-models">
                        <h2>Data Models</h2>

                        <h3>User Model</h3>
                        <p><strong>Namespace:</strong> <code>App\Models\User</code></p>
                        <p>Core user entity with authentication and relationships to roles, addresses, and carts.</p>

                        <h4>Attributes</h4>
                        <ul>
                            <li><code>id</code> - Primary Key</li>
                            <li><code>name</code> - User display name</li>
                            <li><code>email</code> - Unique email address</li>
                            <li><code>password</code> - Hashed password</li>
                        </ul>

                        <h4>Relations</h4>
                        <ul>
                            <li><code>roles()</code> - Many-to-Many with Role</li>
                            <li><code>addresses()</code> - One-to-Many with Address</li>
                            <li><code>carts()</code> - One-to-Many with Cart</li>
                            <li><code>permissions()</code> - Many-to-Many with Permission</li>
                        </ul>

                        <h3>Product Model</h3>
                        <p><strong>Namespace:</strong> <code>App\Models\Product</code></p>
                        <p>Product catalog entity with variants, images, and inventory tracking.</p>

                        <h4>Attributes</h4>
                        <ul>
                            <li><code>id</code> - Primary Key</li>
                            <li><code>category_id</code> - Foreign Key to Category</li>
                            <li><code>name</code> - Product name</li>
                            <li><code>slug</code> - URL-friendly name</li>
                            <li><code>description</code> - Product description</li>
                            <li><code>price</code> - Product price (Decimal)</li>
                            <li><code>sku</code> - Stock Keeping Unit (Unique)</li>
                            <li><code>is_active</code> - Availability flag</li>
                        </ul>

                        <h4>Relations</h4>
                        <ul>
                            <li><code>category()</code> - Belongs-To Category</li>
                            <li><code>images()</code> - One-to-Many with ProductImage</li>
                            <li><code>variants()</code> - One-to-Many with ProductVariant</li>
                        </ul>

                        <h3>Order Model</h3>
                        <p><strong>Namespace:</strong> <code>App\Models\Order</code></p>
                        <p>Order management entity tracking customer purchases and statuses.</p>

                        <h4>Attributes</h4>
                        <ul>
                            <li><code>id</code> - Primary Key</li>
                            <li><code>user_id</code> - Foreign Key to User</li>
                            <li><code>order_status_id</code> - Foreign Key to OrderStatus</li>
                            <li><code>total</code> - Order total amount (Decimal)</li>
                        </ul>

                        <h4>Relations</h4>
                        <ul>
                            <li><code>user()</code> - Belongs-To User</li>
                            <li><code>status()</code> - Belongs-To OrderStatus</li>
                            <li><code>items()</code> - One-to-Many with OrderItem</li>
                        </ul>

                        <h3>Additional Models</h3>
                        <ul>
                            <li><strong>Cart</strong> - Shopping cart management</li>
                            <li><strong>Payment</strong> - Payment records and transactions</li>
                            <li><strong>Shipment</strong> - Order shipment tracking</li>
                            <li><strong>Inventory</strong> - Product stock levels</li>
                            <li><strong>Category</strong> - Product categorization</li>
                            <li><strong>Role</strong> - User role definitions</li>
                            <li><strong>Permission</strong> - System permissions</li>
                            <li><strong>Address</strong> - User delivery addresses</li>
                            <li><strong>ProductVariant</strong> - Product variations</li>
                            <li><strong>OrderItem</strong> - Individual items in orders</li>
                            <li><strong>CartItem</strong> - Individual items in carts</li>
                        </ul>
                    </section>

                    <!-- Development Guidelines -->
                    <section id="development-guidelines">
                        <h2>Development Guidelines</h2>

                        <h3>Code Standards</h3>

                        <h4>PHP Version</h4>
                        <p>Use PHP 8.2+ features: typed properties, union types, match expressions, etc.</p>

                        <h4>Naming Conventions</h4>
                        <ul>
                            <li><strong>Classes:</strong> PascalCase (e.g., ProductController)</li>
                            <li><strong>Methods:</strong> camelCase (e.g., getProducts())</li>
                            <li><strong>Variables:</strong> camelCase (e.g., $productId)</li>
                            <li><strong>Database Tables:</strong> snake_case, plural (e.g., products)</li>
                            <li><strong>Database Columns:</strong> snake_case (e.g., product_id)</li>
                        </ul>

                        <h3>Architecture Patterns</h3>

                        <h4>Repository Pattern</h4>
                        <p>Abstract database queries in repository classes for cleaner controllers.</p>

                        <h4>Service Layer</h4>
                        <p>Complex business logic goes in Service classes, not controllers.</p>

                        <h3>Best Practices</h3>
                        <ol>
                            <li>Always validate user input in requests</li>
                            <li>Use form request classes for validation logic</li>
                            <li>Implement proper error handling and logging</li>
                            <li>Use transactions for multi-step operations</li>
                            <li>Keep controllers thin, push logic to services</li>
                            <li>Use eager loading to prevent N+1 queries</li>
                            <li>Implement authorization checks</li>
                            <li>Document public APIs</li>
                            <li>Use queue for long-running tasks</li>
                            <li>Follow PSR-12 coding standards</li>
                        </ol>

                        <h3>Testing</h3>
                        <div class="code-box">
                            <pre>Unit tests: tests/Unit/
Feature tests: tests/Feature/

Run tests:
php artisan test
./vendor/bin/phpunit</pre>
                        </div>

                        <h3>Code Formatting</h3>
                        <p>Use Laravel Pint for automatic code formatting:</p>
                        <div class="code-box">
                            <pre>./vendor/bin/pint</pre>
                        </div>
                    </section>

                    <!-- Summary -->
                    <section id="summary">
                        <h2>Summary</h2>

                        <p>The E-Commerce Backend API provides a complete, scalable solution for managing e-commerce operations. Built with Laravel 12 and following industry best practices, it features secure authentication, comprehensive product management, order processing, and payment integration.</p>

                        <div class="success-box">
                            <ul>
                                <li>Secure token-based authentication (Laravel Sanctum)</li>
                                <li>Comprehensive product catalog management</li>
                                <li>Shopping cart and order processing</li>
                                <li>Payment integration framework</li>
                                <li>Inventory and stock management</li>
                                <li>Role-based access control</li>
                                <li>RESTful API design</li>
                                <li>Scalable architecture</li>
                            </ul>
                        </div>

                        <p>For more information, visit the <a href="https://github.com/VATSU-tech/e_comerce_backend_laravel">GitHub Repository</a>.</p>
                    </section>
                </article>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2026 E-Commerce Backend API. All rights reserved.</p>
            <p>Version 1.0.0 | Last Updated: May 24, 2026</p>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarLinks = document.querySelectorAll('.sidebar-link');
            const contentSections = document.querySelectorAll('section[id]');

            function updateActiveLink() {
                let currentSection = '';
                contentSections.forEach(section => {
                    const sectionTop = section.offsetTop;
                    const sectionHeight = section.clientHeight;
                    if (window.scrollY >= sectionTop - 150 && window.scrollY < sectionTop + sectionHeight - 150) {
                        currentSection = section.getAttribute('id');
                    }
                });

                sidebarLinks.forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('href') === `#${currentSection}`) {
                        link.classList.add('active');
                    }
                });
            }

            window.addEventListener('scroll', updateActiveLink);
            updateActiveLink();

            sidebarLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href').substring(1);
                    const targetSection = document.getElementById(targetId);
                    if (targetSection) {
                        targetSection.scrollIntoView({ behavior: 'smooth' });
                    }
                });
            });

            document.querySelectorAll('nav a').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href').substring(1);
                    const targetSection = document.getElementById(targetId);
                    if (targetSection) {
                        targetSection.scrollIntoView({ behavior: 'smooth' });
                    }
                });
            });
        });
    </script>
</body>
</html>