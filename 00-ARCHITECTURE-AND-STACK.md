# Architecture and Technology Stack

## 1. Project Overview

Project name:

**Little Joy Florist Ordering System**

Project type:

**Web-based flower ordering information system**

Research object:

**Florist Little Joy Jakarta**

The system will support:

- product catalogue;
- customer registration and login;
- shopping cart;
- checkout;
- manual bank transfer;
- payment-proof upload;
- payment verification;
- order-status tracking;
- product and category management;
- stock management;
- customer management;
- sales dashboard;
- sales reports.

## 2. Recommended Architecture

Use a monolithic Laravel application with React through Inertia.js.

```text
Browser
   ↓
React + Inertia.js
   ↓
Laravel Routes
   ↓
Laravel Controllers
   ↓
Form Requests / Policies / Services
   ↓
Eloquent ORM
   ↓
MySQL
```

Laravel remains responsible for:

- server-side routing;
- authentication;
- authorization;
- validation;
- business logic;
- database transactions;
- Eloquent models;
- migration;
- factory;
- seeder;
- file storage;
- automated testing.

React remains responsible for:

- user interface;
- page rendering;
- reusable components;
- forms and interactions;
- responsive layouts;
- dashboard visualization;
- local UI state.

Inertia.js connects Laravel and React.

Do not create a separate REST API for the MVP unless a concrete requirement needs it.

## 3. Technology Stack

### Backend

- Laravel
- PHP
- Laravel Eloquent ORM
- Laravel Form Request
- Laravel Policy
- Laravel Service Classes
- Laravel Database Transactions
- Laravel Storage

### Frontend

- React
- TypeScript
- Inertia.js
- Tailwind CSS
- Vite
- Lucide React or Material Symbols
- Recharts for dashboard charts if needed

### Authentication

Use Laravel session authentication with:

- CSRF protection;
- password hashing;
- role-based middleware;
- Laravel policies.

Do not use JWT for this application.

### Database

- MySQL
- phpMyAdmin as a database administration tool
- Local MySQL through Laragon or XAMPP

phpMyAdmin is not the database. It is only a tool for inspecting and administering MySQL.

### Testing

- PHPUnit or Pest, according to the repository configuration
- Laravel Feature Tests
- Laravel Unit Tests
- React component tests only for complex interactive components
- Manual Black Box Testing

## 4. Architecture Options

### Option A: Laravel Blade and Tailwind

Use when:

- the team has limited React experience;
- development time is short;
- frontend interactions are simple;
- the priority is completing the thesis safely.

### Option B: Laravel, React, and Inertia.js

Use when:

- the team understands basic React;
- a modern dashboard is desired;
- the Stitch design will be converted into reusable React components;
- the application remains in one repository.

This is the recommended option.

### Option C: Laravel REST API and React SPA

Use only when:

- frontend and backend must be deployed independently;
- a mobile application will consume the same API;
- multiple clients need the same backend.

Do not use this option for the initial MVP.

## 5. Development Environment

```text
PHP               : Compatible with selected Laravel version
Composer          : Latest compatible version
Node.js           : Active LTS version
Package manager   : npm
Database          : MySQL
Database tool     : phpMyAdmin
Local server      : Laragon or XAMPP
Frontend build    : Vite
```

## 6. Database Environments

Create separate databases:

```text
little_joy_florist
little_joy_florist_testing
```

Development database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=little_joy_florist
DB_USERNAME=root
DB_PASSWORD=
```

Testing database:

```env
APP_ENV=testing
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=little_joy_florist_testing
DB_USERNAME=root
DB_PASSWORD=
CACHE_STORE=array
SESSION_DRIVER=array
QUEUE_CONNECTION=sync
MAIL_MAILER=array
```

Never run destructive automated tests against the development database.

## 7. Database Management Rules

1. Laravel migrations are the source of truth.
2. Do not create application tables manually through phpMyAdmin.
3. Use foreign-key constraints.
4. Use indexes on searchable columns.
5. Use unique constraints where appropriate.
6. Use database transactions for checkout and payment verification.
7. Do not use floating-point types for money.
8. Preserve historical order prices.

## 8. Frontend Structure

```text
resources/js/
├── app.tsx
├── types/
├── layouts/
├── components/
│   ├── common/
│   ├── navigation/
│   ├── products/
│   ├── orders/
│   ├── dashboard/
│   └── forms/
├── pages/
│   ├── Public/
│   ├── Auth/
│   ├── Customer/
│   ├── Operator/
│   └── Admin/
├── hooks/
├── utils/
└── constants/
```

## 9. Backend Structure

```text
app/
├── Enums/
├── Http/
│   ├── Controllers/
│   ├── Middleware/
│   └── Requests/
├── Models/
├── Policies/
├── Services/
├── Actions/
└── Support/
```

## 10. Routing Strategy

Use Laravel named routes and Inertia links.

Do not use React Router for primary navigation.

## 11. Security Requirements

- Use CSRF protection.
- Hash all passwords.
- Use server-side validation.
- Use Laravel policies.
- Recalculate totals on the server.
- Validate uploaded files.
- Protect role-based routes.
- Store secrets in `.env`.
- Use transactions for critical operations.

## 12. Final Decision

```text
Laravel
React
TypeScript
Inertia.js
Tailwind CSS
MySQL
phpMyAdmin
Vite
Laravel session authentication
```
