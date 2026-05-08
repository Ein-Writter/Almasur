<?php
// 1. Iniciar sesión PRIMERO que cualquier otra cosa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Incluir la conexión a la base de datos
include 'config/db.php'; 

// 3. Verificar si el usuario está logueado y es admin
// IMPORTANTE: Asegúrate de que en tu login guardas el rol como 'admin' (en minúsculas)
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'Administrador') {
    echo "ID Usuario: " . ($_SESSION['usuario_id'] ?? 'No definido') . "<br>";
    echo "Rol: " . ($_SESSION['usuario_rol'] ?? 'No definido') . "<br>";
    die("Acceso denegado: No tienes permisos de administrador.");
}

// 4. Si pasa la validación, hacer la consulta
$sql = "SELECT codigo, nombre, stock FROM productos WHERE stock <= 5 ORDER BY stock ASC";
$res = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Stock Bajo</title>
    <style>
        body { font-family: sans-serif; color: #333; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background: #f4f4f4; }
        .header { text-align: center; margin-bottom: 30px; }
        .fecha { font-size: 0.9rem; color: #666; }
        @media print {
            .btn-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <h1>Reporte de Reposición de Inventario</h1>
        <p class="fecha">Generado el: <?php echo date('d/m/Y H:i'); ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Producto</th>
                <th>Stock Actual</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $res->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['codigo']; ?></td>
                <td><?php echo $row['nombre']; ?></td>
                <td style="color: red; font-weight: bold;"><?php echo $row['stock']; ?></td>
                <td>Agotándose</td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <p style="margin-top: 30px; font-size: 0.8rem; text-align: center;">--- Fin del Reporte ---</p>

</body>
</html>