<?php
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $codigo = $_POST['codigo'];
    $nueva_pass = password_hash($_POST['nueva_password'], PASSWORD_DEFAULT); 
    $check = $conn->query("SELECT id FROM usuarios WHERE email = '$email' AND codigo_recuperacion = '$codigo'");

    if ($check->num_rows > 0) {
        $update = $conn->query("UPDATE usuarios SET password = '$nueva_pass' WHERE email = '$email'");
        
        if ($update) {
            echo "<script>alert('Contraseña actualizada con éxito'); window.location.href='../login.php';</script>";
        }
    } else {
        echo "<script>alert('Datos incorrectos. Verifica tu correo y código.'); window.history.back();</script>";
    }
}
?>