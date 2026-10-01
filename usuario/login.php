<?php

session_start();

include "../conexion.php";

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (empty($email) || empty($password)) {

        $mensaje = "Por favor completa todos los campos.";

    } else {

        $consulta = $conexion->prepare(
            "SELECT id_usuario, nombre, apellido, email, password, rol, puntos
             FROM usuarios
             WHERE email = ?"
        );

        $consulta->bind_param("s", $email);
        $consulta->execute();

        $resultado = $consulta->get_result();

        if ($resultado->num_rows == 1) {

            $usuario = $resultado->fetch_assoc();

            if (password_verify($password, $usuario["password"])) {

                // Guardar información del usuario en la sesión
                $_SESSION["id_usuario"] = $usuario["id_usuario"];
                $_SESSION["nombre"] = $usuario["nombre"];
                $_SESSION["apellido"] = $usuario["apellido"];
                $_SESSION["email"] = $usuario["email"];
                $_SESSION["rol"] = $usuario["rol"];
                $_SESSION["puntos"] = $usuario["puntos"];

                // Enviar al perfil
                header("Location: perfil.php");
                exit;

            } else {

                $mensaje = "El correo o la contraseña son incorrectos.";

            }

        } else {

            $mensaje = "El correo o la contraseña son incorrectos.";

        }

        $consulta->close();
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Iniciar sesión - AquaComunidad</title>

    <link rel="stylesheet"
          href="../style.css">

    <style>

        .login-container {
            min-height: 80vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
        }

        .login-card {
            width: 100%;
            max-width: 450px;
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.10);
        }

        .login-card h1 {
            text-align: center;
            color: #087ea4;
            margin-bottom: 10px;
        }

        .login-card p {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
        }

        .campo {
            margin-bottom: 20px;
        }

        .campo label {
            display: block;
            margin-bottom: 7px;
            font-weight: 600;
            color: #333;
        }

        .campo input {
            width: 100%;
            padding: 13px;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-size: 15px;
            box-sizing: border-box;
        }

        .campo input:focus {
            outline: none;
            border-color: #087ea4;
        }

        .boton-login {
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

        .boton-login:hover {
            background: #056b8b;
        }

        .mensaje {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 10px;
            background: #ffecec;
            color: #c0392b;
            text-align: center;
        }

        .registro-link {
            margin-top: 20px;
            text-align: center;
        }

        .registro-link a {
            color: #087ea4;
            font-weight: bold;
            text-decoration: none;
        }

    </style>

</head>

<body>

    <div class="login-container">

        <div class="login-card">

            <h1>Bienvenido</h1>

            <p>
                Ingresa a AquaComunidad y continúa cuidando el agua.
            </p>

            <?php if (!empty($mensaje)): ?>

                <div class="mensaje">
                    <?php echo htmlspecialchars($mensaje); ?>
                </div>

            <?php endif; ?>

            <form method="POST">

                <div class="campo">

                    <label for="email">
                        Correo electrónico
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        required
                    >

                </div>

                <div class="campo">

                    <label for="password">
                        Contraseña
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                    >

                </div>

                <button
                    type="submit"
                    class="boton-login"
                >
                    Iniciar sesión
                </button>

            </form>

            <div class="registro-link">

                ¿Todavía no tienes una cuenta?

                <a href="registro.php">
                    Crear cuenta
                </a>

            </div>

        </div>

    </div>

</body>

</html>