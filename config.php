<?php
// config.php
// Archivo de configuración y conexión a la base de datos mediante PDO

$host = 'localhost';
$db   = 'pruebas_practicas';
$user = 'pruebajr';
$pass = 'pruebajr';
$charset = 'utf8mb4';

// DSN de conexión a MySQL con PDO
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Opciones de PDO
$options = [
    // GESTIÓN DE ERRORES:
    // PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    // Hace que cualquier error de BD lance una excepción PDOException
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,

    // Devuelve los resultados como arrays asociativos
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,

    // Usar sentencias preparadas nativas del driver
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // Intentamos crear el objeto PDO (conexión)
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    /*
     * GESTIÓN DE ERRORES DE CONEXIÓN:
     * - Este bloque se ejecutará si falla la conexión a la BD.
     *   Por ejemplo:
     *   - Usuario/contraseña incorrectos.
     *   - BD 'pruebas_practicas' no existe.
     *   - Servidor MySQL parado.
     *
     * Usamos die() para detener la ejecución y mostrar un mensaje
     * controlado en lugar de un error feo de PHP.
     */
    die("Error de conexión a la base de datos: " . htmlspecialchars($e->getMessage()));
}
