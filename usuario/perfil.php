<?php

session_start();

include "../conexion.php";

// Verificar si el usuario inició sesión
if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

$id_usuario = $_SESSION["id_usuario"];

// Obtener información actualizada del usuario
$consulta = $conexion->prepare(
    "SELECT nombre, apellido, email, telefono, direccion, puntos, fecha_registro, rol
     FROM usuarios
     WHERE id_usuario = ?"
);

$consulta->bind_param("i", $id_usuario);
$consulta->execute();

$resultado = $consulta->get_result();

if ($resultado->num_rows != 1) {
    session_destroy();
    header("Location: login.php");
    exit;
}

$usuario = $resultado->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Mi perfil - AquaComunidad</title>

    <link rel="stylesheet"
          href="../style.css">

    <style>

        .perfil-container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 50px 20px;
        }

        .perfil-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .perfil-header h1 {
            color: #087ea4;
            margin-bottom: 8px;
        }

        .perfil-header p {
            color: #666;
        }

        .perfil-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
        }

        .perfil-card {
            background: white;
            padding: 30px;
            border-radius: 18px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        .perfil-card h2 {
            color: #087ea4;
            margin-bottom: 20px;
        }

        .dato {
            padding: 12px 0;
            border-bottom: 1px solid #eeeeee;
        }

        .dato:last-child {
            border-bottom: none;
        }

        .dato strong {
            display: block;
            color: #555;
            margin-bottom: 4px;
        }

        .dato span {
            color: #222;
        }

        .puntos-card {
            text-align: center;
        }

        .puntos-numero {
            font-size: 50px;
            font-weight: bold;
            color: #087ea4;
            margin: 15px 0;
        }

        .puntos-texto {
            color: #666;
        }

        .acciones {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 20px;
        }

        .accion {
            display: block;
            padding: 18px;
            background: #eefaff;
            border-radius: 12px;
            text-decoration: none;
            color: #087ea4;
            font-weight: bold;
            text-align: center;
            transition: 0.2s;
        }

        .accion:hover {
            background: #d9f4fc;
            transform: translateY(-2px);
        }

        .admin-accion {
            display: block;
            padding: 18px;
            background: #087ea4;
            border-radius: 12px;
            text-decoration: none;
            color: white;
            font-weight: bold;
            text-align: center;
            transition: 0.2s;
            grid-column: 1 / -1;
        }

        .admin-accion:hover {
            background: #066783;
            transform: translateY(-2px);
        }

        .cerrar-sesion {
            display: inline-block;
            margin-top: 30px;
            padding: 13px 25px;
            background: #e74c3c;
            color: white;
            border-radius: 10px;
            text-decoration: none;
            font-weight: bold;
        }

        .cerrar-sesion:hover {
            background: #c0392b;
        }

        .volver {
            display: inline-block;
            margin-bottom: 25px;
            color: #087ea4;
            text-decoration: none;
            font-weight: bold;
        }

        @media (max-width: 700px) {

            .perfil-grid {
                grid-template-columns: 1fr;
            }

            .acciones {
                grid-template-columns: 1fr;
            }

            .admin-accion {
                grid-column: auto;
            }

        }

    </style>

</head>

<body>

    <div class="perfil-container">

        <a href="../index.php" class="volver">
            ← Volver al inicio
        </a>

        <div class="perfil-header">

            <h1>
                Hola, <?php echo htmlspecialchars($usuario["nombre"]); ?> 👋
            </h1>

            <p>
                Este es tu espacio dentro de AquaComunidad.
            </p>

        </div>

        <div class="perfil-grid">

            <!-- INFORMACIÓN PERSONAL -->

            <div class="perfil-card">

                <h2>👤 Mi información</h2>

                <div class="dato">

                    <strong>Nombre completo</strong>

                    <span>
                        <?php
                        echo htmlspecialchars(
                            $usuario["nombre"] . " " . $usuario["apellido"]
                        );
                        ?>
                    </span>

                </div>

                <div class="dato">

                    <strong>Correo electrónico</strong>

                    <span>
                        <?php echo htmlspecialchars($usuario["email"]); ?>
                    </span>

                </div>

                <div class="dato">

                    <strong>Teléfono</strong>

                    <span>
                        <?php
                        echo !empty($usuario["telefono"])
                            ? htmlspecialchars($usuario["telefono"])
                            : "No registrado";
                        ?>
                    </span>

                </div>

                <div class="dato">

                    <strong>Dirección</strong>

                    <span>
                        <?php
                        echo !empty($usuario["direccion"])
                            ? htmlspecialchars($usuario["direccion"])
                            : "No registrada";
                        ?>
                    </span>

                </div>

                <div class="dato">

                    <strong>Miembro desde</strong>

                    <span>
                        <?php
                        echo date(
                            "d/m/Y",
                            strtotime($usuario["fecha_registro"])
                        );
                        ?>
                    </span>

                </div>

                <div class="dato">

                    <strong>Tipo de cuenta</strong>

                    <span>
                        <?php
                        echo ($usuario["rol"] === "admin")
                            ? "Administrador"
                            : "Usuario";
                        ?>
                    </span>

                </div>

            </div>


            <!-- PUNTOS Y ACCIONES -->

            <div class="perfil-card puntos-card">

                <h2>💧 Mis puntos</h2>

                <div class="puntos-numero">

                    <?php echo $usuario["puntos"]; ?>

                </div>

                <div class="puntos-texto">

                    Puntos ambientales acumulados

                </div>

                <div class="acciones">

                    <?php if ($usuario["rol"] === "admin"): ?>

                        <a href="../admin/index.php" class="admin-accion">
                            🛠️ Panel de administrador
                        </a>

                    <?php endif; ?>

                    <a href="../retos/index.php" class="accion">
                        🏆 Ver retos
                    </a>

                    <a href="../reportes/crear.php" class="accion">
                        🚰 Reportar daño
                    </a>

                    <a href="../reportes/mis_reportes.php" class="accion">
                        📋 Mis reportes
                    </a>

                    <a href="../consejos/index.php" class="accion">
                        💡 Consejos
                    </a>

                </div>

            </div>

        </div>


        <!-- CERRAR SESIÓN -->

        <div style="text-align: center;">

            <a href="logout.php" class="cerrar-sesion">
                Cerrar sesión
            </a>

        </div>

    </div>

</body>

</html>