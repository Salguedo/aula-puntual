<?php
$servidor = 'localhost';
$usuario = 'root';
$clave = '';
$baseDatos = 'pag_asistencias';
$enlace = mysqli_connect($servidor, $usuario, $clave, $baseDatos);

if (isset($_POST['registro'])) {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $tipo = $_POST['tipo'];
    $contrasena = $_POST['contrasena'];

    $insertarDatos = "INSERT INTO usuarios (nombre, correo, telefono, tipo, contrasena) 
                      VALUES ('$nombre','$correo','$telefono','$tipo','$contrasena')";
    
    mysqli_query($enlace, $insertarDatos);
    echo "Registro exitoso";
}

// registro.php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Aquí va el código para insertar los datos en la base de datos

    // Supongamos que el registro fue exitoso
    $registroExitoso = true;  // Ajusta esta variable según el éxito del registro

    if ($registroExitoso) {
        echo "<script>alert('Registro exitoso. Ahora puedes iniciar sesión'); window.location.href = 'login.php';</script>";
    } else {
        echo "<script>alert('Error en el registro. Por favor, intenta nuevamente'); window.location.href = 'registro.php';</script>";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Registro | Página de Asistencias </title>
    <link rel="stylesheet" href="css/styles_registro.css">
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

        <!-- Formulario de registro -->
        <form name="registroForm" action="registro.php" method="post" onsubmit="return validarFormulario(event)">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="email" name="correo" placeholder="Correo" required>
            <input type="text" name="telefono" placeholder="Teléfono (9 dígitos)" required maxlength="9" pattern="[0-9]{9}">
            <input type="password" name="contrasena" placeholder="Contraseña (mínimo 6 caracteres)" required minlength="6">
            
            <select name="tipo" required onchange="cambiarImagen()">
                <option value="">Selecciona tu rol</option>
                <option value="Profesor">Profesor</option>
                <option value="Alumno">Alumno</option>
            </select>
            
            <input type="submit" name="registro" value="Registrarse">
            
            <h3>¿Ya tienes tu cuenta?</h3>
            <button class="btn-iniciar-sesion"><a href="login.php">Inicia Sesión</a></button>
        </form>
    </div>

    <footer>
        <p>© 2024 Sistema de Asistencias Estudiantiles</p>
    </footer>

    <!-- Referencia al archivo JavaScript externo -->
    <script src="js/script.js"></script>

</body>
</html>

