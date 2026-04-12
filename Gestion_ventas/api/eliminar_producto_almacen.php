<?php
include '../config/db.php';
$id_u = $_SESSION['usuario_id'];

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM productos WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../index.php");
    } else {
        echo "Error al eliminar: " . $conn->error;
    }
}
?>