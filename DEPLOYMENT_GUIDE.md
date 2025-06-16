# Cantina Smart Sales - PHP Deployment Guide

## Overview
This is a complete PHP-based point-of-sale system for canteen management, converted from the original TypeScript React application. The system maintains all original functionality while using PHP, HTML, CSS, and JavaScript.

## System Requirements

### Server Requirements
- **PHP**: 7.4 or higher (8.0+ recommended)
- **MySQL**: 5.7 or higher (8.0+ recommended)
- **Web Server**: Apache 2.4+ or Nginx 1.18+
- **Memory**: Minimum 512MB RAM (1GB+ recommended)
- **Storage**: Minimum 100MB free space

### PHP Extensions Required
- PDO
- PDO_MySQL
- JSON
- Session
- OpenSSL (for password hashing)

## Installation Steps

### 1. Download and Extract Files
```bash
# Extract the project files to your web server directory
# For Apache: /var/www/html/cantina-smart-sales/
# For XAMPP: C:\xampp\htdocs\cantina-smart-sales\
# For WAMP: C:\wamp64\www\cantina-smart-sales\
```

### 2. Database Setup

#### Option A: Using phpMyAdmin
1. Open phpMyAdmin in your browser
2. Create a new database named `cantina_smart_sales`
3. Import the SQL file: `database/schema.sql`
4. Verify all tables are created successfully

#### Option B: Using MySQL Command Line
```bash
# Login to MySQL
mysql -u root -p

# Create database
CREATE DATABASE cantina_smart_sales;

# Import schema
mysql -u root -p cantina_smart_sales < database/schema.sql
```

### 3. Database Configuration
Edit the file `config/database.php` with your database credentials:

```php
<?php
// Database configuration
define('DB_HOST', 'localhost');        // Your database host
define('DB_NAME', 'cantina_smart_sales'); // Database name
define('DB_USER', 'root');             // Your database username
define('DB_PASS', '');                 // Your database password
?>
```

### 4. Web Server Configuration

#### For Apache (.htaccess)
Create a `.htaccess` file in the root directory:
```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]

# Security headers
Header always set X-Content-Type-Options nosniff
Header always set X-Frame-Options DENY
Header always set X-XSS-Protection "1; mode=block"

# Cache static assets
<FilesMatch "\.(css|js|png|jpg|jpeg|gif|ico|svg)$">
    ExpiresActive On
    ExpiresDefault "access plus 1 month"
</FilesMatch>
```

#### For Nginx
Add this to your Nginx configuration:
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/html/cantina-smart-sales;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.0-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~* \.(css|js|png|jpg|jpeg|gif|ico|svg)$ {
        expires 1M;
        add_header Cache-Control "public, immutable";
    }
}
```

### 5. File Permissions
Set appropriate permissions for your web server:

```bash
# For Linux/Unix systems
chmod 755 /var/www/html/cantina-smart-sales
chmod 644 /var/www/html/cantina-smart-sales/*.php
chmod 644 /var/www/html/cantina-smart-sales/assets/css/*
chmod 644 /var/www/html/cantina-smart-sales/assets/js/*

# Make sure the web server can read all files
chown -R www-data:www-data /var/www/html/cantina-smart-sales
```

## Default Login Credentials

The system comes with three pre-configured user accounts:

| Role | Email | Password | Permissions |
|------|-------|----------|-------------|
| **Admin** | admin@example.com | admin | Full system access |
| **Manager** | manager@example.com | Manager | Reports, inventory, user management |
| **Cashier** | caixa@example.com | cashier | Sales, basic inventory |

**⚠️ IMPORTANT**: Change these default passwords immediately after installation!

## Features Overview

### Core Functionality
- **Product Management**: Add, edit, delete products with categories
- **Sales Processing**: Point-of-sale with cart functionality
- **Inventory Management**: Stock tracking and low-stock alerts
- **Student Accounts**: Prepaid account system for students/teachers
- **Debt Management**: Track and manage student debts
- **Reporting**: Sales reports, inventory reports, financial summaries
- **User Management**: Role-based access control
- **Multi-language Support**: English and Portuguese

### User Roles & Permissions

#### Admin
- Full system access
- User management
- Financial reports
- System configuration
- Data export/import

#### Manager
- Product management
- Inventory control
- Sales reports
- Account management
- Limited user management

#### Cashier (Caixa)
- Process sales
- View products
- Basic inventory viewing
- Student account transactions

## Database Schema

### Main Tables
- **users**: System users and authentication
- **products**: Product catalog with pricing and stock
- **sales**: Transaction records
- **student_accounts**: Prepaid accounts for students/teachers
- **account_transactions**: Account balance changes
- **debts**: Student debt tracking
- **debt_payments**: Debt payment history
- **purchases**: Inventory purchases
- **expenses**: Business expenses
- **categories**: Product categories

### Key Relationships
- Sales → Products (product information)
- Sales → Users (who processed the sale)
- Sales → Student Accounts (account-based payments)
- Account Transactions → Student Accounts
- Debt Payments → Debts

## API Endpoints

The system includes RESTful API endpoints for AJAX functionality:

### Products API (`api/products.php`)
- `GET /api/products.php` - List all products
- `GET /api/products.php?id={id}` - Get single product
- `POST /api/products.php` - Create new product
- `PUT /api/products.php` - Update product
- `DELETE /api/products.php?id={id}` - Delete product

### Sales API (`api/sales.php`)
- `POST /api/sales.php` - Process new sale
- `GET /api/sales.php` - Get sales data (manager+)

### Accounts API (`api/accounts.php`)
- `GET /api/accounts.php` - List student accounts
- `POST /api/accounts.php` - Create new account
- `PUT /api/accounts.php` - Update account balance

## Security Features

### Authentication
- Session-based authentication
- Password hashing using PHP's `password_hash()`
- Role-based access control
- Automatic session timeout

### Data Protection
- SQL injection prevention using PDO prepared statements
- XSS protection through proper output escaping
- CSRF protection for forms
- Input validation and sanitization

### Security Headers
- X-Content-Type-Options: nosniff
- X-Frame-Options: DENY
- X-XSS-Protection: 1; mode=block

## Backup and Maintenance

### Database Backup
```bash
# Create backup
mysqldump -u root -p cantina_smart_sales > backup_$(date +%Y%m%d_%H%M%S).sql

# Restore backup
mysql -u root -p cantina_smart_sales < backup_file.sql
```

### File Backup
```bash
# Create complete backup
tar -czf cantina_backup_$(date +%Y%m%d_%H%M%S).tar.gz /var/www/html/cantina-smart-sales/
```

### Regular Maintenance
1. **Weekly**: Database backup
2. **Monthly**: Clear old session files
3. **Quarterly**: Update PHP and dependencies
4. **Annually**: Security audit and password updates

## Troubleshooting

### Common Issues

#### "Database connection failed"
- Check database credentials in `config/database.php`
- Verify MySQL service is running
- Ensure database exists and user has permissions

#### "Page not found" errors
- Check web server configuration
- Verify `.htaccess` file exists (Apache)
- Check file permissions

#### "Session not working"
- Verify PHP session configuration
- Check session directory permissions
- Ensure cookies are enabled in browser

#### "Permission denied" errors
- Check file/directory permissions
- Verify web server user ownership
- Review PHP error logs

### Log Files
- **PHP Errors**: Check `/var/log/apache2/error.log` or `/var/log/nginx/error.log`
- **MySQL Errors**: Check `/var/log/mysql/error.log`
- **Application Logs**: Enable error reporting in PHP for debugging

## Performance Optimization

### Database Optimization
- Regular `OPTIMIZE TABLE` commands
- Monitor slow query log
- Add indexes for frequently queried columns
- Regular database maintenance

### Caching
- Enable PHP OPcache
- Use browser caching for static assets
- Consider Redis for session storage (high traffic)

### Web Server Optimization
- Enable gzip compression
- Optimize image sizes
- Minify CSS/JavaScript files
- Use CDN for static assets (production)

## Deployment Environments

### Development
- Use XAMPP/WAMP for local development
- Enable error reporting and debugging
- Use development database

### Production
- Disable error reporting
- Use production database with backups
- Enable security headers
- Use HTTPS
- Regular security updates

## Support and Updates

### Getting Help
1. Check this documentation first
2. Review error logs
3. Check database connectivity
4. Verify file permissions

### Version Updates
- Always backup before updating
- Test updates in development environment
- Review changelog for breaking changes
- Update database schema if required

## License and Credits

This system is based on the original TypeScript React application and has been converted to PHP while maintaining all functionality and design elements.

---

**Note**: This deployment guide covers the basic setup. For production environments, additional security measures, monitoring, and backup strategies should be implemented based on your specific requirements.