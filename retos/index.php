<?php

session_start();

include "../conexion.php";

// Verificar si el usuario inició sesión
if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../usuario/login.php");
    exit;
}

$id_usuario = $_SESSION["id_usuario"];

// Obtener retos activos
$consulta = $conexion->prepare(
    "SELECT 
        r.id_reto,
        r.nombre,
        r.descripcion,
        r.duracion_dias,
        r.puntos,
        r.fecha_inicio,
        r.fecha_fin,
        r.estado,
        p.id_participacion,
        p.progreso,
        p.completado
    FROM retos r
    LEFT JOIN participacion_retos p
        ON r.id_reto = p.id_reto
        AND p.id_usuario = ?
    WHERE r.estado = 'activo'
    ORDER BY r.id_reto ASC"
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

    <title>Retos - AquaComunidad</title>

    <link rel="stylesheet"
          href="../style.css">

    <style>

        .retos-container {
            max-width: 1150px;
            margin: 0 auto;
            padding: 50px 20px;
        }

        .retos-header {
            text-align: center;
            margin-bottom: 45px;
        }

        .retos-header h1 {
            color: #087ea4;
            margin-bottom: 10px;
        }

        .retos-header p {
            color: #666;
            font-size: 17px;
        }

        .volver {
            display: inline-block;
            margin-bottom: 25px;
            color: #087ea4;
            text-decoration: none;
            font-weight: bold;
        }

        .retos-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
        }

        .reto-card {
            background: white;
            border-radius: 18px;
            padding: 28px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            display: flex;
            flex-direction: column;
        }

        .reto-icono {
            font-size: 45px;
            margin-bottom: 15px;
        }

        .reto-card h2 {
            color: #087ea4;
            margin-bottom: 12px;
        }

        .reto-descripcion {
            color: #555;
            line-height: 1.6;
            flex-grow: 1;
        }

        .reto-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin: 20px 0;
        }

        .reto-dato {
            background: #eefaff;
            padding: 12px;
            border-radius: 10px;
            text-align: center;
        }

        .reto-dato strong {
            display: block;
            color: #087ea4;
            font-size: 18px;
        }

        .reto-dato span {
            color: #666;
            font-size: 13px;
        }

        .barra-contenedor {
            margin: 15px 0;
        }

        .barra-texto {
            display: flex;
            justify-content: space-between;
            margin-bottom: 7px;
            color: #555;
            font-size: 14px;
        }

        .barra {
            width: 100%;
            height: 12px;
            background: #e5e5e5;
            border-radius: 20px;
            overflow: hidden;
        }

        .barra-progreso {
            height: 100%;
            background: #087ea4;
            border-radius: 20px;
        }

        .boton {
            display: block;
            width: 100%;
            box-sizing: border-box;
            padding: 13px;
            border: none;
            border-radius: 10px;
            text-align: center;
            text-decoration: none;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
            font-size: 15px;
        }

        .boton-participar {
            background: #087ea4;
            color: white;
        }

        .boton-participar:hover {
            background: #066783;
        }

        .boton-progreso {
            background: #eefaff;
            color: #087ea4;
        }

        .boton-completado {
            background: #d9f5df;
            color: #218838;
            cursor: default;
        }

        .mensaje-vacio {
            background: white;
            padding: 40px;
            text-align: center;
            border-radius: 18px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        @media (max-width: 900px) {

            .retos-grid {
                grid-template-columns: 1fr 1fr;
            }

        }

        @media (max-width: 600px) {

            .retos-grid {
                grid-template-columns: 1fr;
            }

        }

    </style>

</head>

<body>

    <div class="retos-container">

        <a href="../usuario/perfil.php" class="volver">
            ← Volver a mi perfil
        </a>

        <div class="retos-header">

            <h1>🏆 Retos AquaComunidad</h1>

            <p>
                Participa en diferentes retos y demuestra tu compromiso
                con el cuidado del agua.
            </p>

        </div>


        <?php if ($resultado->num_rows > 0): ?>

            <div class="retos-grid">

                <?php while ($reto = $resultado->fetch_assoc()): ?>

                    <div class="reto-card">

                        <div class="reto-icono">
                            💧
                        </div>

                        <h2>
                            <?php echo htmlspecialchars($reto["nombre"]); ?>
                        </h2>

                        <p class="reto-descripcion">
                            <?php echo htmlspecialchars($reto["descripcion"]); ?>
                        </p>

                        <div class="reto-info">

                            <div class="reto-dato">

                                <strong>
                                    <?php echo $reto["duracion_dias"]; ?>
                                </strong>

                                <span>
                                    días
                                </span>

                            </div>

                            <div class="reto-dato">

                                <strong>
                                    <?php echo $reto["puntos"]; ?>
                                </strong>

                                <span>
                                    puntos
                                </span>

                            </div>

                        </div>


                        <?php if ($reto["id_participacion"] === null): ?>

                            <a
                                href="participar.php?id_reto=<?php echo $reto["id_reto"]; ?>"
                                class="boton boton-participar"
                            >
                                🏆 Participar en este reto
                            </a>


                        <?php elseif ($reto["completado"] == 1): ?>

                            <div class="barra-contenedor">

                                <div class="barra-texto">

                                    <span>Progreso</span>

                                    <strong>100%</strong>

                                </div>

                                <div class="barra">

                                    <div
                                        class="barra-progreso"
                                        style="width: 100%;"
                                    ></div>

                                </div>

                            </div>

                            <div class="boton boton-completado">

                                ✅ Reto completado

                            </div>


                        <?php else: ?>

                            <div class="barra-contenedor">

                                <div class="barra-texto">

                                    <span>Progreso</span>

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

                            <a
                                href="progreso.php?id=<?php echo $reto["id_participacion"]; ?>"
                                class="boton boton-progreso"
                            >
                                📈 Actualizar progreso
                            </a>

                        <?php endif; ?>

                    </div>

                <?php endwhile; ?>

            </div>

        <?php else: ?>

            <div class="mensaje-vacio">

                <h2>No hay retos disponibles</h2>

                <p>
                    En este momento no hay retos activos.
                </p>

            </div>

        <?php endif; ?>

    </div>

</body>

</html>