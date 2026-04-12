<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Negocio</title>
    <link rel="stylesheet" href="css/login_style.css"> </head>
<body>
    <div class="background-image"></div>
    <div class="login-card">
        <h2>Crear Cuenta</h2>
        <form action="api/procesar_registro.php" method="POST">
            <div class="input-group">
                <input type="text" name="nombre_negocio" placeholder="Nombre del Negocio" required>
            </div>
            <div class="input-group">
                <input type="text" name="usuario" placeholder="Usuario" required>
            </div>
            <div class="input-group">
                <input type="password" name="password" placeholder="Contraseña" required>
            </div>
            <button type="submit" class="login-btn">Registrarme</button>
        </form>
        <p class="register-text">¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></p>
    </div>
</body>
</html>