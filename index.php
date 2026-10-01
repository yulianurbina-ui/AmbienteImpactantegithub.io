<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AquaComiunity | Cada gota cuenta</title>

    <link rel="stylesheet" href="style.css">
</head>

<body>

    <!-- BARRA DE NAVEGACIÓN -->
    <header class="navbar">

        <div class="logo">
            <span class="logo-icon">💧</span>
            <div>
                <h1>AquaComiunity</h1>
                <span>Cada gota cuenta</span>
            </div>
        </div>

        <nav>
            <a href="index.php" class="activo">Inicio</a>
            <a href="consejos/index.php">Consejos</a>
            <a href="consejos/index.php">Aguas lluvias</a>
            <a href="reportes/crear.php">Reportar Daños</a>
            <a href="retos/index.php">Retos</a>
            <a href="consejos/index.php">Reciclaje</a>
        </nav>

        <div class="nav-login">
            <a href="usuario/login.php" class="btn-login">Iniciar sesión</a>
        </div>

    </header>


    <!-- SECCIÓN PRINCIPAL -->
    <main>

        <section class="hero">

            <div class="hero-contenido">

                <span class="etiqueta">🌎 Cuidemos nuestro planeta</span>

                <h2>
                    Cada gota cuenta,
                    <span>cada acción importa.</span>
                </h2>

                <p>
                    AquaComunidad es una plataforma creada para incentivar
                    a la comunidad a cuidar el agua, aprovechar las aguas
                    lluvias, reportar daños y participar en actividades
                    ambientales.
                </p>

                <div class="hero-botones">
                    <a href="usuario/registro.php" class="btn-principal">
                        Únete a la comunidad
                    </a>

                    <a href="#como-funciona" class="btn-secundario">
                        Conoce más
                    </a>
                </div>

            </div>

            <div class="hero-visual">

                <div class="gota-grande">
                    💧
                </div>

                <div class="mensaje-agua">
                    <strong>El agua es vida</strong>
                    <span>Ayudemos a conservarla.</span>
                </div>

            </div>

        </section>


        <!-- ESTADÍSTICAS -->
        <section class="estadisticas">

            <div class="estadistica">
                <span class="estadistica-icono">💧</span>
                <div>
                    <strong>0</strong>
                    <p>Acciones registradas</p>
                </div>
            </div>

            <div class="estadistica">
                <span class="estadistica-icono">🚨</span>
                <div>
                    <strong>0</strong>
                    <p>Reportes realizados</p>
                </div>
            </div>

            <div class="estadistica">
                <span class="estadistica-icono">🏆</span>
                <div>
                    <strong>0</strong>
                    <p>Retos completados</p>
                </div>
            </div>

            <div class="estadistica">
                <span class="estadistica-icono">♻️</span>
                <div>
                    <strong>0</strong>
                    <p>Acciones ambientales</p>
                </div>
            </div>

        </section>


        <!-- COMO FUNCIONA -->
        <section class="seccion" id="como-funciona">

            <div class="titulo-seccion">
                <span>¿QUÉ PUEDES HACER?</span>

                <h2>
                    Pequeñas acciones pueden generar
                    <strong>grandes cambios</strong>
                </h2>

                <p>
                    Nuestra plataforma busca convertir el cuidado del agua
                    en una actividad participativa, sencilla y dinámica.
                </p>
            </div>


            <div class="tarjetas">

                <!-- TARJETA 1 -->
                <article class="tarjeta">

                    <div class="tarjeta-icono azul">
                        💡
                    </div>

                    <h3>Aprende a cuidar el agua</h3>

                    <p>
                        Encuentra consejos y recomendaciones para utilizar
                        el agua de manera responsable en tus actividades
                        diarias.
                    </p>

                    <a href="consejos/index.php">
                        Ver consejos →
                    </a>

                </article>


                <!-- TARJETA 2 -->
                <article class="tarjeta">

                    <div class="tarjeta-icono celeste">
                        🌧️
                    </div>

                    <h3>Aprovecha el agua lluvia</h3>

                    <p>
                        Conoce diferentes formas de recolectar y aprovechar
                        el agua lluvia para actividades que no requieren
                        agua potable.
                    </p>

                    <a href="consejos/index.php">
                        Aprender más →
                    </a>

                </article>


                <!-- TARJETA 3 -->
                <article class="tarjeta">

                    <div class="tarjeta-icono rojo">
                        🚨
                    </div>

                    <h3>Reporta daños</h3>

                    <p>
                        Identifica una fuga o un daño relacionado con el
                        agua y repórtalo para facilitar su atención.
                    </p>

                    <a href="reportes/crear.php">
                        Hacer reporte →
                    </a>

                </article>


                <!-- TARJETA 4 -->
                <article class="tarjeta">

                    <div class="tarjeta-icono amarillo">
                        🏆
                    </div>

                    <h3>Participa en retos</h3>

                    <p>
                        Completa retos de corto, mediano y largo plazo
                        mientras desarrollas mejores hábitos ambientales.
                    </p>

                    <a href="retos/index.php">
                        Ver retos →
                    </a>

                </article>

            </div>

        </section>


        <!-- SECCIÓN MOTIVACIONAL -->
        <section class="seccion-azul">

            <div class="contenido-azul">

                <div>
                    <span class="etiqueta-clara">💧 UNIDOS POR EL AGUA</span>

                    <h2>
                        El cambio empieza
                        <span>con nosotros.</span>
                    </h2>

                    <p>
                        No se trata solamente de ahorrar agua. Se trata de
                        aprender a utilizarla responsablemente y motivar a
                        nuestra comunidad a hacer lo mismo.
                    </p>
                </div>

                <div class="gota-secundaria">
                    💧
                </div>

            </div>

        </section>


        <!-- RETOS -->
        <section class="seccion retos-inicio">

            <div class="titulo-seccion">

                <span>PARTICIPA</span>

                <h2>
                    Conviértete en parte del <strong>cambio</strong>
                </h2>

                <p>
                    Participa en nuestros retos y acumula puntos mientras
                    realizas acciones que ayudan al medio ambiente.
                </p>

            </div>


            <div class="retos">

                <div class="reto">

                    <span class="reto-numero">01</span>

                    <div>
                        <h3>Reto de 7 días</h3>
                        <p>
                            Practica diferentes hábitos de ahorro de agua
                            durante una semana.
                        </p>
                    </div>

                    <span class="reto-duracion">Corto plazo</span>

                </div>


                <div class="reto">

                    <span class="reto-numero">02</span>

                    <div>
                        <h3>Reto de 30 días</h3>
                        <p>
                            Mantén buenos hábitos de consumo responsable
                            durante un mes.
                        </p>
                    </div>

                    <span class="reto-duracion">Mediano plazo</span>

                </div>


                <div class="reto">

                    <span class="reto-numero">03</span>

                    <div>
                        <h3>Comunidad sostenible</h3>
                        <p>
                            Participa durante varios meses en diferentes
                            actividades ambientales.
                        </p>
                    </div>

                    <span class="reto-duracion">Largo plazo</span>

                </div>

            </div>


            <div class="centrar">

                <a href="retos/index.php" class="btn-principal">
                    Ver todos los retos
                </a>

            </div>

        </section>


        <!-- LLAMADO FINAL -->
        <section class="llamado">

            <div>

                <span>🌎 TU PARTICIPACIÓN ES IMPORTANTE</span>

                <h2>
                    ¿Listo para comenzar?
                </h2>

                <p>
                    Únete a AquaComunidad y empieza a hacer parte de una
                    comunidad comprometida con el cuidado del agua.
                </p>

                <a href="usuario/registro.php" class="btn-blanco">
                    Crear mi cuenta
                </a>

            </div>

        </section>

    </main>


    <!-- PIE DE PÁGINA -->
    <footer>

        <div class="footer-contenido">

            <div class="footer-logo">

                <div class="logo">
                    <span class="logo-icon">💧</span>

                    <div>
                        <h2>AquaComunidad</h2>
                        <span>Cada gota cuenta</span>
                    </div>
                </div>

                <p>
                    Plataforma comunitaria para promover la conservación
                    y el uso responsable del agua.
                </p>

            </div>


            <div class="footer-columna">

                <h3>Plataforma</h3>

                <a href="index.php">Inicio</a>
                <a href="consejos/index.php">Consejos</a>
                <a href="retos/index.php">Retos</a>
                <a href="reportes/crear.php">Reportar daño</a>

            </div>


            <div class="footer-columna">

                <h3>Comunidad</h3>

                <a href="usuario/login.php">Iniciar sesión</a>
                <a href="usuario/registro.php">Registrarse</a>
                <a href="consejos/index.php">Reciclaje</a>

            </div>

        </div>


        <div class="footer-final">

            <p>
                © 2026 AquaComunidad | Cada gota cuenta 💧
            </p>

        </div>

    </footer>

</body>
</html>