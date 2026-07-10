📦 platform-use.pls

Rental & Booking Platform built with Laravel, Octane (FrankenPHP), Redis, Livewire, Docker.

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# Development commands

## Container management

```bash
docker exec -it rent_use_app bash
docker restart rent_use_app
docker compose up -d --build
```

## Laravel

```bash
docker exec -it rent_use_app php artisan migrate:fresh --seed
docker exec -it rent_use_app php artisan route:clear
docker exec -it rent_use_app php artisan cache:clear
```

## Octane / Reverb

```bash
docker exec -it rent_use_app php artisan octane:status
docker exec -it rent_use_app php artisan octane:reload
docker exec -it rent_use_app php artisan reverb:start
```

## Filament

```bash
docker exec -it rent_use_app php artisan make:filament-resource Listing --generate --view
docker exec -it rent_use_app php artisan make:filament-resource User --generate
```

## Admin access

```bash
# Recommended — safe, respects mass assignment protection
docker exec -it rent_use_app php artisan user:make-admin your@email.com

# Or via seeder (set ADMIN_EMAIL / ADMIN_PASSWORD in .env first)
docker exec -it rent_use_app php artisan db:seed --class=AdminSeeder
```

## Stripe (local webhook testing)

```bash
stripe listen --forward-to localhost/webhook/stripe
```
