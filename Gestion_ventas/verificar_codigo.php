<?php $email = $_GET['email']; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Verificar Código - Almasur</title>
    <link rel="stylesheet" href="css/menu_style.css">
    <style>
        body { background: #0f172a; color: white; display: flex; justify-content: center; align-items: center; height: 100vh; font-family: sans-serif; }
        .card { background: #1e293b; padding: 30px; border-radius: 12px; border: 1px solid #4ade80; width: 350px; text-align: center; }
        input { width: 100%; padding: 10px; margin: 10px 0; border-radius: 5px; border: 1px solid #334155; background: #0f172a; color: white; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #4ade80; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; color: #064e3b; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Paso Final</h2>
        <p style="font-size: 0.9em; color: #94a3b8;">Introduce el código de 6 dígitos y tu nueva clave.</p>
        <form action="api/finalizar_recuperacion.php" method="POST">
            <input type="hidden" name="email" value="<?php echo $email; ?>">
            <input type="text" name="codigo" placeholder="Código de 6 dígitos" maxlength="6" required>
            <input type="password" name="nueva_pass" placeholder="Nueva Contraseña" required>
            <button type="submit">CAMBIAR CONTRASEÑA</button>
        </form>
    </div>
</body>
</html>