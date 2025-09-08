# Environment Variables Guide

This document provides a comprehensive explanation of all environment variables used in the Laravel API project.

## 📋 Overview

The `.env` file contains configuration settings for the Laravel application. These variables control various aspects of the application including database connections, caching, sessions, email, and third-party services.

## 🔧 Application Configuration

### **APP_NAME**
- **Description**: The name of your application
- **Default**: `Laravel`
- **Example**: `Laravel API`
- **Usage**: Used in email templates, logs, and application headers

### **APP_ENV**
- **Description**: Application environment
- **Values**: `local`, `staging`, `production`
- **Default**: `local`
- **Usage**: Determines error reporting level and debug settings

### **APP_KEY**
- **Description**: Application encryption key
- **Format**: `base64:...`
- **Usage**: Used for encrypting cookies, sessions, and other sensitive data
- **Generation**: `php artisan key:generate`

### **APP_DEBUG**
- **Description**: Enable/disable debug mode
- **Values**: `true`, `false`
- **Default**: `true`
- **Usage**: Shows detailed error messages when enabled

### **APP_URL**
- **Description**: Application URL
- **Default**: `http://localhost`
- **Example**: `http://localhost:8000`
- **Usage**: Used for generating URLs in emails and API responses

## 🗄️ Database Configuration

### **DB_CONNECTION**
- **Description**: Database driver
- **Values**: `mysql`, `pgsql`, `sqlite`, `sqlsrv`
- **Default**: `mysql`
- **Usage**: Specifies which database driver to use

### **DB_HOST**
- **Description**: Database host address
- **Default**: `127.0.0.1`
- **Example**: `localhost`, `db.example.com`
- **Usage**: IP address or hostname of the database server

### **DB_PORT**
- **Description**: Database port
- **Default**: `3306` (MySQL), `5432` (PostgreSQL)
- **Usage**: Port number for database connection

### **DB_DATABASE**
- **Description**: Database name
- **Default**: `laravel`
- **Example**: `laravel_api`, `myapp_production`
- **Usage**: Name of the database to connect to

### **DB_USERNAME**
- **Description**: Database username
- **Default**: `root`
- **Usage**: Username for database authentication

### **DB_PASSWORD**
- **Description**: Database password
- **Default**: Empty
- **Usage**: Password for database authentication

## 📝 Logging Configuration

### **LOG_CHANNEL**
- **Description**: Logging channel
- **Values**: `stack`, `single`, `daily`, `slack`, `papertrail`, `syslog`, `errorlog`
- **Default**: `stack`
- **Usage**: Determines how logs are handled

### **LOG_LEVEL**
- **Description**: Logging level
- **Values**: `emergency`, `alert`, `critical`, `error`, `warning`, `notice`, `info`, `debug`
- **Default**: `debug`
- **Usage**: Minimum level of messages to log

## 🗄️ Cache Configuration

### **CACHE_DRIVER**
- **Description**: Cache storage driver
- **Values**: `file`, `redis`, `database`, `memcached`, `array`
- **Default**: `file`
- **Usage**: Determines where cached data is stored

## ⚡ Queue Configuration

### **QUEUE_CONNECTION**
- **Description**: Queue driver
- **Values**: `sync`, `database`, `redis`, `sqs`, `beanstalkd`
- **Default**: `sync`
- **Usage**: Determines how background jobs are processed

## 🔐 Session Configuration

### **SESSION_DRIVER**
- **Description**: Session storage driver
- **Values**: `file`, `redis`, `database`, `cookie`, `array`
- **Default**: `file`
- **Usage**: Determines where session data is stored

### **SESSION_LIFETIME**
- **Description**: Session lifetime in minutes
- **Default**: `120`
- **Usage**: How long sessions remain valid

## 📡 Broadcasting Configuration

### **BROADCAST_DRIVER**
- **Description**: Broadcasting driver
- **Values**: `log`, `pusher`, `redis`, `null`
- **Default**: `log`
- **Usage**: Determines how real-time events are broadcast

## 🔴 Redis Configuration

### **REDIS_HOST**
- **Description**: Redis server host
- **Default**: `127.0.0.1`
- **Usage**: IP address or hostname of Redis server

### **REDIS_PASSWORD**
- **Description**: Redis password
- **Default**: `null`
- **Usage**: Password for Redis authentication (if required)

### **REDIS_PORT**
- **Description**: Redis server port
- **Default**: `6379`
- **Usage**: Port number for Redis connection

### **REDIS_CLIENT**
- **Description**: Redis client library
- **Values**: `predis`, `phpredis`
- **Default**: `predis`
- **Usage**: PHP library used to connect to Redis

## 🚀 Memcached Configuration

### **MEMCACHED_HOST**
- **Description**: Memcached server host
- **Default**: `127.0.0.1`
- **Usage**: IP address or hostname of Memcached server

## 📧 Mail Configuration

### **MAIL_MAILER**
- **Description**: Mail driver
- **Values**: `smtp`, `sendmail`, `mailgun`, `ses`, `postmark`, `array`, `log`
- **Default**: `smtp`
- **Usage**: Determines how emails are sent

### **MAIL_HOST**
- **Description**: SMTP server host
- **Default**: `mailhog` (for development)
- **Example**: `smtp.gmail.com`, `mail.example.com`
- **Usage**: SMTP server address

### **MAIL_PORT**
- **Description**: SMTP server port
- **Default**: `1025` (Mailhog), `587` (Gmail), `465` (SSL)
- **Usage**: Port number for SMTP connection

### **MAIL_USERNAME**
- **Description**: SMTP username
- **Default**: `null`
- **Usage**: Username for SMTP authentication

### **MAIL_PASSWORD**
- **Description**: SMTP password
- **Default**: `null`
- **Usage**: Password for SMTP authentication

### **MAIL_ENCRYPTION**
- **Description**: SMTP encryption
- **Values**: `null`, `tls`, `ssl`
- **Default**: `null`
- **Usage**: Encryption method for SMTP connection

### **MAIL_FROM_ADDRESS**
- **Description**: Default sender email
- **Default**: `null`
- **Example**: `noreply@example.com`
- **Usage**: Default email address for outgoing emails

### **MAIL_FROM_NAME**
- **Description**: Default sender name
- **Default**: `"${APP_NAME}"`
- **Usage**: Default name for outgoing emails

## ☁️ AWS Configuration

### **AWS_ACCESS_KEY_ID**
- **Description**: AWS access key
- **Default**: Empty
- **Usage**: Used for AWS services (S3, SES, SQS)

### **AWS_SECRET_ACCESS_KEY**
- **Description**: AWS secret key
- **Default**: Empty
- **Usage**: Used for AWS services authentication

### **AWS_DEFAULT_REGION**
- **Description**: AWS region
- **Default**: `us-east-1`
- **Example**: `us-west-2`, `eu-west-1`
- **Usage**: Default AWS region for services

### **AWS_BUCKET**
- **Description**: S3 bucket name
- **Default**: Empty
- **Usage**: S3 bucket for file storage

## 📡 Pusher Configuration

### **PUSHER_APP_ID**
- **Description**: Pusher application ID
- **Default**: Empty
- **Usage**: Identifies your Pusher application

### **PUSHER_APP_KEY**
- **Description**: Pusher application key
- **Default**: Empty
- **Usage**: Public key for Pusher authentication

### **PUSHER_APP_SECRET**
- **Description**: Pusher application secret
- **Default**: Empty
- **Usage**: Secret key for server-side Pusher operations

### **PUSHER_APP_CLUSTER**
- **Description**: Pusher cluster
- **Default**: `mt1`
- **Example**: `us-east-1`, `eu-west-1`
- **Usage**: Geographic cluster for Pusher services

### **MIX_PUSHER_APP_KEY**
- **Description**: Pusher key for frontend
- **Default**: `"${PUSHER_APP_KEY}"`
- **Usage**: Makes Pusher key available to JavaScript

### **MIX_PUSHER_APP_CLUSTER**
- **Description**: Pusher cluster for frontend
- **Default**: `"${PUSHER_APP_CLUSTER}"`
- **Usage**: Makes Pusher cluster available to JavaScript

## 🔧 Configuration Examples

### **Development Environment**
```env
APP_NAME=Laravel API
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_api
DB_USERNAME=root
DB_PASSWORD=

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

REDIS_HOST=127.0.0.1
REDIS_PORT=6379
REDIS_CLIENT=predis

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
```

### **Production Environment**
```env
APP_NAME=My Production API
APP_ENV=production
APP_DEBUG=false
APP_URL=https://api.example.com

DB_CONNECTION=mysql
DB_HOST=db.example.com
DB_PORT=3306
DB_DATABASE=production_db
DB_USERNAME=prod_user
DB_PASSWORD=secure_password

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

REDIS_HOST=redis.example.com
REDIS_PORT=6379
REDIS_PASSWORD=redis_password
REDIS_CLIENT=phpredis

MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=noreply@example.com
MAIL_PASSWORD=email_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="My Production API"
```

## ⚠️ Security Considerations

### **Sensitive Variables**
These variables contain sensitive information and should be kept secure:
- `APP_KEY`
- `DB_PASSWORD`
- `REDIS_PASSWORD`
- `MAIL_PASSWORD`
- `AWS_ACCESS_KEY_ID`
- `AWS_SECRET_ACCESS_KEY`
- `PUSHER_APP_SECRET`

### **Environment-Specific Settings**
- **Development**: Use `APP_DEBUG=true` for detailed error messages
- **Production**: Use `APP_DEBUG=false` to hide sensitive information
- **Production**: Use strong passwords and secure connections

### **File Permissions**
```bash
# Secure .env file permissions
chmod 600 .env
```

## 🔄 Variable Usage in Code

### **Accessing Variables**
```php
// Get environment variable
$appName = env('APP_NAME', 'Laravel');

// Get with default value
$debugMode = env('APP_DEBUG', false);

// Type casting
$port = (int) env('DB_PORT', 3306);
```

### **Configuration Files**
Variables are typically accessed through Laravel's configuration system:
```php
// config/app.php
'name' => env('APP_NAME', 'Laravel'),
'debug' => env('APP_DEBUG', false),

// config/database.php
'host' => env('DB_HOST', '127.0.0.1'),
'port' => env('DB_PORT', '3306'),
```

## 🧪 Testing Configuration

### **Test Environment Variables**
```bash
# Check if variables are loaded
php artisan tinker
>>> config('app.name')
>>> config('database.connections.mysql.host')
>>> config('cache.default')
```

### **Validate Configuration**
```bash
# Check configuration
php artisan config:show

# Clear configuration cache
php artisan config:clear
```

## 📚 Additional Resources

- [Laravel Configuration](https://laravel.com/docs/configuration)
- [Environment Configuration](https://laravel.com/docs/configuration#environment-configuration)
- [Redis Configuration](https://laravel.com/docs/redis)
- [Mail Configuration](https://laravel.com/docs/mail)
- [Queue Configuration](https://laravel.com/docs/queues)

---

**Created**: September 5, 2025  
**Laravel Version**: 9.52.20  
**Last Updated**: September 5, 2025
