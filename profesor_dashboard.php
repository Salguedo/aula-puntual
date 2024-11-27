<?php 
session_start();

// Verificar si el usuario está autenticado y tiene el rol de Profesor
if (!isset($_SESSION['correo']) || $_SESSION['tipo'] !== 'Profesor') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Profesor</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/styles_prof.css">
    <style>
        /* Estilos generales */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc; /* Gris claro para el fondo */
            margin: 0;
            padding: 0;
        }

        /* Encabezado */
        .header {
            background-color: #007bff; /* Azul principal */
            color: white;
            text-align: center;
            padding: 20px;
            font-size: 24px;
            font-weight: bold;
            border-bottom: 3px solid #0056b3; /* Azul oscuro */
        }

        /* Barra de navegación */
        .navbar {
            display: flex;
            justify-content: center;
            background-color: #343a40; /* Gris oscuro */
            padding: 15px;
            border-bottom: 3px solid #444;
        }

        .navbar a {
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            text-align: center;
            margin: 0 15px;
            border-radius: 5px;
            font-size: 18px;
            transition: background-color 0.3s ease;
        }

        .navbar a:hover {
            background-color: #007bff; /* Azul claro en hover */
        }

        /* Contenedor principal */
        .container {
            margin: 20px;
            padding: 30px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Estilos del contenedor de dashboard */
        .dashboard-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            margin-top: 20px;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            width: 100%;
        }

        /* Estilos de las tarjetas */
        .card {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%; /* Asegura que todas las tarjetas tengan la misma altura */
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card-icon {
            font-size: 40px;
            margin-bottom: 15px;
        }

        .card-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .card-description {
            font-size: 16px;
            margin-bottom: 15px;
            text-align: left;
            line-height: 1.6;
            flex-grow: 1;
        }

        /* Ajuste de los botones */
        .card button {
            background-color: #007bff;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 5px; /* Reducir el margen superior */
            transition: background-color 0.3s ease;
        }

        .card button:hover {
            background-color: #0056b3;
        }

    </style>
</head>
<body>

    <div class="header">
        Bienvenido Profesor
    </div>

    <!-- Barra de Navegación -->
    <div class="navbar">
        <a href="agregar_alumno.php"><i class="fas fa-user-plus"></i>Agregar Alumnos</a>
        <a href="registrar_asistencia.php"><i class="fas fa-calendar-check"></i>Registrar Asistencias</a>
        <a href="informe_asistencia.php"><i class="fas fa-chart-line"></i>Informe de Asistencias</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Cerrar Sesión</a>
    </div>

    <div class="container">
        <!-- Contenedor Principal -->
        <div class="dashboard-container">
            <div class="dashboard-grid">
                
                <!-- Tarjeta de Períodos -->
                <div class="card">
                    <div class="card-icon">📅</div>
                    <div class="card-title">Períodos</div>
                    <div class="card-description">
                        Accede y administra los períodos académicos actuales y pasados.
                    </div>
                    <button onclick="window.location.href='periodos.php'">Ver Períodos</button>
                </div>

                <!-- Tarjeta de Materias -->
                <div class="card">
                    <div class="card-icon">📚</div>
                    <div class="card-title">Materias</div>
                    <div class="card-description">
                        Consulta y gestiona las materias que enseñas.
                    </div>
                    <button onclick="window.location.href='materias.php'">Ver Materias</button>
                </div>

                <!-- Tarjeta de Tareas -->
                <div class="card">
                    <div class="card-icon">📝</div>
                    <div class="card-title">Tareas</div>
                    <div class="card-description">
                        Asigna, consulta y gestiona las tareas de los estudiantes.
                    </div>
                    <button onclick="window.location.href='tareas.php'">Ver Tareas</button>
                </div>

            </div>
        </div>
    </div>

</body>
</html>
