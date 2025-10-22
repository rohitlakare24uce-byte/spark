# SPARK - Sanjivani Platform for AI, Research & Knowledge

A comprehensive university club management website with dual panels for students and administrators.

## Quick Start

### Database Setup
1. Import the database schema:
```bash
mysql -u root -p < database/schema.sql
```

2. Update database credentials in `config/database.php`

### Run the Website
```bash
php -S localhost:5000
```

Visit: `http://localhost:5000`

## Admin Access
- **URL**: `/admin/login.php`
- **Username**: admin
- **Password**: password

## Features

### Student Panel
- Browse events and register
- View team members
- Verify certificates
- Contact form

### Admin Panel
- Dashboard with statistics
- Manage events, team, registrations
- Track payments
- Issue certificates
- View contact messages

## Tech Stack
- PHP 8.2
- MySQL
- Bootstrap 5
- jQuery

## License
© 2025 SPARK - Sanjivani University
