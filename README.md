<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## INSTALACIÓN

```bash
composer create-project laravel/laravel nombre-proyecto
```

## GUIA PARA CREAR CRUD BASICO

###### 1) Crear `Modelo `con  todo lo básico necesario

```bash
php artisan make:model [NombreTablaEnSingular] -msc -r
```

* la "m" significa que creará el archivo para migraciones
* la "s" creará el archivo para seed (no es necesario en todos pero no hace daño tenerlo)
* la "c" creara el controlador
* la "-r" creara las funciones básicas para un CRUD en el controlador

###### 2) Editar archivo `Migración`

*recordemos que la migración es donde declaramos la estructura de la tabla, campos, tipos de dato, y cosas de la BD*

###### 3) Editar archivo `Seed`

*Les recomiendo que si van a agregar seed, lo hagan en el archivo llamado DatabaseSeed.php*

###### 4) Editar archivo `Modelo`

*el modelo es un intermediario entre la tabla y lo que haremos en código, es lo que se dice "mapear", es decir, poder interpretar los datos de una tabla a través de código (aquí declaramos las relaciones e información com, cual es nuestra llave primaria, cuales las foránea, cual es la relación entre cada tabla, datos default, etc., ahi tengo comentarios)*

*Incluso podemos definir que propiedades podemos pedir y cuales ocultar.*

###### 5) Editar archivo `Controller`

*Aquí básicamente es hacer las consultas que necesitaremos, por el momento hagamos el crud para entender el código sintaxis y funciones, después nos ponemos más creativos con lo que necesitamos.
Ahi deje algunos comentarios como guía.*

###### 6) Editar archivo `routes/api.php`

*Aquí copien y peguen un bloque de los que tengo ya creado y solo le cambian el parámetro donde se le declara el nombre de la ruta en plural y la clase del controlador, es decir ("/[users…]",[Cambiar controlador])*

## MIGRACIONES Y SEMILLAS

###### Migracíon y Seedeers

```bash
php artisan migrate 
php artisan db:seed
```

###### Otras opciones

* **Ejecutar una migración especifica**

  ```bash
  php artisan migrate
  ```
* **Conexión especifica por nombreDeConexion (el que declarste en `/config/database.php`)**

  ```bash
  php artisan migrate --database=nombre_de_la_conexion
  ```
* **Conexión especifica por `path`**

  ```bash
  php artisan migrate --path=database/migrations/nueva_carpeta
  ```
* **Migración y Conexión específica**

```bash
php artisan migrate:refresh --path=database/migrations/becas --database=mysql_becas
```

* **Migracíon específica**

```bash
php artisan db:seed --class=DatabaseSeeder
```

## CONFIGURACIONES

###### Region `/config/app.php`

Cambiar zona horaria y localizacion a Mexico

```php
#| Application Timezone
'timezone' => 'America/Monterrey',

#| Application Locale Configuration
'locale' => 'es',
```

###### CORS `/config/cors.php`

Para permitir el correcto paso de peticiones. Asegurarse de tener estas propiedades con estos valores

```php
'paths' => ['api/*', 'sanctum/csrf-cookie'],
...
'allowed_origins' => ['*'],
```

###### Limite de peticiones `/app/Providers/RouteServiceProvider.php`

Si deseas ampliar el limite de peticiones por minuto.  samuel

```php
RateLimiter::for('api', function (Request $request) {
    return Limit::perMinute(100)->by($request->user()?->id ?: $request->ip());
});
```
