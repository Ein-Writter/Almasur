<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Acceso - Almasur</title>
    <link rel="stylesheet" href="css/menu_style.css">
    <style>
        body { background: #0f172a; color: white; display: flex; justify-content: center; align-items: center; height: 100vh; font-family: sans-serif; }
        .card { background: #1e293b; padding: 30px; border-radius: 12px; border: 1px solid #38bdf8; width: 350px; text-align: center; }
        input { width: 100%; padding: 10px; margin: 10px 0; border-radius: 5px; border: 1px solid #334155; background: #0f172a; color: white; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #38bdf8; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Recuperar Contraseña</h2>
        <p style="font-size: 0.9em; color: #94a3b8;">Ingresa tu correo para recibir un código de simulación.</p>
        <form action="api/enviar_codigo_simulado.php" method="POST">
            <input type="email" name="email" placeholder="Correo electrónico" required>
            <button type="submit">ENVIAR CÓDIGO</button>
        </form>
        <a href="login.php" style="color:#38bdf8; text-decoration:none; display:block; margin-top:15px; font-size:0.8em;">Volver al Login</a>
    </div>
</body>
</html>