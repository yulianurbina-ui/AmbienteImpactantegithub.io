<?php

session_start();

include "../conexion.php";

// ==========================================
// VERIFICAR SESIÓN
// ==========================================

if (!isset($_SESSION["id_usuario"])) {

    header("Location: ../usuario/login.php");
    exit;

}


// ==========================================
// VERIFICAR ADMIN
// ==========================================

if ($_SESSION["rol"] !== "admin") {

    header("Location: ../usuario/perfil.php");
    exit;

}


// ==========================================
// RECIBIR DATOS
// ==========================================

$id_reporte = $_POST["id_reporte"] ?? "";
$estado = $_POST["estado"] ?? "";


// ==========================================
// VALIDAR ESTADO
// ==========================================

$estados_permitidos = [
    "pendiente",
    "en_revision",
    "atendido",
    "cerrado"
];

if (
    empty($id_reporte) ||
    !in_array($estado, $estados_permitidos)
) {

    header("Location: index.php");
    exit;

}


// ==========================================
// ACTUALIZAR REPORTE
// ==========================================

$actualizar = $conexion->prepare(
    "UPDATE reportes
     SET estado = ?
     WHERE id_reporte = ?"
);

$actualizar->bind_param(
    "si",
    $estado,
    $id_reporte
);

$actualizar->execute();

$actualizar->close();


// ==========================================
// VOLVER AL PANEL
// ==========================================

header("Location: index.php");

exit;

?>