<?php

session_start();

include "../conexion.php";

// Verificar sesión
if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../usuario/login.php");
    exit;
}

$id_usuario = $_SESSION["id_usuario"];
$id_reto = $_GET["id_reto"] ?? "";

if (empty($id_reto) || !is_numeric($id_reto)) {
    header("Location: index.php");
    exit;
}

// Verificar que el reto exista y esté activo
$consulta = $conexion->prepare(
    "SELECT id_reto
     FROM retos
     WHERE id_reto = ? AND estado = 'activo'"
);

$consulta->bind_param("i", $id_reto);
$consulta->execute();

$resultado = $consulta->get_result();

if ($resultado->num_rows != 1) {
    $consulta->close();
    header("Location: index.php");
    exit;
}

$consulta->close();

// Verificar si ya participa
$consulta = $conexion->prepare(
    "SELECT id_participacion
     FROM participacion_retos
     WHERE id_usuario = ? AND id_reto = ?"
);

$consulta->bind_param("ii", $id_usuario, $id_reto);
$consulta->execute();

$resultado = $consulta->get_result();

if ($resultado->num_rows > 0) {
    $consulta->close();
    header("Location: index.php");
    exit;
}

$consulta->close();

// Registrar participación
$insertar = $conexion->prepare(
    "INSERT INTO participacion_retos
     (id_usuario, id_reto, progreso, completado)
     VALUES (?, ?, 0, FALSE)"
);

$insertar->bind_param("ii", $id_usuario, $id_reto);
$insertar->execute();

$insertar->close();

header("Location: index.php");
exit;

?>