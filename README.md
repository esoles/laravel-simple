1.- buscar el file .env.example
2.- crear un file .env y pegar todo lo de  .env.example
3.- si ustedes tienen base datos mysql modificar
    DB_CONNECTION=sqlite
    # DB_HOST=127.0.0.1
    # DB_PORT=3306
    # DB_DATABASE=laravel
    # DB_USERNAME=root
    # DB_PASSWORD=
4.- ingresan a su proyecto desde el terminar y ejecutan -> composer install
5.- terminado la instacion en terminal ejecutan -> php artisan key:generate
6.- migro mis tablas -> php artisan migrate



/* *********************************** */
crer migracion
php artisan make:migration create_products_table
crear model
php artisan make:model  Product
crear controller 
 php artisan make:controller ProductController



extra : php artisan install:api


Tarea : crear tabla que se relacione solo categoria puede ser 
items 
servicios 
...