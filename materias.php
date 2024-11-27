<?php
session_start();

// Verificar si el usuario está autenticado y tiene el rol de Profesor
if (!isset($_SESSION['correo']) || $_SESSION['tipo'] !== 'Profesor') {
    header("Location: login.php");
    exit();
}

// Conexión a la base de datos
include("conexion.php");

// Mensajes de estado
$message = "";

// Verificar si se envió un formulario para agregar, editar o eliminar
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Si el formulario tiene un campo 'materia', es para agregar una nueva materia
    if (isset($_POST['materia'])) {
        $materia = $_POST['materia']; // Nombre de la materia
        
        // Lógica para guardar la materia en la base de datos
        $query = "INSERT INTO materias (nombre) VALUES ('$materia')";
        if (mysqli_query($conn, $query)) {
            $message = "<p class='text-green-600'>Materia guardada exitosamente: $materia</p>";
        } else {
            $message = "<p class='text-red-600'>Error al guardar la materia: " . mysqli_error($conn) . "</p>";
        }
    }

    // Editar materia
    if (isset($_POST['editar_id'])) {
        $id = $_POST['editar_id'];
        $materia = $_POST['materia']; // Nueva descripción

        $query = "UPDATE materias SET nombre='$materia' WHERE id='$id'";
        if (mysqli_query($conn, $query)) {
            $message = "<p class='text-green-600'>Materia actualizada exitosamente.</p>";
        } else {
            $message = "<p class='text-red-600'>Error al actualizar la materia: " . mysqli_error($conn) . "</p>";
        }
    }

    // Eliminar materia
    if (isset($_POST['eliminar_id'])) {
        $id = $_POST['eliminar_id'];

        $query = "DELETE FROM materias WHERE id='$id'";
        if (mysqli_query($conn, $query)) {
            $message = "<p class='text-green-600'>Materia eliminada exitosamente.</p>";
        } else {
            $message = "<p class='text-red-600'>Error al eliminar la materia: " . mysqli_error($conn) . "</p>";
        }
    }
}

// Obtener las materias existentes (si las hay)
$query = "SELECT * FROM materias";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Materias</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome para los íconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Barra de navegación */
        .navbar {
            display: flex;
            justify-content: space-between; /* Distribuye los elementos uniformemente */
            background-color: #343a40;
            padding: 15px 0; /* Relleno solo en la parte superior e inferior */
            border-bottom: 3px solid #444;
            width: 100vw; /* Asegura que ocupe el ancho completo de la ventana */
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            box-sizing: border-box;
        }

        /* Estilo de los enlaces dentro de la barra de navegación */
        .navbar a {
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            text-align: center;
            border-radius: 5px;
            font-size: 18px;
            transition: background-color 0.3s ease;
        }

        /* Efecto hover para los enlaces */
        .navbar a:hover {
            background-color: #007bff;
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Barra de Navegación -->
    <div class="navbar">
        <a href="profesor_dashboard.php"><i class="fas fa-home"></i> Inicio</a>
        <a href="agregar_alumno.php"><i class="fas fa-user-plus"></i> Agregar Alumnos</a>
        <a href="registrar_asistencia.php"><i class="fas fa-calendar-check"></i> Registrar Asistencias</a>
        <a href="informe_asistencia.php"><i class="fas fa-chart-line"></i> Informe de Asistencias</a>
        <a href="perfil.php"><i class="fas fa-user"></i> Perfil</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
    </div>

    <!-- Contenedor Principal -->
    <div class="container mx-auto p-6 mt-24">
        <!-- Título -->
        <h1 class="text-3xl font-semibold mb-4 text-purple-700">Materias</h1>
        
        <!-- Mostrar Mensaje -->
        <div id="messageContainer" class="mb-4">
            <?php echo $message; ?>
        </div>
        
        <!-- Botón para abrir el modal de nueva materia -->
        <button 
            class="px-5 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-all duration-300 mb-4"
            onclick="document.getElementById('modal').classList.remove('hidden')">
            Nueva Materia
        </button>

        <!-- Tabla de Materias -->
        <table class="table-auto w-full border-collapse mb-4 bg-white shadow-md rounded-lg">
            <thead>
                <tr>
                    <th class="border-b py-3 px-5 text-left text-gray-700">ID</th>
                    <th class="border-b py-3 px-5 text-left text-gray-700">Materia</th>
                    <th class="border-b py-3 px-5 text-left text-gray-700">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr class="hover:bg-gray-100 transition-all">
                        <td class="border-b py-3 px-5"><?= $row['id'] ?></td>
                        <td class="border-b py-3 px-5"><?= $row['nombre'] ?></td>
                        <td class="border-b py-3 px-5 flex space-x-3">
                            <!-- Botón de editar -->
                            <button 
                                class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-all duration-300"
                                onclick="openEditModal(<?= $row['id'] ?>, '<?= $row['nombre'] ?>')">
                                Editar
                            </button>

                            <!-- Botón de eliminar -->
                            <form action="" method="POST" class="inline" onsubmit="return confirmDelete()">
                                <input type="hidden" name="eliminar_id" value="<?= $row['id'] ?>">
                                <button 
                                    type="submit" 
                                    class="px-4 py-2 text-white bg-red-600 rounded-lg hover:bg-red-700 transition-all duration-300">
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal de Registro de Materia -->
    <div id="modal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-lg w-96">
            <div class="p-6">
                <h3 class="text-lg font-medium mb-4" id="modalTitle">Registrar Materia</h3>
                
                <!-- Formulario para Registrar o Editar Materia -->
                <form action="" method="POST">
                    <input type="hidden" name="editar_id" id="editId">
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2" for="materia">Materia</label>
                        <input 
                            type="text" 
                            name="materia" 
                            id="materia"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
                            placeholder="Nombre de la Materia"
                            required
                        >
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button 
                            type="button" 
                            class="px-4 py-2 text-gray-600 hover:text-gray-800 rounded-md"
                            onclick="document.getElementById('modal').classList.add('hidden')">
                            Cancelar
                        </button>
                        <button 
                            type="submit" 
                            class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-all duration-300">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Abre el modal de editar materia
        function openEditModal(id, nombre) {
            document.getElementById('modal').classList.remove('hidden');
            document.getElementById('modalTitle').textContent = 'Editar Materia';
            document.getElementById('materia').value = nombre;
            document.getElementById('editId').value = id;
        }

        // Confirmar eliminación
        function confirmDelete() {
            return confirm('¿Estás seguro de que deseas eliminar esta materia?');
        }
    </script>
</body>
</html>
