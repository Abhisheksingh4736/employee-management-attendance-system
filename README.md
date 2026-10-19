# Employee Management & Attendance System

A web-based Employee Management and Attendance System built using Laravel 13, PHP, MySQL, Blade, and Laravel Sanctum.

## Features

### Authentication & Roles
- Login and logout functionality.
- Role-based access for Admin and Employee.
- API authentication using Laravel Sanctum.
- Login request rate limiting.

### Admin Module
- Admin dashboard.
- Create, view, update, and delete employees.
- Search and filter employees.
- Activate or deactivate employee accounts.
- View attendance reports by date range.
- View employees absent on a selected date.

### Employee Module
- Employee dashboard.
- Check-in and check-out functionality.
- View attendance history.
- View attendance and working-hour information.
- Prevent duplicate check-ins and check-outs.
- Inactive employees cannot use attendance functionality.

### REST API
- API login and token generation.
- API logout and token revocation.
- Retrieve the employee list.
- Retrieve an employee by ID.
- Protected employee endpoints using Laravel Sanctum.

## Technology Stack

- **Backend:** PHP, Laravel 13
- **Database:** MySQL
- **Frontend:** Blade, HTML, CSS
- **Authentication:** Laravel Authentication and Sanctum
- **API Testing:** Postman
- **Automated Testing:** PHPUnit / Laravel Tests

## Installation

### 1. Clone or copy the project

Place the project inside your local web development directory, such as:

`C:\xampp\htdocs\employee_management`

### 2. Install dependencies

```bash
composer install
```

### 3. Configure environment

Copy `.env.example` to `.env` and configure your database credentials.

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=employee_management
DB_USERNAME=root
DB_PASSWORD=
```

Create the `employee_management` database in phpMyAdmin.

### 4. Generate application key

```bash
php artisan key:generate
```

### 5. Run database migrations

```bash
php artisan migrate
```

If the project includes database seeders, run them only after reviewing their contents:

```bash
php artisan db:seed
```

### 6. Start the application

```bash
php artisan serve
```

Open the application at:

`http://127.0.0.1:8000`

## Web Routes

| Module | URL |
|---|---|
| Login | `/login` |
| Admin Dashboard | `/admin/dashboard` |
| Employee Attendance | `/employee/attendance` |
| Admin Attendance Report | `/admin/attendance` |
| Admin Absent Employees | `/admin/attendance/absent` |

Login and dashboard access depend on the configured authentication and role middleware.

## REST API Documentation

Base URL:

`http://127.0.0.1:8000/api`

All protected API endpoints require a valid Laravel Sanctum bearer token.

| Method | Endpoint | Purpose | Authentication |
|---|---|---|---|
| POST | `/api/login` | Login and generate token | Not required |
| POST | `/api/logout` | Revoke current token | Bearer token |
| GET | `/api/employees` | Retrieve employees | Bearer token |
| GET | `/api/employees/{id}` | Retrieve one employee | Bearer token |

### API Login

**Request**

`POST /api/login`

```json
{
  "email": "admin@example.com",
  "password": "YOUR_PASSWORD"
}
```

A successful response contains the authenticated user's details and an access token.

### API Logout

**Request**

`POST /api/logout`

Header:

`Authorization: Bearer YOUR_TOKEN`

This endpoint revokes the current access token.

### Get Employees

**Request**

`GET /api/employees`

Header:

`Authorization: Bearer YOUR_TOKEN`

Returns the employee list in JSON format.

### Get Employee by ID

**Request**

`GET /api/employees/1`

Header:

`Authorization: Bearer YOUR_TOKEN`

Returns the requested employee record, or an appropriate error response if it cannot be found.

## Testing

Run the automated test suite:

```bash
php artisan test
```

The test suite was reported to pass during development.

Also test the login, logout, employee listing, employee details, attendance, and admin workflows manually before submission.

## Security Notes

- Do not commit `.env` or real credentials to version control.
- Do not share production access tokens.
- Use a strong password for the admin account.
- Keep protected API routes behind authentication middleware.
- Use a dedicated test database when running database-refreshing tests.

## Project Status

Core web workflows, attendance features, API endpoints, and automated tests have been reported as implemented. Verify all features against the original assignment requirements before final submission.
