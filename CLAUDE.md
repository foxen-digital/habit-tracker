# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Habit Tracker is a Laravel 12 application for personal health and habit tracking. It supports multi-user data isolation with authentication via Laravel Fortify.

## Common Commands

### Development
```bash
composer dev          # Start all services (server, queue, logs, vite)
php artisan serve     # Start PHP dev server only
npm run dev           # Start Vite for frontend assets
npm run build         # Build frontend assets for production
```

### Testing
```bash
php artisan test      # Run all tests
pest                  # Run tests directly via Pest
pest --filter=TestName  # Run specific test
```

### Code Style
```bash
./vendor/bin/pint     # Format code with Laravel Pint
./vendor/bin/pint --test  # Check formatting without modifying
```

### Database
```bash
php artisan migrate   # Run migrations
php artisan migrate:fresh --seed  # Reset database with seeders
```

## Architecture

### Entry Models (Multi-User)
All health tracking entries belong to a user and include a `user_id` foreign key:
- `WeightEntry` - Daily weight logs
- `WalkEntry` - Walking distance and steps
- `WaterEntry` - Daily water intake (glasses)
- `MoodEntry` - Daily mood with energy/sleep ratings
- `GlucoseEntry` - Blood glucose readings with meal context
- `DailyGoal` - Custom daily goals with completions
- `DailyGoalCompletion` - Pivot table tracking goal completion by date

Each entry model has a `scopeForUser($query, User $user)` scope for querying user-specific data.

### User Settings
`UserSettings` model stores per-user preferences:
- Weight/distance unit preferences (kg/lbs, miles/km)
- Goal targets (weight goal, daily walk target, water target)

Access via `$user->getSettings()` which creates defaults if missing.

### Controllers
- `DashboardController` - Single `__invoke` method rendering the main dashboard
- `EntryController` - Handles all health metric form submissions (weight, walk, water, mood, glucose)
- `DailyGoalController` - CRUD for daily goals plus toggle completion
- `SettingsController` - User preferences management

### Authentication
Laravel Fortify handles all auth routes. All application routes require `auth` and `verified` middleware. Two-factor authentication is supported.

### Frontend
Blade templates with Tailwind CSS 4. Views organized in `resources/views/`:
- `dashboard.blade.php` - Main dashboard
- `settings/index.blade.php` - User settings
- `auth/` - Fortify auth views

## Key Patterns

### User Data Isolation
All entry queries must filter by user:
```php
WeightEntry::forUser($user)->orderBy('date', 'desc')->get();
```

### Update or Create Pattern
Water and Mood entries use `updateOrCreate` to ensure one entry per user per day:
```php
WaterEntry::updateOrCreate(
    ['date' => $date, 'user_id' => $userId],
    ['glasses' => $glasses]
);
```

### Test Setup
Tests extend `Tests\TestCase` which uses `RefreshDatabase`. Factories exist for all models with proper user_id handling.

## CI/CD
GitHub Actions runs on push/PR to master/main:
1. PHPUnit tests via `php artisan test`
2. Laravel Pint code style check (`--test` mode)
3. `composer audit` security check
