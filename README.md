📦 platform-use.pls

Rental & Booking Platform built with Laravel, Octane (FrankenPHP), Redis, Livewire, Docker.

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

Commands

docker exec -it rent_use_app

docker exec -it rent_use_app php artisan route:clear
docker exec -it rent_use_app php artisan cache:clear
docker restart rent_use_app

# Перезапускаем Octane, чтобы он перечитал .env

docker exec -it rent_use_app php artisan octane:reload
