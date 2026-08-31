# EduBridge AI Agent Instructions

## Project type
- Laravel 12 application
- PHP ^8.2
- Frontend uses Vite, Tailwind CSS, Alpine.js, and Blade views
- Tests use Pest with Laravel support

## Key files and directories
- `app/Http/Controllers/` — controller logic for web requests
- `app/Models/` — Eloquent models and relationships
- `routes/web.php`, `routes/auth.php` — application routes
- `resources/views/` — Blade templates and UI views
- `resources/js/` — frontend scripts and Alpine.js behavior
- `resources/css/` — Tailwind styles
- `tests/Feature/`, `tests/Unit/` — automated test coverage
- `composer.json`, `package.json` — dependency and script declarations
- `vite.config.js`, `tailwind.config.js` — build configuration

## Setup and validation commands
- `composer install`
- `cp .env.example .env`
- `php artisan key:generate`
- `php artisan migrate --force`
- `npm install`
- `npm run build`
- `php artisan test`
- `npm run dev` for local frontend development

## Development guidance for the AI agent
- Follow Laravel conventions and PSR-12-style PHP code
- Prefer controllers, models, requests, and Blade templates over editing vendor or public build output
- Use Eloquent relationships when adding or modifying data access
- Keep frontend work inside `resources/` and rely on Vite asset compilation
- Preserve existing routes and middleware unless a change is required
- Don't assume environment variables are present; only add `.env` entries when necessary and update documentation accordingly

## Testing and quality
- Validate changes with `php artisan test`
- Use `composer test` when available in composer scripts
- Run `npm run build` if frontend assets change

## References
- Local README: `README.md`
- Laravel docs: https://laravel.com/docs
- Pest docs: https://pestphp.com/docs
