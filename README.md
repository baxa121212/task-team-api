# Task Team API

A RESTful API built with Laravel for managing tasks with authentication.

---

## 🚀 Tech Stack

- PHP 8.2+
- Laravel 11
- SQLite / MySQL
- Laravel Sanctum (Token Authentication)
- RESTful API Architecture

---

## 📌 Features

- User Registration
- User Login (Token-based authentication)
- Authenticated routes using Sanctum
- CRUD operations for tasks
- Pagination, filtering & search
- JSON-only API responses
- Clean architecture (Controllers + Services + Requests + Resources)

---

## 🔐 Authentication

This API uses **Laravel Sanctum** for authentication.

After login, you receive a token.

You must include it in all protected requests:

```
Authorization: Bearer YOUR_TOKEN
```

---

## 📂 API Endpoints

### 🔓 Public Routes

| Method | Endpoint | Description |
|--------|----------|------------|
| POST | /api/v1/auth/register | Register new user |
| POST | /api/v1/auth/login | Login user |

---

### 🔒 Protected Routes (Require Token)

| Method | Endpoint | Description |
|--------|----------|------------|
| GET | /api/v1/me | Get authenticated user |
| GET | /api/v1/tasks | Get user tasks |
| POST | /api/v1/tasks | Create task |
| PUT | /api/v1/tasks/{id} | Update task |
| DELETE | /api/v1/tasks/{id} | Delete task |

---

## 🔎 Query Parameters (Tasks)

| Parameter | Type | Description |
|-----------|------|------------|
| is_completed | boolean | Filter completed tasks |
| q | string | Search by title/description |
| per_page | integer | Pagination size |
| page | integer | Page number |

Example:

```
GET /api/v1/tasks?is_completed=1&per_page=5
```

---

## ⚙️ Installation

Clone repository:

```
git clone https://github.com/baxa121212/task-team-api.git
cd task-team-api
```

Install dependencies:

```
composer install
```

Copy environment file:

```
cp .env.example .env
```

Generate application key:

```
php artisan key:generate
```

Run migrations:

```
php artisan migrate
```

Start development server:

```
php artisan serve
```

API will be available at:

```
http://127.0.0.1:8000
```

---

## 🧪 Example Login Request

```
curl -X POST http://127.0.0.1:8000/api/v1/auth/login \
-H "Content-Type: application/json" \
-d '{"email":"test@example.com","password":"password"}'
```

---

## 📦 Project Structure

- **Controllers** — Handle HTTP requests
- **Services** — Business logic layer
- **Requests** — Validation layer
- **Resources** — API response formatting
- **Middleware** — Force JSON responses

---

## 📈 Project Status

Backend Core: ✅ Completed  
Authentication: ✅ Completed  
Task Management: ✅ Completed  
Documentation: ✅ Completed  
Frontend: ⏳ In Progress

---

## 👨‍💻 Author

Bakdaulet Turebayev  
Junior Backend Developer  
PHP | Laravel | REST API
