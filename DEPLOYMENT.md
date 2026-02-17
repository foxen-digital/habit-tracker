# Habit Tracker Deployment Guide

This document describes the CI/CD pipeline and deployment process for the Habit Tracker application.

## CI/CD Overview

The Habit Tracker uses GitHub Actions for continuous integration and deployment. The pipeline is triggered on every push to the `master`/`main` branch and on all pull requests.

## Pipeline Stages

### 1. Test Suite
- **Trigger:** All pushes and PRs
- **Environment:** Ubuntu with PHP 8.3
- **Steps:**
  1. Checkout code
  2. Setup PHP with required extensions
  3. Copy environment configuration
  4. Create SQLite test database
  5. Install Composer dependencies
  6. Generate application key
  7. Run database migrations
  8. Execute PHPUnit tests

### 2. Code Style (Pint)
- **Trigger:** All pushes and PRs
- **Purpose:** Enforce Laravel coding standards
- **Tool:** Laravel Pint
- **Fails:** If any file needs formatting

### 3. Security Check
- **Trigger:** All pushes and PRs
- **Purpose:** Check for known vulnerabilities in dependencies
- **Tool:** `composer audit`

## Required Secrets

To enable deployment, add these secrets to your GitHub repository settings:

| Secret | Description |
|--------|-------------|
| `DEPLOY_HOST` | Server hostname or IP |
| `DEPLOY_USER` | SSH user for deployment |
| `DEPLOY_KEY` | Private SSH key for server access |
| `DEPLOY_PATH` | Path to application on server |

## Deployment Process

### Automatic Deployment (Future)
After merging to `master`:
1. CI tests must pass
2. Code style must pass
3. Security audit must pass
4. Deployment is triggered automatically

### Manual Deployment
```bash
# SSH into server
ssh user@your-server

# Navigate to application
cd /var/www/habit-tracker

# Pull latest changes
git pull origin master

# Install/update dependencies
composer install --no-dev --optimize-autoloader

# Run migrations
php artisan migrate --force

# Clear and cache config
php artisan config:clear
php artisan config:cache

# Clear and cache routes
php artisan route:clear
php artisan route:cache

# Clear and cache views
php artisan view:clear

# Restart queue workers (if any)
php artisan queue:restart

# Reload PHP-FPM
sudo systemctl reload php8.3-fpm
```

## Environment Requirements

### Server Requirements
- PHP 8.3+
- SQLite or MySQL/MariaDB
- Composer
- Git

### PHP Extensions
- mbstring
- xml
- ctype
- iconv
- intl
- pdo_sqlite (or pdo_mysql)

## Branch Strategy

- `master`/`main` - Production-ready code
- Feature branches - New development
- PRs required for merging to master

## Monitoring

After deployment, verify:
- [ ] Application loads in browser
- [ ] Database migrations ran successfully
- [ ] No errors in Laravel logs (`storage/logs/laravel.log`)

## Rollback

If deployment causes issues:

```bash
# Quick rollback to previous commit
git reset --hard HEAD~1

# Re-run deployment steps
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
sudo systemctl reload php8.3-fpm
```

## Support

For issues with deployment:
1. Check GitHub Actions logs
2. Check Laravel logs on server
3. Verify all secrets are correctly set
4. Ensure server meets requirements
