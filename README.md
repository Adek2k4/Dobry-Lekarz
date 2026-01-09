Dobry-Lekarz

git init
git pull https://github.com/Adek2k4/Dobry-Lekarz
composer require laravel/breeze --dev
npm install
npm run build
php artisan migrate:fresh --seed
php artisan serve
