<?php
include '../config/db.php';
$id_u = $_SESSION['usuario_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $negocio = $_POST['nombre_negocio'];
    $user = $_POST['usuario'];
    $pass = $_POST['password'];

    $sql = "INSERT INTO usuarios (usuario, password, nombre_negocio) 
            VALUES ('$user', '$pass', '$negocio')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('Registro exitoso. Ahora puedes iniciar sesión.');
                window.location.href='../login.php';
              </script>";
    } else {
        echo "Error al registrar: " . $conn->error;
    }
}
?>