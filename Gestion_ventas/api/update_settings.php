<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['usuario_id'])) {
    die("Acceso denegado");
}

$usuario_id = $_SESSION['usuario_id'];
$directorio = "../uploads/";

if (!file_exists($directorio)) {
    mkdir($directorio, 0777, true);
}

if (isset($_POST['update_logo']) && isset($_FILES['logo']) && $_FILES['logo']['error'] === 0) {
    $nombre_archivo = "logo_" . $usuario_id . "_" . time() . ".png";
    $ruta_final = $directorio . $nombre_archivo;
    $ruta_db = "uploads/" . $nombre_archivo;

    if (move_uploaded_file($_FILES['logo']['tmp_name'], $ruta_final)) {
        $sql = "UPDATE usuarios SET logo = '$ruta_db' WHERE id = $usuario_id";
        if ($conn->query($sql)) {
            echo "<script>alert('Logo actualizado con éxito'); window.location.href='../ajustes.php';</script>";
        } else {
            echo "Error en BD: " . $conn->error . ". Asegúrate de haber ejecutado el SQL del paso 1.";
        }
    }
}

if (isset($_POST['update_perfil']) && isset($_FILES['perfil']) && $_FILES['perfil']['error'] === 0) {
    $nombre_archivo = "perfil_" . $usuario_id . "_" . time() . ".png";
    $ruta_final = $directorio . $nombre_archivo;
    $ruta_db = "uploads/" . $nombre_archivo;

    if (move_uploaded_file($_FILES['perfil']['tmp_name'], $ruta_final)) {
        $sql = "UPDATE usuarios SET foto_perfil = '$ruta_db' WHERE id = $usuario_id";
        if ($conn->query($sql)) {
            echo "<script>alert('Foto de perfil actualizada'); window.location.href='../ajustes.php';</script>";
        } else {
            echo "Error en BD: " . $conn->error;
        }
    }
}

if (isset($_POST['update_info'])) {
    $nombre = $conn->real_escape_string($_POST['nombre_negocio']);
    $sql = "UPDATE usuarios SET nombre_negocio = '$nombre' WHERE id = $usuario_id";
    $conn->query($sql);
    echo "<script>alert('Información guardada'); window.location.href='../ajustes.php';</script>";
}
?>