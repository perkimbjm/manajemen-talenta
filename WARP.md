# WARP.md

This file provides guidance to WARP (warp.dev) when working with code in this repository.

## Commonly Used Commands

- **Install dependencies**: `composer install && bun install`
- **Start development server**: `bun run start` (This will run `vite`, `php artisan serve`, `php artisan horizon`, and `php artisan reverb:start` concurrently)
- **Run tests**: `bun test` (This will run `./vendor/bin/pest`)
- **Run a single test**: `./vendor/bin/pest --filter <test_name>`
- **Lint files**: `./vendor/bin/pint`

## Code Architecture

This is a Laravel project that heavily utilizes the TALL stack (Tailwind CSS, Alpine.js, Laravel, and Livewire).

- **Backend**: The application logic is built with Laravel. It uses `spatie/laravel-permission` for roles and permissions and `spatie/laravel-medialibrary` for media management. Background jobs are managed with Laravel Horizon. Real-time features are powered by Laravel Reverb.
- **Frontend**: The frontend is built with Blade templates, with interactivity provided by Livewire and Alpine.js. Styling is done with Tailwind CSS and DaisyUI. Vite is used for asset bundling.
- **Livewire**: The project makes extensive use of Livewire for building dynamic interfaces. PowerGrid is used for creating powerful tables. Volt is used for single-file Livewire components.
- **Database**: The project uses Laravel's Eloquent ORM. Migrations are used to manage the database schema.
