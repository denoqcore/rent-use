📦 platform-use.pls

Rental & Booking Platform built with Laravel, Octane (FrankenPHP), Redis, Livewire, Docker.

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

Commands

docker exec -it rent_use_app
docker restart rent_use_app

php artisan migrate:fresh --seed

# Clear routes

docker exec -it rent_use_app php artisan route:clear

# Clear cache

docker exec -it rent_use_app php artisan cache:clear

# Restart container

docker restart rent_use_app

# Reload Octane

docker exec -it rent_use_app php artisan octane:reload

# Tinker admin

php artisan tinker
User::where('email', 'adminRentUse@gmail.com')->update(['is_admin' => true]);

# Reverb

docker exec -it rent_use_app php artisan reverb:start

# Octane

docker exec -it rent_use_app php artisan octane:status
docker exec -it rent_use_app php artisan octane:reload
