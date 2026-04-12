<?php
include '../config/db.php';

$email = trim($_POST['email']);
$codigo_ingresado = trim($_POST['codigo']);
$pass_nueva = password_hash($_POST['nueva_pass'], PASSWORD_DEFAULT);

$sql = "SELECT id FROM usuarios WHERE 
        email = '$email' AND 
        codigo_verificacion = '$codigo_ingresado' AND 
        codigo_expira > NOW()";

$res = $conn->query($sql);

if ($res && $res->num_rows > 0) {
    $conn->query("UPDATE usuarios SET 
        password = '$pass_nueva', 
        codigo_recuperacion = NULL, 
        codigo_verificacion = NULL, 
        codigo_expira = NULL 
        WHERE email = '$email'");
        
    echo "<script>alert('¡Contraseña actualizada exitosamente!'); window.location.href='../login.php';</script>";
} else {
    $verificar = $conn->query("SELECT codigo_expira, codigo_verificacion FROM usuarios WHERE email = '$email'");
    $fila = $verificar->fetch_assoc();
    
    if (!$fila) {
        $msg = "El correo no existe en nuestro sistema.";
    } else {
        $msg = "El código es incorrecto o ya expiró. Por favor, solicita uno nuevo.";
    }
    
    echo "<script>alert('$msg'); window.history.back();</script>";
}
?>