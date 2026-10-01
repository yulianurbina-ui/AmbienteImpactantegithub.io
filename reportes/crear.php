<?php

session_start();

include "../conexion.php";

// Verificar que el usuario haya iniciado sesión
if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../usuario/login.php");
    exit;
}

$mensaje = "";
$tipo_mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id_usuario = $_SESSION["id_usuario"];
    $tipo = trim($_POST["tipo"]);
    $descripcion = trim($_POST["descripcion"]);
    $ubicacion = trim($_POST["ubicacion"]);

    // ==========================================
    // VALIDACIONES
    // ==========================================

    if (empty($tipo) || empty($descripcion) || empty($ubicacion)) {

        $mensaje = "Por favor completa todos los campos obligatorios.";
        $tipo_mensaje = "error";

    } else {

        // ==========================================
        // GUARDAR REPORTE
        // ==========================================

        $insertar = $conexion->prepare(
            "INSERT INTO reportes
            (id_usuario, tipo, descripcion, ubicacion)
            VALUES (?, ?, ?, ?)"
        );

        $insertar->bind_param(
            "isss",
            $id_usuario,
            $tipo,
            $descripcion,
            $ubicacion
        );

        if ($insertar->execute()) {

            $mensaje = "¡Reporte enviado correctamente! Gracias por ayudar a cuidar el agua.";
            $tipo_mensaje = "exito";

        } else {

            $mensaje = "Ocurrió un error al guardar el reporte.";
            $tipo_mensaje = "error";

        }

        $insertar->close();
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Reportar daño - AquaComunidad</title>

    <link rel="stylesheet"
          href="../style.css">

    <style>

        .reporte-container {
            max-width: 750px;
            margin: 0 auto;
            padding: 50px 20px;
        }

        .reporte-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .reporte-header h1 {
            color: #087ea4;
            margin-bottom: 10px;
        }

        .reporte-header p {
            color: #666;
            line-height: 1.6;
        }

        .reporte-card {
            background: white;
            padding: 35px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        .campo {
            margin-bottom: 20px;
        }

        .campo label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        .campo select,
        .campo input,
        .campo textarea {
            width: 100%;
            padding: 13px;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-size: 15px;
            box-sizing: border-box;
            font-family: inherit;
        }

        .campo textarea {
            min-height: 130px;
            resize: vertical;
        }

        .campo select:focus,
        .campo input:focus,
        .campo textarea:focus {
            outline: none;
            border-color: #087ea4;
        }

        .boton-reporte {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 10px;
            background: #087ea4;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        .boton-reporte:hover {
            background: #056b8b;
        }

        .mensaje {
            padding: 14px;
            margin-bottom: 20px;
            border-radius: 10px;
            text-align: center;
            font-weight: 600;
        }

        .mensaje.exito {
            background: #e8f8ee;
            color: #218838;
        }

        .mensaje.error {
            background: #ffecec;
            color: #c0392b;
        }

        .volver {
            display: inline-block;
            margin-bottom: 25px;
            color: #087ea4;
            text-decoration: none;
            font-weight: bold;
        }

        .informacion {
            margin-top: 25px;
            padding: 18px;
            background: #eefaff;
            border-radius: 12px;
            color: #087ea4;
            line-height: 1.6;
        }

    </style>

</head>

<body>

    <div class="reporte-container">

        <a href="../usuario/perfil.php" class="volver">
            ← Volver a mi perfil
        </a>

        <div class="reporte-header">

            <h1>🚰 Reportar un daño</h1>

            <p>
                Ayúdanos a identificar fugas y problemas relacionados
                con el agua en tu comunidad.
            </p>

        </div>


        <div class="reporte-card">

            <?php if (!empty($mensaje)): ?>

                <div class="mensaje <?php echo $tipo_mensaje; ?>">

                    <?php echo htmlspecialchars($mensaje); ?>

                </div>

            <?php endif; ?>


            <form method="POST">


                <!-- TIPO DE DAÑO -->

                <div class="campo">

                    <label for="tipo">
                        Tipo de daño *
                    </label>

                    <select
                        id="tipo"
                        name="tipo"
                        required
                    >

                        <option value="">
                            Selecciona una opción
                        </option>

                        <option value="Fuga de agua">
                            Fuga de agua
                        </option>

                        <option value="Tubería dañada">
                            Tubería dañada
                        </option>

                        <option value="Llave dañada">
                            Llave o grifo dañado
                        </option>

                        <option value="Alcantarillado">
                            Problema de alcantarillado
                        </option>

                        <option value="Desperdicio de agua">
                            Desperdicio de agua
                        </option>

                        <option value="Otro">
                            Otro
                        </option>

                    </select>

                </div>


                <!-- DESCRIPCIÓN -->

                <div class="campo">

                    <label for="descripcion">
                        Descripción del problema *
                    </label>

                    <textarea
                        id="descripcion"
                        name="descripcion"
                        placeholder="Cuéntanos qué está pasando..."
                        required
                    ></textarea>

                </div>


                <!-- UBICACIÓN -->

                <div class="campo">

                    <label for="ubicacion">
                        Ubicación *
                    </label>

                    <input
                        type="text"
                        id="ubicacion"
                        name="ubicacion"
                        placeholder="Ejemplo: Calle 10 # 20-30"
                        required
                    >

                </div>


                <button
                    type="submit"
                    class="boton-reporte"
                >
                    Enviar reporte
                </button>

            </form>


            <div class="informacion">

                💧 <strong>¿Por qué reportar?</strong>

                <br>

                Un reporte puede ayudar a identificar rápidamente
                una fuga o un problema que esté causando desperdicio
                de agua en la comunidad.

            </div>

        </div>

    </div>

</body>

</html>