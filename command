composer require laravel/passport

php artisan make:seeder UserSeeder

composer require spatie/laravel-permission

php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"

composer require ahmedsaoud31/laravel-permission-to-vuejs=dev-master

php artisan make:seeder RolesAndPermissionsSeeder

php artisan db:seed --class=RolesAndPermissionsSeeder

php artisan passport:client --personal

php artisan vendor:publish --tag=passport-routes