composer require laravel/passport
php artisan make:seeder UserSeeder

composer require spatie/laravel-permission

php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"

composer require ahmedsaoud31/laravel-permission-to-vuejs=dev-master