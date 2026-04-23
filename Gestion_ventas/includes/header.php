<?php
// Iniciamos sesión y conexión si no están iniciadas
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once 'config/db.php';

// Verificación de seguridad global
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="css/style_maestro.css">
    
    <title>Almasur - Gestión de Ventas</title>
</head>
<body>