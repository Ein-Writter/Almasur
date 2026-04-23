<?php
include '../config/db.php';
date_default_timezone_set('America/Mexico_City'); 

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $codigo = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    
    $conn->query("UPDATE usuarios SET codigo_verificacion = '$codigo', 
                  codigo_expira = DATE_ADD(NOW(), INTERVAL 15 MINUTE) WHERE email = '$email'");

    echo "<script>
            alert('TU CÓDIGO ES: $codigo');
            window.location.href='../verificar_codigo.php?email=$email';
          </script>";
}
?>