# SPARK - Sanjivani Platform for AI, Research & Knowledge

## Project Overview
A comprehensive dual-panel university club management website built with PHP and MySQL for Sanjivani University's SPARK platform.

## Tech Stack
- **Frontend**: HTML5, CSS3, JavaScript, Bootstrap 5, jQuery
- **Backend**: PHP 8.2
- **Database**: MySQL (MySQLi)
- **Payment Gateway**: Razorpay (Test Mode)
- **Export**: Excel (CSV format)

## Project Structure
```
spark/
├── student/                 # Student Panel
│   ├── index.php           # Home page
│   ├── events.php          # Events listing and registration
│   ├── team.php            # Team members showcase
│   ├── contact.php         # Contact form
│   ├── certificate.php     # Certificate verification
│   ├── payment.php         # Payment page
│   └── register_event.php  # Event registration handler
├── admin/                   # Admin Panel
│   ├── login.php           # Admin login
│   ├── dashboard.php       # Dashboard with statistics
│   ├── events.php          # Event management (CRUD)
│   ├── team.php            # Team management (CRUD)
│   ├── registrations.php   # Registration management
│   ├── payments.php        # Payment tracking
│   ├── certificates.php    # Certificate management
│   └── contacts.php        # Contact messages
├── config/                  # Configuration files
│   └── database.php        # Database connection
├── includes/                # Shared includes
│   ├── header.php          # Student panel header
│   └── footer.php          # Student panel footer
├── assets/                  # Static assets
│   ├── css/style.css       # Stylesheet
│   ├── js/script.js        # JavaScript
│   └── uploads/            # Upload directories
└── database/
    └── schema.sql          # MySQL database schema
```

## Features Implemented

### Student Panel
1. **Home Page**: Club vision, mission, technologies, and benefits
2. **Events**: Browse events, view details, and register with payment
3. **Team**: View team member profiles with social links
4. **Contact**: Submit inquiries and view contact information
5. **Certificate**: Verify and download certificates by code

### Admin Panel
1. **Dashboard**: Statistics and metrics overview
2. **Event Management**: Full CRUD operations for events
3. **Team Management**: Add, edit, delete team members
4. **Registration Management**: View registrations, export to Excel/PDF
5. **Payment Tracking**: Monitor all payment transactions
6. **Certificate Management**: Issue and manage certificates
7. **Contact Messages**: View and respond to contact submissions

## Database Setup

### Default Admin Credentials
- **Username**: admin
- **Password**: password

### Database Configuration
Update `config/database.php` with your MySQL credentials:
```php
private $host = "localhost";
private $username = "root";
private $password = "";
private $database = "spark_db";
```

### Import Database Schema
```bash
mysql -u root -p < database/schema.sql
```

## Student Information Fields
1. First Name, Middle Name, Last Name
2. PRN (Student ID)
3. Contact Number
4. Email Address
5. Department: CSE, CY, AIML, ALDS
6. Year: FY, SY, TY, FINAL YEAR

## Payment Integration
Currently running in **test mode**. The Razorpay payment gateway is set up for testing purposes and will mark payments as successful automatically. To enable live payments:

1. Get Razorpay API credentials
2. Update payment processing files with live keys
3. Implement webhook handlers for payment verification

## Email Functionality
Basic email functionality is implemented using PHP's `mail()` function. For production:
- Configure SMTP settings
- Use PHPMailer for better reliability
- Set up email templates

## Export Features
- **Excel Export**: Implemented using basic HTML table export
- **PDF Export**: Requires FPDF or TCPDF library installation

## Deployment Notes
- Ensure PHP 8.2+ is installed
- Configure MySQL database connection
- Set proper file permissions for upload directories
- Enable mod_rewrite for .htaccess support

## Recent Changes
- Created complete dual-panel website (October 20, 2025)
- Implemented all CRUD operations for admin panel
- Added payment integration in test mode
- Created responsive design with Bootstrap 5

## Future Enhancements
- Live Razorpay payment integration
- Automated email notifications
- PDF certificate generation
- Advanced analytics dashboard
- Bulk certificate generation
- Student profile management
- Image upload for events and team members
