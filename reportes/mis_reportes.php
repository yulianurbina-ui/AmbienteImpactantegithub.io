<?php

session_start();

include "../conexion.php";

// Verificar que el usuario haya iniciado sesión
if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../usuario/login.php");
    exit;
}

$id_usuario = $_SESSION["id_usuario"];

// Obtener los reportes del usuario
$consulta = $conexion->prepare(
    "SELECT id_reporte, tipo, descripcion, ubicacion, estado, fecha_reporte
     FROM reportes
     WHERE id_usuario = ?
     ORDER BY fecha_reporte DESC"
);

$consulta->bind_param("i", $id_usuario);
$consulta->execute();

$resultado = $consulta->get_result();

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Mis reportes - AquaComunidad</title>

    <link rel="stylesheet"
          href="../style.css">

    <style>

        .reportes-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 50px 20px;
        }

        .reportes-header {
            text-align: center;
            margin-bottom: 35px;
        }

        .reportes-header h1 {
            color: #087ea4;
            margin-bottom: 10px;
        }

        .reportes-header p {
            color: #666;
        }

        .volver {
            display: inline-block;
            margin-bottom: 25px;
            color: #087ea4;
            text-decoration: none;
            font-weight: bold;
        }

        .reporte-card {
            background: white;
            padding: 25px;
            margin-bottom: 20px;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        .reporte-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }

        .reporte-tipo {
            color: #087ea4;
            font-size: 20px;
            font-weight: bold;
        }

        .estado {
            padding: 7px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
            white-space: nowrap;
        }

        .estado-pendiente {
            background: #fff3cd;
            color: #856404;
        }

        .estado-en_revision {
            background: #cfe2ff;
            color: #084298;
        }

        .estado-atendido {
            background: #d1e7dd;
            color: #0f5132;
        }

        .estado-cerrado {
            background: #e2e3e5;
            color: #41464b;
        }

        .dato-reporte {
            margin: 10px 0;
            color: #555;
            line-height: 1.5;
        }

        .dato-reporte strong {
            color: #333;
        }

        .fecha {
            color: #888;
            font-size: 14px;
            margin-top: 15px;
        }

        .sin-reportes {
            text-align: center;
            background: white;
            padding: 50px 25px;
            border-radius: 18px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        .sin-reportes h2 {
            color: #087ea4;
        }

        .sin-reportes p {
            color: #666;
            margin-bottom: 25px;
        }

        .boton-reporte {
            display: inline-block;
            padding: 13px 22px;
            background: #087ea4;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-weight: bold;
        }

        .boton-reporte:hover {
            background: #056b8b;
        }

        @media (max-width: 600px) {

            .reporte-top {
                flex-direction: column;
                align-items: flex-start;
            }

        }

    </style>

</head>

<body>

    <div class="reportes-container">

        <a href="../usuario/perfil.php" class="volver">
            ← Volver a mi perfil
        </a>

        <div class="reportes-header">

            <h1>📋 Mis reportes</h1>

            <p>
                Aquí puedes consultar los daños que has reportado
                y revisar su estado.
            </p>

        </div>


        <?php if ($resultado->num_rows > 0): ?>


            <?php while ($reporte = $resultado->fetch_assoc()): ?>

                <div class="reporte-card">

                    <div class="reporte-top">

                        <div class="reporte-tipo">

                            🚰
                            <?php
                            echo htmlspecialchars($reporte["tipo"]);
                            ?>

                        </div>


                        <?php

                        $estado = $reporte["estado"];

                        if ($estado == "pendiente") {
                            $texto_estado = "Pendiente";
                        } elseif ($estado == "en_revision") {
                            $texto_estado = "En revisión";
                        } elseif ($estado == "atendido") {
                            $texto_estado = "Atendido";
                        } else {
                            $texto_estado = "Cerrado";
                        }

                        ?>

                        <div class="estado estado-<?php echo $estado; ?>">

                            <?php echo $texto_estado; ?>

                        </div>

                    </div>


                    <div class="dato-reporte">

                        <strong>Descripción:</strong>

                        <br>

                        <?php
                        echo nl2br(
                            htmlspecialchars($reporte["descripcion"])
                        );
                        ?>

                    </div>


                    <div class="dato-reporte">

                        <strong>📍 Ubicación:</strong>

                        <?php
                        echo htmlspecialchars($reporte["ubicacion"]);
                        ?>

                    </div>


                    <div class="fecha">

                        📅 Reportado el:

                        <?php

                        echo date(
                            "d/m/Y H:i",
                            strtotime($reporte["fecha_reporte"])
                        );

                        ?>

                    </div>

                </div>

            <?php endwhile; ?>


        <?php else: ?>


            <div class="sin-reportes">

                <h2>💧 Todavía no tienes reportes</h2>

                <p>
                    Cuando encuentres una fuga o un problema
                    relacionado con el agua, puedes reportarlo
                    desde aquí.
                </p>

                <a
                    href="crear.php"
                    class="boton-reporte"
                >
                    🚰 Crear mi primer reporte
                </a>

            </div>


        <?php endif; ?>


    </div>

</body>

</html>

<?php

$consulta->close();

?>