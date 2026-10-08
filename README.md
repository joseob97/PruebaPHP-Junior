# Prueba técnica · PHP Junior

Resolución de una prueba técnica para un puesto de desarrollador PHP junior: una pequeña aplicación de gestión de clientes en **PHP puro** con **MySQL**, sin frameworks.

## Qué incluye

| Archivo | Contenido |
|---|---|
| [`database.sql`](database.sql) | Script que crea la base de datos, el usuario y la tabla `TEST_CLIENTS`. |
| [`config.php`](config.php) | Conexión con **PDO** y gestión de errores de conexión. |
| [`index.php`](index.php) | Listado de clientes con los **Premium** destacados y formulario de alta con **validación en servidor y en cliente** (JavaScript). |
| [`detail.php`](detail.php) | Ficha de detalle de un cliente, con validación del parámetro `id` y control de errores. |
| [`figura.php`](figura.php) | Ejercicio de lógica: dibuja un rombo de `#` con bucles. |

## Puntos a destacar

- Consultas con **PDO y sentencias preparadas**.
- Validación de datos en servidor y en cliente.
- Manejo de errores en la conexión, en los parámetros de entrada y en las consultas.
- Código comentado explicando cada decisión.

## Ejecución local

```bash
mysql -u root -p < database.sql
php -S localhost:8000
```

Abre `http://localhost:8000`. El rombo se ejecuta por consola con `php figura.php`.
