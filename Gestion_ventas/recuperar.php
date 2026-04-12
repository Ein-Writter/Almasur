<?php include 'config/db.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Contraseña - Almasur</title>
    <link rel="stylesheet" href="css/menu_style.css">
    <style>
        body { background: #0f172a; color: white; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; font-family: sans-serif; }
        .card { background: #1e293b; padding: 40px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.5); width: 350px; text-align: center; border: 1px solid #38bdf8; }
        input { width: 100%; padding: 12px; margin: 10px 0; border-radius: 8px; border: 1px solid #334155; background: #0f172a; color: white; box-sizing: border-box; }
        button { width: 100%; padding: 12px; border-radius: 8px; border: none; background: #38bdf8; color: #0f172a; font-weight: bold; cursor: pointer; margin-top: 10px; }
        .volver { display: block; margin-top: 20px; color: #94a3b8; text-decoration: none; font-size: 0.9em; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Recuperar Acceso</h2>
        <p style="color: #94a3b8; font-size: 0.9em;">Ingresa tu correo y tu código de seguridad para cambiar la contraseña.</p>
        
        <form action="api/procesar_recuperacion.php" method="POST">
            <input type="email" name="email" placeholder="Correo electrónico" required>
            <input type="text" name="codigo" placeholder="Código de recuperación" required>
            <input type="password" name="nueva_password" placeholder="Nueva Contraseña" required>
            <button type="submit">ACTUALIZAR CONTRASEÑA</button>
        </form>
        
        <a href="login.php" class="volver">← Volver al Login</a>
    </div>
</body>
</html>