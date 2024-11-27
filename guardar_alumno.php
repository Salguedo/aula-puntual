<?php
session_start();

// Verificar si el usuario está autenticado y tiene el rol de Profesor
if (!isset($_SESSION['correo']) || $_SESSION['tipo'] !== 'Profesor') {
    header("Location: login.php");
    exit();
}

include_once "conexion.php"; // Asegúrate de que el archivo de conexión esté correcto

// Obtener los datos del formulario
$nombre = $_POST['nombre'];
$curso = $_POST['curso'];

// Insertar el nuevo alumno y su asistencia en la base de datos
$sql = "INSERT INTO alumnos_asistencias (nombre_alumno, curso, fecha, estado) VALUES ('$nombre', '$curso', CURDATE(), 'ausente')";
if ($conn->query($sql) === TRUE) {
    // Redirigir a la página de lista de alumnos
    header("Location: lista_alumnos.php");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>


