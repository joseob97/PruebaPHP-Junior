<?php
// index.php
require 'config.php'; // Aquí ya hay gestión de errores por si falla la conexión (en config.php)

// Array para recoger mensajes de error
$errores = [];
// Mensaje de éxito para informar al usuario
$mensajeExito = '';

// --------- GESTIÓN DEL FORMULARIO (INSERTAR REGISTRO) - EJERCICIOS 2 y 6 ---------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger datos del formulario (usamos el operador null coalescing ?? para evitar warnings)
    $name        = trim($_POST['name'] ?? '');
    $address     = trim($_POST['address'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $telf        = trim($_POST['telf'] ?? '');
    $type        = trim($_POST['type'] ?? '');

    /*
     * GESTIÓN DE ERRORES DE VALIDACIÓN (SERVIDOR):
     * Estos errores saltan ANTES de intentar insertar en la BD.
     * Casos:
     *  - Algún campo está vacío.
     *  - El teléfono no es numérico.
     *  - El tipo no es N ni P.
     */
    if ($name === '' || $address === '' || $description === '' || $telf === '' || $type === '') {
        $errores[] = 'Todos los campos son obligatorios.';
    }

    if ($telf !== '' && !ctype_digit($telf)) {
        // ctype_digit comprueba que todos los caracteres son dígitos
        $errores[] = 'El teléfono debe ser numérico.';
    }

    if ($type !== '' && !in_array($type, ['N', 'P'], true)) {
        $errores[] = 'El tipo debe ser N (Normal) o P (Premium).';
    }

    // Si no hay errores de validación, intentamos insertar en la BD
    if (empty($errores)) {
        try {
            $sql = "INSERT INTO TEST_CLIENTS (NAME, ADDRESS, DESCRIPTION, TELF, TYPE)
                    VALUES (:name, :address, :description, :telf, :type)";

            $stmt = $pdo->prepare($sql);

            // Ejecutamos la consulta preparada
            $stmt->execute([
                ':name'        => $name,
                ':address'     => $address,
                ':description' => $description,
                ':telf'        => $telf,
                ':type'        => $type,
            ]);

            // Si llega aquí, el insert ha ido bien
            $mensajeExito = 'Cliente insertado correctamente.';
        } catch (PDOException $e) {
            /*
             * GESTIÓN DE ERRORES DE INSERCIÓN (BD):
             * Este catch se ejecutará si ocurre un error al hacer el INSERT, por ejemplo:
             *  - Se viola una restricción NOT NULL (intentando guardar NULL).
             *  - Problemas de conexión momentánea con la BD.
             *  - Error de sintaxis en la consulta (aunque aquí no debería).
             */
            $errores[] = 'Error al insertar el registro: ' . htmlspecialchars($e->getMessage());
        }
    }
}

// --------- OBTENER LISTADO DE REGISTROS - EJERCICIOS 1, 3 y 6 ---------
$clientes = [];

try {
    // Incluimos TYPE porque lo usaremos para marcar los Premium
    $sql = "SELECT ID, NAME, ADDRESS, TELF, TYPE FROM TEST_CLIENTS";
    $stmt = $pdo->query($sql);
    $clientes = $stmt->fetchAll();
} catch (PDOException $e) {
    /*
     * GESTIÓN DE ERRORES EN LA CONSULTA DE LISTADO:
     * Este catch se ejecutará si ocurre un error al ejecutar el SELECT, por ejemplo:
     *  - La tabla TEST_CLIENTS no existe.
     *  - Problema de conexión con la BD.
     *  - Falta de permisos en la tabla.
     */
    $errores[] = 'Error al obtener el listado de clientes: ' . htmlspecialchars($e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Prueba PHP Junior - Listado de clientes</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }

        .error { color: #b00020; margin-bottom: 10px; }
        .exito { color: #006400; margin-bottom: 10px; }

        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; }

        th { background-color: #f2f2f2; }

        /* Destacar clientes Premium (Ejercicio 3) */
        .premium {
            background-color: #fff4bf;
            font-weight: bold;
        }

        .badge-premium {
            background-color: #ffcc00;
            color: #000;
            padding: 3px 6px;
            border-radius: 4px;
            font-size: 0.8rem;
            margin-left: 5px;
        }
    </style>
</head>
<body>

<h1>Gestión de clientes (TEST_CLIENTS)</h1>

<!-- BLOQUE DE ERRORES (VISUALIZACIÓN) -->
<?php if (!empty($errores)): ?>
    <div class="error">
        <?php foreach ($errores as $error): ?>
            <!--
                Cada elemento de $errores es un mensaje generado en:
                - Validación del formulario
                - Errores de BD (INSERT / SELECT)
            -->
            <div><?php echo htmlspecialchars($error); ?></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- MENSAJE DE ÉXITO (VISUALIZACIÓN) -->
<?php if ($mensajeExito !== ''): ?>
    <div class="exito">
        <?php echo htmlspecialchars($mensajeExito); ?>
    </div>
<?php endif; ?>

<!-- FORMULARIO DE INSERCIÓN - EJERCICIO 2 + 4 -->
<h2>Nuevo cliente</h2>
<form id="formCliente" method="post" action="index.php">
    <div>
        <label for="name">Nombre:</label><br>
        <input type="text" id="name" name="name">
    </div>

    <div>
        <label for="address">Dirección:</label><br>
        <input type="text" id="address" name="address">
    </div>

    <div>
        <label for="description">Descripción:</label><br>
        <textarea id="description" name="description"></textarea>
    </div>

    <div>
        <label for="telf">Teléfono:</label><br>
        <input type="text" id="telf" name="telf">
    </div>

    <div>
        <label for="type">Tipo (N = Normal, P = Premium):</label><br>
        <input type="text" id="type" name="type" maxlength="1">
    </div>

    <br>
    <button type="submit">Guardar</button>
</form>

<!-- LISTADO DE CLIENTES - EJERCICIO 1 y 3 -->
<h2>Listado de clientes</h2>

<?php if (empty($clientes)): ?>
    <!-- Caso sin datos: no es un "error", simplemente informamos -->
    <p>No hay clientes registrados.</p>
<?php else: ?>
    <table>
        <thead>
        <tr>
            <th>Nombre</th>
            <th>Dirección</th>
            <th>Teléfono</th>
            <th>Premium</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($clientes as $cliente): ?>
            <?php
            // Marcamos la fila como premium si TYPE = 'P'
            $esPremium = ($cliente['TYPE'] === 'P');
            ?>
            <tr class="<?php echo $esPremium ? 'premium' : ''; ?>">
                <td>
                    <!-- Enlazamos al detalle - Ejercicio 5 -->
                    <a href="detail.php?id=<?php echo (int)$cliente['ID']; ?>">
                        <?php echo htmlspecialchars($cliente['NAME']); ?>
                    </a>
                    <?php if ($esPremium): ?>
                        <span class="badge-premium">Premium</span>
                    <?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars($cliente['ADDRESS']); ?></td>
                <td><?php echo htmlspecialchars($cliente['TELF']); ?></td>
                <td><?php echo $esPremium ? 'Sí' : 'No'; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<!-- VALIDACIÓN JAVASCRIPT - EJERCICIO 4 -->
<script>
    /*
     * VALIDACIÓN EN CLIENTE (JavaScript):
     * No sustituye a la validación en servidor, pero:
     * - Evita enviar formularios con campos vacíos.
     * - Evita teléfonos no numéricos.
     * - Avisa si el tipo no es N o P.
     *
     * Si hay errores, muestra un alert y no se envía el formulario.
     */
    document.getElementById('formCliente').addEventListener('submit', function (e) {
        const name = document.getElementById('name').value.trim();
        const address = document.getElementById('address').value.trim();
        const description = document.getElementById('description').value.trim();
        const telf = document.getElementById('telf').value.trim();
        let type = document.getElementById('type').value.trim().toUpperCase();

        let errores = [];

        if (name === '' || address === '' || description === '' || telf === '' || type === '') {
            errores.push('Todos los campos son obligatorios.');
        }

        if (telf !== '' && isNaN(telf)) {
            errores.push('El teléfono debe ser numérico.');
        }

        if (type !== '' && type !== 'N' && type !== 'P') {
            errores.push('El tipo debe ser N (Normal) o P (Premium).');
        }

        if (errores.length > 0) {
            // Si hay errores, bloqueamos el envío del formulario
            e.preventDefault();
            alert(errores.join('\n'));
        } else {
            // Normalizamos el tipo a mayúscula antes de enviar
            document.getElementById('type').value = type;
        }
    });
</script>

</body>
</html>
