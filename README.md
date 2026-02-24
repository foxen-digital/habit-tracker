# Habit Tracker

A personal health and habit tracking application built with Laravel. Track your weight, walking, water intake, mood, blood glucose, and daily goals all in one place.

## Features

### Health Metrics

- **Weight Tracking** - Log your daily weight with progress visualization toward your goal
- **Walking Log** - Track daily walking distance with target goals
- **Water Intake** - Monitor daily water consumption by glasses
- **Mood Tracking** - Record your mood with emoji-based entries and optional notes
- **Blood Glucose** - Log glucose readings with meal context (fasting, before/after meals)

### Daily Goals

- Create custom daily goals (e.g., "Hit Calorie Target", "Brush Teeth", "Stretch Routine")
- Check off goals each day with emoji indicators
- View weekly completion statistics

### Dashboard

- Unified dashboard showing all metrics at a glance
- Progress charts for weight loss journey
- Weekly statistics for daily goals
- Quick entry forms for all metrics

### User Features

- Secure authentication (Laravel Fortify)
- Two-factor authentication support
- Personalized settings (weight unit, distance unit, targets)
- Multi-user support with data isolation

## Tech Stack

- **Framework:** Laravel 12
- **PHP Version:** 8.4+
- **Frontend:** Blade templates with Tailwind CSS 4
- **Authentication:** Laravel Fortify
- **Testing:** Pest PHP
- **Code Style:** Laravel Pint

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/foxen-digital/habit-tracker.git
   cd habit-tracker
   ```

2. Install dependencies:
   ```bash
   composer install
   npm install
   ```

3. Set up environment:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Configure your database in `.env`, then run migrations:
   ```bash
   php artisan migrate
   ```

5. Build frontend assets:
   ```bash
   npm run build
   ```

6. Serve the application:
   ```bash
   php artisan serve
   ```

Or use the convenience command:
```bash
composer dev
```

## Testing

Run the test suite with Pest:

```bash
php artisan test
# or
pest
```

## Code Style

Format code with Laravel Pint:

```bash
./vendor/bin/pint
```

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

Built by [Foxen Digital](https://foxendigital.co.uk)
