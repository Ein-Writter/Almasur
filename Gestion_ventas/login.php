<?php
session_start();
include 'config/db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $usuario_input = trim($_POST['usuario']); 
    $password_escrita = $_POST['password'];

    // CORRECCIÓN: Agregamos la coma entre 'rol' y 'foto_perfil'
    $stmt = $conn->prepare("SELECT id, nombre, password, rol, foto_perfil FROM usuarios WHERE usuario = ?");
    $stmt->bind_param("s", $usuario_input); 
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        
        if (password_verify($password_escrita, $usuario['password'])) {
            session_regenerate_id(); 

            // Guardamos todo con los nombres exactos que espera el sidebar
            $_SESSION['usuario_id']   = $usuario['id'];
            $_SESSION['nombre']       = $usuario['nombre'];
            $_SESSION['usuario_rol']  = $usuario['rol'];
            $_SESSION['usuario_foto'] = $usuario['foto_perfil'];
        
            $id_u = $usuario['id'];
            $conn->query("INSERT INTO logs_acceso (id_usuario) VALUES ($id_u)");

            header("Location: menu.php");
            exit();
        }
        else {
            echo "<script>alert('Contraseña incorrecta'); window.location.href='login.php';</script>";
        }
    } else {
        echo "<script>alert('El nombre de usuario no existe'); window.location.href='login.php';</script>";
    }
    
    $stmt->close();
}
?>
<style>

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        outline: none !important; 
        background-image: none;   
    }

body {
    margin: 0;
    padding: 0;
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Segoe UI', sans-serif;
    
    background: 
        linear-gradient(rgba(11, 14, 20, 0.8), rgba(11, 14, 20, 0.8)),
        url('uploads/background.png') no-repeat center center;
    background-size: cover;
    
    background-attachment: fixed;
}
    .login-card {
        background: rgba(30, 41, 59, 0.7);
        backdrop-filter: blur(10px); 
        border: 1px solid rgba(255, 255, 255, 0.1);
        padding: 40px;
        border-radius: 16px;
        width: 100%;
        max-width: 400px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5);
        text-align: center;
    }

    .login-card h2 {
        margin-bottom: 10px;
        font-size: 1.8rem;
        color: #38bdf8;
    }

    .login-card p {
        color: #94a3b8;
        margin-bottom: 30px;
        font-size: 0.9rem;
    }

    .form-group {
        text-align: left;
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-size: 0.85rem;
        margin-bottom: 8px;
        color: #cbd5e1;
    }

    .input-login {
        width: 100%;
        padding: 12px 15px;
        background: #0f172a;
        border: 1px solid #334155;
        border-radius: 8px;
        color: white;
        transition: all 0.3s ease;
    }

    .input-login:focus {
        border-color: #38bdf8;
        box-shadow: 0 0 0 2px rgba(56, 189, 248, 0.2);
    }

    .btn-login {
        width: 100%;
        padding: 12px;
        background: #38bdf8;
        color: #0f172a;
        border: none;
        border-radius: 8px;
        font-weight: bold;
        font-size: 1rem;
        cursor: pointer;
        transition: 0.3s;
        margin-top: 10px;
    }

    .btn-login:hover {
        background: #7dd3fc;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(56, 189, 248, 0.3);
    }
</style>

<div class="login-card">
    <div style="font-size: 3rem; margin-bottom: 15px;">🚀</div>
    <h2>Bienvenido</h2>
    <p>Ingresa tus credenciales para continuar</p>
    
    <form action="login.php" method="POST">
        <div class="form-group">
            <label>Usuario</label>
            <input type="text" name="usuario" class="input-login" placeholder="Tu usuario" required>
        </div>
        
        <div class="form-group">
            <label>Contraseña</label>
            <input type="password" name="password" class="input-login" placeholder="••••••••" required>
        </div>
        
        <button type="submit" class="btn-login">Iniciar Sesión</button>
    </form>
</div>
