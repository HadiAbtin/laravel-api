# Laravel API Starter Kit Setup Guide

This guide contains all the necessary steps to set up the Laravel API project.

## 📋 Prerequisites

### Required Software:
- **PHP 8.0+** (Installed version: 8.4.12)
- **Composer** (PHP dependency manager)
- **MySQL** (Database)
- **Redis** (Caching and Session storage)
- **Package Manager**: Homebrew (macOS) or APT (Debian/Ubuntu)

## 🚀 Setup Steps

### 1. Install System Dependencies

#### For macOS (using Homebrew):

```bash
# Install PHP and Composer
brew install php composer

# Install MySQL
brew install mysql

# Install Redis
brew install redis
```

#### For Debian/Ubuntu (using APT):

```bash
# Update package list
sudo apt update

# Install PHP and required extensions
sudo apt install php8.1 php8.1-cli php8.1-fpm php8.1-mysql php8.1-xml php8.1-mbstring php8.1-curl php8.1-zip php8.1-bcmath php8.1-gd

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install MySQL Server
sudo apt install mysql-server

# Install Redis
sudo apt install redis-server

# Install additional tools
sudo apt install curl git unzip
```

### 2. Setup MySQL

#### For macOS:

```bash
# Start MySQL service
brew services start mysql

# Create database
mysql -u root -e "CREATE DATABASE laravel_api;"
```

#### For Debian/Ubuntu:

```bash
# Start MySQL service
sudo systemctl start mysql
sudo systemctl enable mysql

# Secure MySQL installation (optional but recommended)
sudo mysql_secure_installation

# Create database
sudo mysql -u root -e "CREATE DATABASE laravel_api;"

# Create MySQL user (optional - for better security)
sudo mysql -u root -e "CREATE USER 'laravel_user'@'localhost' IDENTIFIED BY 'your_password';"
sudo mysql -u root -e "GRANT ALL PRIVILEGES ON laravel_api.* TO 'laravel_user'@'localhost';"
sudo mysql -u root -e "FLUSH PRIVILEGES;"
```

### 3. Setup Redis

#### For macOS:

```bash
# Start Redis service
brew services start redis

# Test Redis connection
redis-cli ping
# Expected output: PONG
```

#### For Debian/Ubuntu:

```bash
# Start Redis service
sudo systemctl start redis-server
sudo systemctl enable redis-server

# Test Redis connection
redis-cli ping
# Expected output: PONG

# Check Redis status
sudo systemctl status redis-server
```

### 4. Install PHP Redis Extension

#### For macOS:

```bash
# Install PHP Redis extension
pecl install redis

# Verify installation
php -m | grep redis
# Expected output: redis
```

#### For Debian/Ubuntu:

```bash
# Install PHP Redis extension
sudo apt install php8.1-redis

# Restart PHP-FPM (if using)
sudo systemctl restart php8.1-fpm

# Verify installation
php -m | grep redis
# Expected output: redis
```

### 5. Configure Project

```bash
# Navigate to project directory
cd /path/to/your/laravel-api/laravel-api

# Create environment file (.env)
cat > .env << 'EOF'
APP_NAME=Laravel API
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_api
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_DRIVER=log
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
REDIS_CLIENT=predis

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=null
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=mt1

MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
EOF
```

**Note for Debian/Ubuntu users**: If you created a MySQL user instead of using root, update the database credentials in `.env`:
```
DB_USERNAME=laravel_user
DB_PASSWORD=your_password
```

### 6. Install Project Dependencies

```bash
# Install Predis (Redis client for PHP)
composer require predis/predis

# Update dependencies for PHP compatibility
composer update

# Install dependencies
composer install
```

**Note for Debian/Ubuntu users**: If you encounter permission issues, you may need to set proper permissions:
```bash
# Set proper permissions for storage and cache directories
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### 7. Generate Application Key

```bash
# Generate Laravel application key
php artisan key:generate
```

### 8. Run Migrations

```bash
# Run migrations to create database tables
php artisan migrate
```

### 9. Setup Laravel Passport

```bash
# Install and configure Laravel Passport for OAuth2
php artisan passport:install
```

**Expected Output:**
```
Encryption keys generated successfully.
Personal access client created successfully.
Client ID: 1
Client secret: [SECRET_KEY]
Password grant client created successfully.
Client ID: 2
Client secret: [SECRET_KEY]
```

### 10. Run Seeders

```bash
# Run seeders to create initial data
php artisan db:seed
```

### 11. Clear and Optimize Cache

```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize for production (optional)
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 12. Start Server

#### For macOS:

```bash
# Start Laravel server
php artisan serve --host=0.0.0.0 --port=8000
```

#### For Debian/Ubuntu:

```bash
# Start Laravel server
php artisan serve --host=0.0.0.0 --port=8000
```

**Alternative for Debian/Ubuntu**: You can also use Apache or Nginx as web server:

```bash
# Install Apache (optional)
sudo apt install apache2 libapache2-mod-php8.1

# Enable required modules
sudo a2enmod rewrite
sudo a2enmod php8.1

# Configure Apache virtual host (optional)
sudo nano /etc/apache2/sites-available/laravel-api.conf
```

Apache virtual host configuration:
```apache
<VirtualHost *:80>
    ServerName laravel-api.local
    DocumentRoot /path/to/your/laravel-api/laravel-api/public
    
    <Directory /path/to/your/laravel-api/laravel-api/public>
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/laravel-api_error.log
    CustomLog ${APACHE_LOG_DIR}/laravel-api_access.log combined
</VirtualHost>
```

## 🧪 Testing

### Redis Connection Test

```bash
# Test Redis connection
redis-cli ping
# Expected output: PONG

# Test Redis from Laravel
php artisan tinker
>>> Cache::put('test', 'Redis is working!', 60)
>>> Cache::get('test')
# Expected output: "Redis is working!"
```

### API Health Check

```bash
curl -X GET http://localhost:8000/api/ping
```

**Expected Response:**
```json
{
  "status": "ok",
  "timestamp": "2025-09-05T11:38:57.689110Z",
  "host": "127.0.0.1"
}
```

### Performance Test with Redis

```bash
# Test cache performance (if performance test controller is available)
curl -X GET http://localhost:8000/api/performance/without-cache
curl -X GET http://localhost:8000/api/performance/with-cache
curl -X GET http://localhost:8000/api/performance/cache-stats
```

### User Registration Test

```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

**Expected Response:**
```json
{
  "data": {
    "id": "3e44570c-beb8-4085-8d30-1519b3283918",
    "name": "Test User",
    "email": "test@example.com",
    "created_at": "2025-09-05T11:39:11+00:00",
    "updated_at": "2025-09-05T11:39:11+00:00",
    "roles": {
      "data": [
        {
          "id": "c438ede6-40de-4e28-a779-112ae294586a",
          "name": "User",
          "created_at": "2025-09-05T11:38:45+00:00",
          "updated_at": "2025-09-05T11:38:45+00:00",
          "permissions": {
            "data": []
          }
        }
      ]
    }
  }
}
```

## 🌐 API Access

- **Server URL**: `http://localhost:8000`
- **API Endpoint**: `http://localhost:8000/api/`

## 📋 API Routes

### Public Routes (No Authentication Required)

| Method | Route | Description |
|--------|-------|-------------|
| GET | `/api/ping` | API health check |
| POST | `/api/register` | Register new user |
| POST | `/api/passwords/reset` | Request password reset |
| PUT | `/api/passwords/reset` | Reset password |
| GET | `/api/assets/{uuid}/render` | Display file |

### Protected Routes (OAuth2 Authentication Required)

| Method | Route | Description |
|--------|-------|-------------|
| GET | `/api/users` | List users |
| POST | `/api/users` | Create new user |
| GET | `/api/users/{uuid}` | Show user |
| PUT | `/api/users/{uuid}` | Update user |
| DELETE | `/api/users/{uuid}` | Delete user |
| GET | `/api/roles` | List roles |
| POST | `/api/roles` | Create new role |
| GET | `/api/roles/{uuid}` | Show role |
| PUT | `/api/roles/{uuid}` | Update role |
| DELETE | `/api/roles/{uuid}` | Delete role |
| GET | `/api/permissions` | List permissions |
| GET | `/api/me` | Current user profile |
| PUT | `/api/me` | Update profile |
| PUT | `/api/me/password` | Change password |
| POST | `/api/assets` | Upload file |

## 🔑 OAuth2 Authentication

### Client Credentials

**Personal Access Client:**
- Client ID: `1`
- Client Secret: `FYFV17hutPH2h7k9OyW5xzU4MZEMOrOD9jvdRUE0`

**Password Grant Client:**
- Client ID: `2`
- Client Secret: `jU0NwbC53HqdjxAoCHn5xweEHj32zcKjZblFFl2N`

### Authentication Request Example

```bash
curl -X POST http://localhost:8000/oauth/token \
  -H "Content-Type: application/json" \
  -d '{
    "grant_type": "password",
    "client_id": "2",
    "client_secret": "jU0NwbC53HqdjxAoCHn5xweEHj32zcKjZblFFl2N",
    "username": "test@example.com",
    "password": "password123"
  }'
```

## 📚 API Documentation

To generate API documentation:

```bash
# Install required tools
npm install -g aglio merge-apib

# Generate documentation
composer api-docs
```

Documentation will be generated in `resources/views/apidocs.blade.php`.

## 🗄️ Database Structure

### Main Tables:

- **users**: User information
- **roles**: User roles
- **permissions**: Access permissions
- **model_has_roles**: Role assignments to users
- **model_has_permissions**: Permission assignments to users
- **role_has_permissions**: Permission assignments to roles
- **assets**: Uploaded files
- **oauth_***: Laravel Passport tables

## ⚠️ Important Notes

1. **Deprecation Warnings**: The deprecation messages are related to PHP 8.4 compatibility with Laravel 9 and do not affect functionality.

2. **Security**: In production environment, make sure to:
   - Set database password
   - Set `APP_DEBUG=false`
   - Change OAuth2 keys

3. **File Uploads**: Files are stored in `storage/app/` directory.

## 🔧 Troubleshooting

### Common Issues:

1. **Database Connection Error**:

   **For macOS**:
   ```bash
   # Check MySQL status
   brew services list | grep mysql
   
   # Restart MySQL
   brew services restart mysql
   ```

   **For Debian/Ubuntu**:
   ```bash
   # Check MySQL status
   sudo systemctl status mysql
   
   # Restart MySQL
   sudo systemctl restart mysql
   
   # Check MySQL logs
   sudo journalctl -u mysql
   ```

2. **Application Key Error**:
   ```bash
   php artisan key:generate
   ```

3. **Permission Error**:

   **For macOS**:
   ```bash
   # Set storage directory permissions
   chmod -R 775 storage bootstrap/cache
   ```

   **For Debian/Ubuntu**:
   ```bash
   # Set storage directory permissions
   sudo chown -R www-data:www-data storage bootstrap/cache
   sudo chmod -R 775 storage bootstrap/cache
   
   # If using different user
   sudo chown -R $USER:$USER storage bootstrap/cache
   sudo chmod -R 775 storage bootstrap/cache
   ```

4. **PHP Extension Missing (Debian/Ubuntu)**:
   ```bash
   # Install missing PHP extensions
   sudo apt install php8.1-mysql php8.1-xml php8.1-mbstring php8.1-curl php8.1-zip php8.1-bcmath php8.1-gd
   
   # Restart web server
   sudo systemctl restart apache2  # if using Apache
   sudo systemctl restart nginx    # if using Nginx
   ```

5. **Composer Memory Limit Error**:
   ```bash
   # Increase PHP memory limit
   php -d memory_limit=2G /usr/local/bin/composer install
   
   # Or set in php.ini
   sudo nano /etc/php/8.1/cli/php.ini
   # Change: memory_limit = 2G
   ```

6. **Redis Connection Error**:

   **For macOS**:
   ```bash
   # Check Redis status
   brew services list | grep redis
   
   # Restart Redis
   brew services restart redis
   
   # Test connection
   redis-cli ping
   ```

   **For Debian/Ubuntu**:
   ```bash
   # Check Redis status
   sudo systemctl status redis-server
   
   # Restart Redis
   sudo systemctl restart redis-server
   
   # Test connection
   redis-cli ping
   
   # Check Redis logs
   sudo journalctl -u redis-server
   ```

7. **PHP Redis Extension Missing**:

   **For macOS**:
   ```bash
   # Install PHP Redis extension
   pecl install redis
   
   # Verify installation
   php -m | grep redis
   ```

   **For Debian/Ubuntu**:
   ```bash
   # Install PHP Redis extension
   sudo apt install php8.1-redis
   
   # Restart PHP-FPM (if using)
   sudo systemctl restart php8.1-fpm
   
   # Verify installation
   php -m | grep redis
   ```

8. **MySQL Authentication Error (Debian/Ubuntu)**:
   ```bash
   # Reset MySQL root password
   sudo mysql
   ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'new_password';
   FLUSH PRIVILEGES;
   EXIT;
   ```

## 📞 Support

For more information, refer to Laravel documentation and used packages:
- [Laravel Documentation](https://laravel.com/docs)
- [Laravel Passport](https://laravel.com/docs/passport)
- [Spatie Permission](https://spatie.be/docs/laravel-permission)
- [Redis Documentation](https://redis.io/documentation)
- [Predis Documentation](https://github.com/predis/predis)

---

**Created**: September 5, 2025  
**Laravel Version**: 9.52.20  
**PHP Version**: 8.4.12 (macOS) / 8.1+ (Debian/Ubuntu)  
**Redis Version**: Latest (via package manager)  
**Supported OS**: macOS, Debian, Ubuntu
