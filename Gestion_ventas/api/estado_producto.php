<?php
session_start();
include '../config/db.php';
$id = $_GET['id'];
$nuevo_estado = $_GET['estado'];
$id_u = $_SESSION['usuario_id'];

$sql = "UPDATE productos SET estado = $nuevo_estado WHERE id = $id AND id_usuario = $id_u";
if($conn->query($sql)){
    $accion = ($nuevo_estado == 0) ? "Inhabilitó un producto" : "Activó un producto";
    $conn->query("INSERT INTO historial_operaciones (id_usuario, accion) VALUES ($id_u, '$accion ID: $id')");
    header("Location: ../index.php");
}
?>