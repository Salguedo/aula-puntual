<?php 
session_start();

// Verificar si el usuario está autenticado y tiene el rol de Profesor
if (!isset($_SESSION['correo']) || $_SESSION['tipo'] !== 'Profesor') {
    header("Location: login.php");
    exit();
}

// Incluir archivos comunes
include_once "header.php";
?>
<link rel="stylesheet" href="css/agregar_alumno.css">
<!-- Barra de Navegación -->
<div class="navbar">
    <a href="profesor_dashboard.php"><i class="fas fa-home"></i> Inicio</a> <!-- Nuevo enlace de Inicio -->
    <a href="agregar_alumno.php"><i class="fas fa-user-plus"></i> Agregar Alumnos</a>
    <a href="registrar_asistencia.php"><i class="fas fa-calendar-check"></i> Registrar Asistencias</a>
    <a href="informe_asistencia.php"><i class="fas fa-chart-line"></i> Informe de Asistencias</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
</div>

<!-- Contenedor Principal -->
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0 rounded-4 bg-white">
                <div class="card-header text-white text-center p-4 rounded-top" style="background-color: #004d99;">
                   <h2 class="fw-bold">Agregar Nuevo Alumno</h2>
                </div>
                <div class="card-body">
                    <form action="guardar_alumno.php" method="POST">
                        <div class="mb-4">
                            <label for="nombre" class="form-label fs-4 text-dark">Nombre del Alumno:</label>
                            <input type="text" id="nombre" name="nombre" class="form-control fs-5 p-3 border-2 border-dark" required placeholder="Ingrese el nombre completo">
                        </div>

                        <div class="mb-4">
                            <label for="curso" class="form-label fs-4 text-dark">Curso:</label>
                            <input type="text" id="curso" name="curso" class="form-control fs-5 p-3 border-2 border-dark" required placeholder="Ingrese el nombre del curso">
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="submit" class="btn btn-primary btn-lg w-48 fs-5 p-3 rounded-3 transition-transform">Guardar Alumno</button>
                            <a href="lista_alumnos.php" class="btn btn-primary btn-lg w-48 fs-5 p-3 rounded-3 transition-transform">Ver Lista de Alumnos</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include_once "footer.php";
?>
