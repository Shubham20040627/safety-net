# Neighborhood Safety and Incident Reporting System

A professional, SaaS-grade Laravel 10 application for community safety management.

## Features

- **Authentication**: Laravel Breeze with Role-Based Access Control (RBAC).
- **Admin Approval Workflow**: New users are 'pending' by default and must be approved by an admin.
- **Incident Reporting**: Users can report incidents with titles, descriptions, types, locations, and images.
- **Advanced Dashboard**: Visual stats and recent activity tracking.
- **Filtering & Search**: Filter incidents by type (Crime, Accident, Suspicious, Other) and search by location.
- **Admin Panel**: Manage users (Approve/Reject) and manage reports (Resolve/Delete).
- **Responsive Design**: Premium UI built with Tailwind CSS and Inter font.

## Installation

1. **Clone the project**
2. **Install dependencies**:
   ```bash
   composer install
   npm install && npm run build
   ```
3. **Configure Database**:
   Update `.env` with your MySQL credentials.
4. **Run Migrations & Seeders**:
   ```bash
   php artisan migrate --seed
   ```
5. **Start the application**:
   ```bash
   php artisan serve
   ```

## Default Admin Credentials
- **Email**: `admin@safety.com`
- **Password**: `password`

## Default User Credentials
- **Email**: `john@example.com`
- **Password**: `password`
