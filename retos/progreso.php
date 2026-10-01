<?php

session_start();

include "../conexion.php";

// Verificar que el usuario haya iniciado sesión
if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../usuario/login.php");
    exit;
}

$id_usuario = $_SESSION["id_usuario"];
$id_participacion = $_GET["id"] ?? "";

if (empty($id_participacion) || !is_numeric($id_participacion)) {
    header("Location: index.php");
    exit;
}

// Obtener información de la participación y del reto
$consulta = $conexion->prepare(
    "SELECT
        p.id_participacion,
        p.progreso,
        p.completado,
        r.id_reto,
        r.nombre,
        r.descripcion,
        r.duracion_dias,
        r.puntos
     FROM participacion_retos p
     INNER JOIN retos r
        ON p.id_reto = r.id_reto
     WHERE p.id_participacion = ?
     AND p.id_usuario = ?"
);

$consulta->bind_param("ii", $id_participacion, $id_usuario);
$consulta->execute();

$resultado = $consulta->get_result();

if ($resultado->num_rows != 1) {
    $consulta->close();
    header("Location: index.php");
    exit;
}

$reto = $resultado->fetch_assoc();

$consulta->close();

$mensaje = "";
$tipo_mensaje = "";

// Procesar actualización del progreso
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nuevo_progreso = $_POST["progreso"] ?? "";

    // Verificar que sea un número
    if (!is_numeric($nuevo_progreso)) {

        $mensaje = "Ingresa un valor válido.";
        $tipo_mensaje = "error";

    } else {

        $nuevo_progreso = intval($nuevo_progreso);

        // Limitar el progreso entre 0 y 100
        if ($nuevo_progreso < 0) {
            $nuevo_progreso = 0;
        }

        if ($nuevo_progreso > 100) {
            $nuevo_progreso = 100;
        }

        // Si ya estaba completado, no modificarlo
        if ($reto["completado"] == 1) {

            $mensaje = "Este reto ya está completado.";
            $tipo_mensaje = "error";

        } else {

            // Si llega al 100%, completar el reto
            if ($nuevo_progreso >= 100) {

                $conexion->begin_transaction();

                try {

                    // Actualizar participación
                    $actualizar = $conexion->prepare(
                        "UPDATE participacion_retos
                         SET progreso = 100,
                             completado = TRUE
                         WHERE id_participacion = ?
                         AND id_usuario = ?
                         AND completado = FALSE"
                    );

                    $actualizar->bind_param(
                        "ii",
                        $id_participacion,
                        $id_usuario
                    );

                    $actualizar->execute();

                    $filas_actualizadas = $actualizar->affected_rows;

                    $actualizar->close();

                    // Solo entregar puntos si realmente se completó ahora
                    if ($filas_actualizadas == 1) {

                        $actualizar_puntos = $conexion->prepare(
                            "UPDATE usuarios
                             SET puntos = puntos + ?
                             WHERE id_usuario = ?"
                        );

                        $actualizar_puntos->bind_param(
                            "ii",
                            $reto["puntos"],
                            $id_usuario
                        );

                        $actualizar_puntos->execute();
                        $actualizar_puntos->close();

                        $conexion->commit();

                        $mensaje = "¡Felicitaciones! Completaste el reto y ganaste "
                            . $reto["puntos"] . " puntos.";
                        $tipo_mensaje = "exito";

                    } else {

                        $conexion->rollback();

                        $mensaje = "El reto ya había sido completado.";
                        $tipo_mensaje = "error";
                    }

                } catch (Exception $e) {

                    $conexion->rollback();

                    $mensaje = "Ocurrió un error al completar el reto.";
                    $tipo_mensaje = "error";
                }

            } else {

                // Actualizar progreso sin completar
                $actualizar = $conexion->prepare(
                    "UPDATE participacion_retos
                     SET progreso = ?
                     WHERE id_participacion = ?
                     AND id_usuario = ?"
                );

                $actualizar->bind_param(
                    "iii",
                    $nuevo_progreso,
                    $id_participacion,
                    $id_usuario
                );

                if ($actualizar->execute()) {

                    $mensaje = "Progreso actualizado correctamente.";
                    $tipo_mensaje = "exito";

                } else {

                    $mensaje = "No se pudo actualizar el progreso.";
                    $tipo_mensaje = "error";
                }

                $actualizar->close();
            }

            // Actualizar información mostrada en pantalla
            $consulta = $conexion->prepare(
                "SELECT
                    p.progreso,
                    p.completado,
                    r.nombre,
                    r.descripcion,
                    r.duracion_dias,
                    r.puntos
                 FROM participacion_retos p
                 INNER JOIN retos r
                    ON p.id_reto = r.id_reto
                 WHERE p.id_participacion = ?
                 AND p.id_usuario = ?"
            );

            $consulta->bind_param(
                "ii",
                $id_participacion,
                $id_usuario
            );

            $consulta->execute();

            $resultado = $consulta->get_result();

            if ($resultado->num_rows == 1) {
                $reto = $resultado->fetch_assoc();
            }

            $consulta->close();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Progreso del reto - AquaComunidad</title>

    <link rel="stylesheet"
          href="../style.css">

    <style>

        .progreso-container {
            max-width: 750px;
            margin: 0 auto;
            padding: 50px 20px;
        }

        .volver {
            display: inline-block;
            margin-bottom: 25px;
            color: #087ea4;
            text-decoration: none;
            font-weight: bold;
        }

        .progreso-card {
            background: white;
            padding: 35px;
            border-radius: 18px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        .progreso-card h1 {
            color: #087ea4;
            margin-bottom: 15px;
        }

        .descripcion {
            color: #555;
            line-height: 1.6;
            margin-bottom: 25px;
        }

        .informacion {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 30px;
        }

        .dato {
            background: #eefaff;
            padding: 18px;
            border-radius: 12px;
            text-align: center;
        }

        .dato strong {
            display: block;
            color: #087ea4;
            font-size: 24px;
            margin-bottom: 5px;
        }

        .dato span {
            color: #666;
        }

        .progreso-actual {
            margin-bottom: 30px;
        }

        .progreso-texto {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            color: #555;
        }

        .barra {
            width: 100%;
            height: 18px;
            background: #e5e5e5;
            border-radius: 20px;
            overflow: hidden;
        }

        .barra-progreso {
            height: 100%;
            background: #087ea4;
            border-radius: 20px;
        }

        .formulario label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #444;
        }

        .formulario input {
            width: 100%;
            box-sizing: border-box;
            padding: 13px;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-size: 16px;
            margin-bottom: 15px;
        }

        .boton {
            width: 100%;
            padding: 14px;
            background: #087ea4;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        .boton:hover {
            background: #066783;
        }

        .mensaje {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .mensaje-exito {
            background: #d9f5df;
            color: #218838;
        }

        .mensaje-error {
            background: #fde2e2;
            color: #c0392b;
        }

        .completado {
            text-align: center;
            background: #d9f5df;
            color: #218838;
            padding: 20px;
            border-radius: 12px;
            font-weight: bold;
            font-size: 18px;
        }

        @media (max-width: 600px) {

            .informacion {
                grid-template-columns: 1fr;
            }

        }

    </style>

</head>

<body>

    <div class="progreso-container">

        <a href="index.php" class="volver">
            ← Volver a los retos
        </a>

        <div class="progreso-card">

            <h1>
                💧 <?php echo htmlspecialchars($reto["nombre"]); ?>
            </h1>

            <p class="descripcion">
                <?php echo htmlspecialchars($reto["descripcion"]); ?>
            </p>

            <div class="informacion">

                <div class="dato">

                    <strong>
                        <?php echo $reto["duracion_dias"]; ?>
                    </strong>

                    <span>
                        días
                    </span>

                </div>

                <div class="dato">

                    <strong>
                        <?php echo $reto["puntos"]; ?>
                    </strong>

                    <span>
                        puntos al completar
                    </span>

                </div>

            </div>


            <!-- PROGRESO ACTUAL -->

            <div class="progreso-actual">

                <div class="progreso-texto">

                    <span>
                        Progreso actual
                    </span>

                    <strong>
                        <?php echo $reto["progreso"]; ?>%
                    </strong>

                </div>

                <div class="barra">

                    <div
                        class="barra-progreso"
                        style="width: <?php echo $reto["progreso"]; ?>%;"
                    ></div>

                </div>

            </div>


            <?php if (!empty($mensaje)): ?>

                <div
                    class="mensaje
                    <?php
                    echo ($tipo_mensaje == "exito")
                        ? "mensaje-exito"
                        : "mensaje-error";
                    ?>"
                >

                    <?php echo htmlspecialchars($mensaje); ?>

                </div>

            <?php endif; ?>


            <?php if ($reto["completado"] == 1): ?>

                <div class="completado">

                    🎉 ¡Reto completado!

                    <br><br>

                    Ya recibiste los
                    <?php echo $reto["puntos"]; ?>
                    puntos correspondientes.

                </div>

            <?php else: ?>

                <form method="POST" class="formulario">

                    <label for="progreso">
                        Actualiza tu progreso
                    </label>

                    <input
                        type="number"
                        id="progreso"
                        name="progreso"
                        min="0"
                        max="100"
                        value="<?php echo $reto["progreso"]; ?>"
                        required
                    >

                    <button type="submit" class="boton">
                        💧 Guardar progreso
                    </button>

                </form>

            <?php endif; ?>

        </div>

    </div>

</body>

</html>