<?php
// detail.php
require 'config.php'; // También hereda la gestión de errores de conexión

$cliente = null;
$errores = [];

// Recoger el parámetro id desde la URL
$id = $_GET['id'] ?? '';

/*
 * GESTIÓN DE ERRORES DEL PARÁMETRO 'id':
 * - Si 'id' no es numérico, lo consideramos no válido.
 *   Usamos ctype_digit para asegurar que son solo dígitos.
 */
if (!ctype_digit($id)) {
    $errores[] = 'Identificador no válido.';
} else {
    try {
        $sql = "SELECT ID, NAME, ADDRESS, DESCRIPTION, TELF, TYPE
                FROM TEST_CLIENTS
                WHERE ID = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        $cliente = $stmt->fetch();

        if (!$cliente) {
            /*
             * Caso en el que la consulta no devuelve filas:
             * - No es una excepción, pero sí un error desde el punto de vista del usuario,
             *   porque el ID no corresponde a ningún cliente.
             */
            $errores[] = 'No se ha encontrado un cliente con ese ID.';
        }
    } catch (PDOException $e) {
        /*
         * GESTIÓN DE ERRORES DE CONSULTA:
         * Este catch se ejecutará si hay un error al hacer el SELECT.
         * Casos:
         *  - Tabla TEST_CLIENTS no existe.
         *  - Problemas de conexión o permisos.
         */
        $errores[] = 'Error al obtener el detalle: ' . htmlspecialchars($e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle cliente</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .error { color: #b00020; margin-bottom: 10px; }
        dt { font-weight: bold; }
    </style>
</head>
<body>

<h1>Detalle de cliente</h1>

<!-- Mostrar errores, si los hay -->
<?php if (!empty($errores)): ?>
    <div class="error">
        <?php foreach ($errores as $error): ?>
            <div><?php echo htmlspecialchars($error); ?></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Mostrar el detalle solo si se ha encontrado un cliente -->
<?php if ($cliente): ?>
    <dl>
        <dt>ID</dt>
        <dd><?php echo (int)$cliente['ID']; ?></dd>

        <dt>Nombre</dt>
        <dd><?php echo htmlspecialchars($cliente['NAME']); ?></dd>

        <dt>Dirección</dt>
        <dd><?php echo htmlspecialchars($cliente['ADDRESS']); ?></dd>

        <dt>Descripción</dt>
        <dd><?php echo nl2br(htmlspecialchars($cliente['DESCRIPTION'])); ?></dd>

        <dt>Teléfono</dt>
        <dd><?php echo htmlspecialchars($cliente['TELF']); ?></dd>

        <dt>Tipo</dt>
        <dd>
            <?php
            if ($cliente['TYPE'] === 'P') {
                echo 'Premium';
            } elseif ($cliente['TYPE'] === 'N') {
                echo 'Normal';
            } else {
                echo 'Desconocido (' . htmlspecialchars($cliente['TYPE']) . ')';
            }
            ?>
        </dd>
    </dl>
<?php endif; ?>

<p><a href="index.php">Volver al listado</a></p>

</body>
</html>
