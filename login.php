<?php
$servidor = 'localhost';
$usuario = 'root';
$clave = '';
$baseDatos = 'pag_asistencias';
$enlace = mysqli_connect($servidor, $usuario, $clave, $baseDatos);

session_start();

if (isset($_POST['login'])) {
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];
    $tipo = $_POST['tipo'];

    // Consulta para obtener el usuario y verificar la contraseña
    $consulta = "SELECT * FROM usuarios WHERE correo='$correo' AND contrasena='$contrasena' AND tipo='$tipo'";
    $resultado = mysqli_query($enlace, $consulta);

    if (mysqli_num_rows($resultado) > 0) {
        $_SESSION['correo'] = $correo;
        $_SESSION['tipo'] = $tipo;

        // Redirige al dashboard correspondiente
        if ($tipo == 'Profesor') {
            header("Location: profesor_dashboard.php");
        } else {
            header("Location: alumno_dashboard.php");
        }
        exit;
    } else {
        echo "<script>alert('Usuario, contraseña o tipo incorrecto. Por favor, intente nuevamente.'); window.location.href = 'login.php';</script>";
    }
}


?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login | Página de Asistencias</title>
    <link rel="stylesheet" href="css/styles_login.css">
</head>
<body>
    <header>
        <h1>Página de Sistema de Asistencias Escolar</h1>
    </header>

    <div class="container">
        <!-- Área para la imagen que cambia -->
        <div class="imagen-container">
            <img id="imagenRol" src="images/user.png" alt="Imagen por Defecto" />
        </div>

        <form action="login.php" method="post">
            <input type="email" name="correo" placeholder="Correo" required>
            <input type="password" name="contrasena" placeholder="Contraseña" required>
            
            <select name="tipo" required onchange="cambiarImagen()">
                <option value="">Selecciona tu rol</option>
                <option value="Profesor">Profesor</option>
                <option value="Alumno">Alumno</option>
            </select>
            
            <input type="submit" name="login" value="Iniciar sesión">
            <h3>¿No tienes una cuenta?</h3>
            <button class="btn-registrar"><a href="registro.php">Regístrate</a></button>
        </form>
    </div>

    <footer>
        <p>© 2024 Sistema de Asistencias Estudiantiles</p>
    </footer>

    <!-- Referencia al archivo JavaScript externo -->
    <script src="js/script.js"></script>
    
</body>
</html>

