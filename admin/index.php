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
// VERIFICAR QUE SEA ADMINISTRADOR
// ==========================================

if ($_SESSION["rol"] !== "admin") {

    header("Location: ../usuario/perfil.php");
    exit;

}


// ==========================================
// CONTAR USUARIOS
// ==========================================

$consulta_usuarios = $conexion->query(
    "SELECT COUNT(*) AS total FROM usuarios"
);

$total_usuarios = $consulta_usuarios->fetch_assoc()["total"];


// ==========================================
// CONTAR REPORTES
// ==========================================

$consulta_reportes = $conexion->query(
    "SELECT COUNT(*) AS total FROM reportes"
);

$total_reportes = $consulta_reportes->fetch_assoc()["total"];


// ==========================================
// CONTAR REPORTES PENDIENTES
// ==========================================

$consulta_pendientes = $conexion->query(
    "SELECT COUNT(*) AS total
     FROM reportes
     WHERE estado = 'pendiente'"
);

$total_pendientes = $consulta_pendientes->fetch_assoc()["total"];


// ==========================================
// OBTENER REPORTES
// ==========================================

$reportes = $conexion->query(
    "SELECT
        r.id_reporte,
        r.tipo,
        r.descripcion,
        r.ubicacion,
        r.estado,
        r.fecha_reporte,
        u.nombre,
        u.apellido,
        u.email
     FROM reportes r
     INNER JOIN usuarios u
        ON r.id_usuario = u.id_usuario
     ORDER BY r.fecha_reporte DESC"
);

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Panel administrador - AquaComunidad</title>

    <link rel="stylesheet"
          href="../style.css">

    <style>

        .admin-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 35px;
            gap: 20px;
        }

        .admin-header h1 {
            color: #087ea4;
            margin-bottom: 5px;
        }

        .admin-header p {
            color: #666;
        }

        .volver {
            color: #087ea4;
            text-decoration: none;
            font-weight: bold;
        }

        .estadisticas {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 40px;
        }

        .estadistica {
            background: white;
            padding: 25px;
            border-radius: 18px;
            text-align: center;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        .estadistica-icono {
            font-size: 30px;
        }

        .estadistica-numero {
            font-size: 35px;
            font-weight: bold;
            color: #087ea4;
            margin: 10px 0;
        }

        .estadistica-texto {
            color: #666;
        }

        .seccion-titulo {
            color: #087ea4;
            margin-bottom: 20px;
        }

        .reporte-admin {
            background: white;
            padding: 25px;
            margin-bottom: 20px;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        .reporte-cabecera {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }

        .reporte-tipo {
            font-size: 20px;
            font-weight: bold;
            color: #087ea4;
        }

        .usuario-reporte {
            background: #f5f5f5;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .dato {
            margin: 10px 0;
            line-height: 1.5;
            color: #555;
        }

        .dato strong {
            color: #333;
        }

        .estado {
            padding: 7px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
            white-space: nowrap;
        }

        .pendiente {
            background: #fff3cd;
            color: #856404;
        }

        .en_revision {
            background: #cfe2ff;
            color: #084298;
        }

        .atendido {
            background: #d1e7dd;
            color: #0f5132;
        }

        .cerrado {
            background: #e2e3e5;
            color: #41464b;
        }

        .cambiar-estado {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-top: 20px;
        }

        .cambiar-estado select {
            flex: 1;
            padding: 11px;
            border: 1px solid #ccc;
            border-radius: 10px;
        }

        .boton-estado {
            padding: 11px 18px;
            border: none;
            border-radius: 10px;
            background: #087ea4;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        .boton-estado:hover {
            background: #056b8b;
        }

        .fecha {
            color: #888;
            font-size: 14px;
            margin-top: 15px;
        }

        .sin-reportes {
            background: white;
            padding: 40px;
            border-radius: 16px;
            text-align: center;
            color: #666;
        }

        .cerrar {
            display: inline-block;
            padding: 10px 18px;
            background: #e74c3c;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-weight: bold;
        }

        @media (max-width: 750px) {

            .estadisticas {
                grid-template-columns: 1fr;
            }

            .admin-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .reporte-cabecera {
                flex-direction: column;
                align-items: flex-start;
            }

            .cambiar-estado {
                flex-direction: column;
                align-items: stretch;
            }

        }

    </style>

</head>

<body>

<div class="admin-container">


    <!-- CABECERA -->

    <div class="admin-header">

        <div>

            <h1>👨‍💼 Panel de administrador</h1>

            <p>
                Bienvenido,
                <?php echo htmlspecialchars($_SESSION["nombre"]); ?>.
                Aquí puedes gestionar AquaComunidad.
            </p>

        </div>

        <a
            href="../usuario/logout.php"
            class="cerrar"
        >
            Cerrar sesión
        </a>

    </div>


    <!-- ESTADÍSTICAS -->

    <div class="estadisticas">


        <div class="estadistica">

            <div class="estadistica-icono">
                👥
            </div>

            <div class="estadistica-numero">
                <?php echo $total_usuarios; ?>
            </div>

            <div class="estadistica-texto">
                Usuarios registrados
            </div>

        </div>


        <div class="estadistica">

            <div class="estadistica-icono">
                🚰
            </div>

            <div class="estadistica-numero">
                <?php echo $total_reportes; ?>
            </div>

            <div class="estadistica-texto">
                Reportes recibidos
            </div>

        </div>


        <div class="estadistica">

            <div class="estadistica-icono">
                ⏳
            </div>

            <div class="estadistica-numero">
                <?php echo $total_pendientes; ?>
            </div>

            <div class="estadistica-texto">
                Reportes pendientes
            </div>

        </div>

    </div>


    <!-- REPORTES -->

    <h2 class="seccion-titulo">
        🚰 Gestión de reportes
    </h2>


    <?php if ($reportes->num_rows > 0): ?>


        <?php while ($reporte = $reportes->fetch_assoc()): ?>


            <div class="reporte-admin">


                <div class="reporte-cabecera">

                    <div class="reporte-tipo">

                        🚰
                        <?php
                        echo htmlspecialchars($reporte["tipo"]);
                        ?>

                    </div>


                    <div class="estado <?php echo $reporte["estado"]; ?>">

                        <?php

                        if ($reporte["estado"] == "pendiente") {

                            echo "Pendiente";

                        } elseif ($reporte["estado"] == "en_revision") {

                            echo "En revisión";

                        } elseif ($reporte["estado"] == "atendido") {

                            echo "Atendido";

                        } else {

                            echo "Cerrado";

                        }

                        ?>

                    </div>

                </div>


                <!-- INFORMACIÓN DEL USUARIO -->

                <div class="usuario-reporte">

                    <strong>👤 Reportado por:</strong>

                    <?php

                    echo htmlspecialchars(
                        $reporte["nombre"] .
                        " " .
                        $reporte["apellido"]
                    );

                    ?>

                    <br>

                    <strong>📧 Correo:</strong>

                    <?php
                    echo htmlspecialchars($reporte["email"]);
                    ?>

                </div>


                <!-- DESCRIPCIÓN -->

                <div class="dato">

                    <strong>Descripción:</strong>

                    <br>

                    <?php

                    echo nl2br(
                        htmlspecialchars($reporte["descripcion"])
                    );

                    ?>

                </div>


                <!-- UBICACIÓN -->

                <div class="dato">

                    <strong>📍 Ubicación:</strong>

                    <?php

                    echo htmlspecialchars(
                        $reporte["ubicacion"]
                    );

                    ?>

                </div>


                <!-- FECHA -->

                <div class="fecha">

                    📅

                    <?php

                    echo date(
                        "d/m/Y H:i",
                        strtotime($reporte["fecha_reporte"])
                    );

                    ?>

                </div>


                <!-- CAMBIAR ESTADO -->

                <form
                    method="POST"
                    action="cambiar_estado.php"
                    class="cambiar-estado"
                >

                    <input
                        type="hidden"
                        name="id_reporte"
                        value="<?php echo $reporte["id_reporte"]; ?>"
                    >

                    <select name="estado" required>

                        <option value="pendiente"
                            <?php
                            if ($reporte["estado"] == "pendiente")
                                echo "selected";
                            ?>>
                            Pendiente
                        </option>

                        <option value="en_revision"
                            <?php
                            if ($reporte["estado"] == "en_revision")
                                echo "selected";
                            ?>>
                            En revisión
                        </option>

                        <option value="atendido"
                            <?php
                            if ($reporte["estado"] == "atendido")
                                echo "selected";
                            ?>>
                            Atendido
                        </option>

                        <option value="cerrado"
                            <?php
                            if ($reporte["estado"] == "cerrado")
                                echo "selected";
                            ?>>
                            Cerrado
                        </option>

                    </select>

                    <button
                        type="submit"
                        class="boton-estado"
                    >
                        Actualizar estado
                    </button>

                </form>


            </div>


        <?php endwhile; ?>


    <?php else: ?>


        <div class="sin-reportes">

            <h2>💧 No hay reportes todavía</h2>

            <p>
                Cuando los usuarios creen reportes,
                aparecerán aquí.
            </p>

        </div>


    <?php endif; ?>


</div>

</body>

</html>