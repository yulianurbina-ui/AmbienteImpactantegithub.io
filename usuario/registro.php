<?php

include "../conexion.php";

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = trim($_POST["nombre"]);
    $apellido = trim($_POST["apellido"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $telefono = trim($_POST["telefono"]);
    $direccion = trim($_POST["direccion"]);

    // ==========================================
    // VALIDACIONES
    // ==========================================

    // Nombre: solamente letras y espacios
    if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $nombre)) {

        $mensaje = "El nombre solo puede contener letras.";

    // Apellido: solamente letras y espacios
    } elseif (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $apellido)) {

        $mensaje = "El apellido solo puede contener letras.";

    // Correo: debe tener formato válido y @
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $mensaje = "Ingresa un correo electrónico válido.";

    // Teléfono: solamente números
    } elseif (!empty($telefono) && !preg_match("/^[0-9]+$/", $telefono)) {

        $mensaje = "El teléfono solo puede contener números.";

    // Contraseña: mínimo 6 caracteres
    } elseif (strlen($password) < 6) {

        $mensaje = "La contraseña debe tener mínimo 6 caracteres.";

    } else {

        // ==========================================
        // VERIFICAR SI EL CORREO YA EXISTE
        // ==========================================

        $consulta = $conexion->prepare(
            "SELECT id_usuario FROM usuarios WHERE email = ?"
        );

        $consulta->bind_param("s", $email);
        $consulta->execute();

        $resultado = $consulta->get_result();

        if ($resultado->num_rows > 0) {

            $mensaje = "Este correo ya está registrado.";

        } else {

            // ==========================================
            // ENCRIPTAR CONTRASEÑA
            // ==========================================

            $password_segura = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            // ==========================================
            // INSERTAR USUARIO
            // ==========================================

            $insertar = $conexion->prepare(
                "INSERT INTO usuarios
                (nombre, apellido, email, password, telefono, direccion)
                VALUES (?, ?, ?, ?, ?, ?)"
            );

            $insertar->bind_param(
                "ssssss",
                $nombre,
                $apellido,
                $email,
                $password_segura,
                $telefono,
                $direccion
            );

            if ($insertar->execute()) {

                $mensaje = "¡Registro exitoso! Ya puedes iniciar sesión.";

            } else {

                $mensaje = "Ocurrió un error al registrar el usuario.";

            }

            $insertar->close();
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

    <title>Crear cuenta - AquaComunidad</title>

    <link rel="stylesheet"
          href="../style.css">

    <style>

        .registro-container {
            min-height: 80vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
        }

        .registro-card {
            width: 100%;
            max-width: 600px;
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.10);
        }

        .registro-card h1 {
            text-align: center;
            color: #087ea4;
            margin-bottom: 10px;
        }

        .registro-card p {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
        }

        .campo {
            margin-bottom: 18px;
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

        .boton-registro {
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

        .boton-registro:hover {
            background: #056b8b;
        }

        .mensaje {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 10px;
            background: #e8f7fb;
            color: #087ea4;
            text-align: center;
        }

        .login-link {
            margin-top: 20px;
            text-align: center;
        }

        .login-link a {
            color: #087ea4;
            font-weight: bold;
            text-decoration: none;
        }

    </style>

</head>

<body>

    <div class="registro-container">

        <div class="registro-card">

            <h1>Crear cuenta</h1>

            <p>
                Únete a AquaComunidad y empieza a cuidar el agua.
            </p>

            <?php if (!empty($mensaje)): ?>

                <div class="mensaje">
                    <?php echo htmlspecialchars($mensaje); ?>
                </div>

            <?php endif; ?>

            <form method="POST">

                <!-- NOMBRE -->

                <div class="campo">

                    <label for="nombre">
                        Nombre *
                    </label>

                    <input
                        type="text"
                        id="nombre"
                        name="nombre"
                        required
                        pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+"
                        title="El nombre solo puede contener letras y espacios."
                    >

                </div>


                <!-- APELLIDO -->

                <div class="campo">

                    <label for="apellido">
                        Apellido *
                    </label>

                    <input
                        type="text"
                        id="apellido"
                        name="apellido"
                        required
                        pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+"
                        title="El apellido solo puede contener letras y espacios."
                    >

                </div>


                <!-- CORREO -->

                <div class="campo">

                    <label for="email">
                        Correo electrónico *
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        required
                        title="Ingresa un correo válido, por ejemplo: usuario@gmail.com"
                    >

                </div>


                <!-- CONTRASEÑA -->

                <div class="campo">

                    <label for="password">
                        Contraseña *
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        minlength="6"
                        title="La contraseña debe tener mínimo 6 caracteres."
                    >

                </div>


                <!-- TELEFONO -->

                <div class="campo">

                    <label for="telefono">
                        Teléfono
                    </label>

                    <input
                        type="tel"
                        id="telefono"
                        name="telefono"
                        inputmode="numeric"
                        pattern="[0-9]+"
                        title="El teléfono solo puede contener números."
                    >

                </div>


                <!-- DIRECCION -->

                <div class="campo">

                    <label for="direccion">
                        Dirección
                    </label>

                    <input
                        type="text"
                        id="direccion"
                        name="direccion"
                    >

                </div>


                <button
                    type="submit"
                    class="boton-registro"
                >
                    Crear mi cuenta
                </button>

            </form>

            <div class="login-link">

                ¿Ya tienes una cuenta?

                <a href="login.php">
                    Iniciar sesión
                </a>

            </div>

        </div>

    </div>

</body>

</html>