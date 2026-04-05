# Finance Tracker API

A RESTful API for personal finance management built with Laravel 13 and MySQL.  
Supports income/expense tracking, filtering, and financial analytics.

## Tech Stack

- **Laravel 13** — PHP framework
- **MySQL 8** — database
- **Laravel Sanctum** — token-based API authentication
- **Docker / Laravel Sail** — local development environment

## Features

- User registration and authentication via API tokens (Sanctum)
- Full CRUD for financial transactions (income & expense)
- Filtering by type, category, and date range
- Custom middleware for request validation
- API Resources for consistent JSON response formatting
- Analytics endpoint with balance summary and monthly trends

## Getting Started

### Requirements

- Docker Desktop

### Installation
```bash
git clone https://github.com/vas281990-rgb/finance-tracker.git
cd finance-tracker
cp .env.example .env
./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
```

The API will be available at `http://localhost`.

## API Endpoints

### Authentication

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/register` | Register a new user |
| POST | `/api/login` | Login and receive token |
| POST | `/api/logout` | Logout (requires token) |

### Transactions

All transaction endpoints require `Authorization: Bearer {token}` header.

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/transactions` | List all transactions |
| POST | `/api/transactions` | Create a transaction |
| GET | `/api/transactions/{id}` | Get a single transaction |
| PUT | `/api/transactions/{id}` | Update a transaction |
| DELETE | `/api/transactions/{id}` | Delete a transaction |

**Query filters for GET `/api/transactions`:**

| Parameter | Description | Example |
|-----------|-------------|---------|
| `type` | Filter by type | `income` or `expense` |
| `category` | Filter by category | `food` |
| `from` | Date range start | `2024-01-01` |
| `to` | Date range end | `2024-12-31` |

### Analytics

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/analytics/summary` | Income, expense, balance summary + trends |

Supports optional `from` and `to` query parameters.

## Example Request
```bash
curl -X POST http://localhost/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Jane Doe",
    "email": "jane@example.com",
    "password": "secret123",
    "password_confirmation": "secret123"
  }'
```

## Project Structure
```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── TransactionController.php
│   │   └── AnalyticsController.php
│   ├── Middleware/
│   │   └── EnsureValidTransactionType.php
│   ├── Requests/
│   │   ├── StoreTransactionRequest.php
│   │   └── UpdateTransactionRequest.php
│   └── Resources/
│       ├── TransactionResource.php
│       └── UserResource.php
├── Models/
│   ├── User.php
│   └── Transaction.php
```