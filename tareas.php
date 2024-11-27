<?php
// Iniciar sesión
session_start();

// Verificar si el usuario está autenticado y tiene el rol de Profesor
if (!isset($_SESSION['correo']) || $_SESSION['tipo'] !== 'Profesor') {
    header("Location: login.php");
    exit();
}

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pag_asistencias";

$conn = mysqli_connect($servername, $username, $password, $dbname);

// Verificar la conexión
if (!$conn) {
    die("Conexión fallida: " . mysqli_connect_error());
}

// Obtener los periodos disponibles desde la base de datos
$queryPeriodos = "SELECT * FROM periodos ORDER BY descripcion ASC";
$periodosResult = mysqli_query($conn, $queryPeriodos);

// Obtener las materias disponibles desde la base de datos
$queryMaterias = "SELECT * FROM materias ORDER BY nombre ASC";
$materiasResult = mysqli_query($conn, $queryMaterias);

// Guardar la tarea en la base de datos
if (isset($_POST['saveTarea'])) {
    $periodo_id = $_POST['periodo'];
    $materia_id = $_POST['materia'];
    $fecha_entrega = $_POST['fechaEntrega'];
    $nombre = $_POST['tareaNombre'];

    // Validar si la fecha de entrega es en el futuro
    if (strtotime($fecha_entrega) < strtotime(date('Y-m-d'))) {
        $error_message = "La fecha de entrega no puede ser en el pasado.";
    } else {
        $query = "INSERT INTO tareas (periodo_id, materia_id, fecha_entrega, nombre) 
                  VALUES ('$periodo_id', '$materia_id', '$fecha_entrega', '$nombre')";

        if (mysqli_query($conn, $query)) {
            header("Location: tareas.php");
            exit();
        } else {
            echo "Error al guardar la tarea: " . mysqli_error($conn);
        }
    }
}

// Editar o eliminar tarea
if (isset($_POST['editTarea']) || isset($_POST['deleteTarea'])) {
    $tarea_id = $_POST['tarea_id'];

    // Editar tarea
    if (isset($_POST['editTarea'])) {
        $periodo_id = $_POST['periodo'];
        $materia_id = $_POST['materia'];
        $fecha_entrega = $_POST['fechaEntrega'];
        $nombre = $_POST['tareaNombre'];

        // Validar si la fecha de entrega es en el futuro
        if (strtotime($fecha_entrega) < strtotime(date('Y-m-d'))) {
            $error_message = "La fecha de entrega no puede ser en el pasado.";
        } else {
            $query = "UPDATE tareas SET periodo_id = '$periodo_id', materia_id = '$materia_id', 
                      fecha_entrega = '$fecha_entrega', nombre = '$nombre' WHERE id = '$tarea_id'";

            if (mysqli_query($conn, $query)) {
                header("Location: tareas.php");
                exit();
            } else {
                echo "Error al actualizar la tarea: " . mysqli_error($conn);
            }
        }
    }

    // Eliminar tarea
    if (isset($_POST['deleteTarea'])) {
        $query = "DELETE FROM tareas WHERE id = '$tarea_id'";

        if (mysqli_query($conn, $query)) {
            header("Location: tareas.php");
            exit();
        } else {
            echo "Error al eliminar la tarea: " . mysqli_error($conn);
        }
    }
}

// Obtener todas las tareas desde la base de datos
$queryTareas = "SELECT t.id, t.nombre, t.fecha_entrega, p.descripcion AS periodo, m.nombre AS materia
                FROM tareas t
                JOIN periodos p ON t.periodo_id = p.id
                JOIN materias m ON t.materia_id = m.id";
$tareasResult = mysqli_query($conn, $queryTareas);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles_prof.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/tareas.css">
    <title>Registro de Tareas</title>
</head>
<body>

    <!-- Barra de Navegación -->
    <div class="navbar">
        <a href="profesor_dashboard.php"><i class="fas fa-home"></i> Inicio</a>
        <a href="agregar_alumno.php"><i class="fas fa-user-plus"></i> Agregar Alumnos</a>
        <a href="registrar_asistencia.php"><i class="fas fa-calendar-check"></i> Registrar Asistencias</a>
        <a href="informe_asistencia.php"><i class="fas fa-chart-line"></i> Informe de Asistencias</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
    </div>

    <div class="container">
        <h2>Registro de Tareas</h2>

        <!-- Mostrar mensaje de error si la fecha es en el pasado -->
        <?php if (isset($error_message)) { ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php } ?>

        <!-- Botón para abrir el modal -->
        <button id="newTaskBtn" class="button">Nueva Tarea</button>
    </div>

    <!-- Modal -->
    <div id="taskModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Registrar Tarea</h3>
            <form method="POST" action="tareas.php">
                <input type="hidden" name="tarea_id" id="tarea_id">

                <label for="periodo">Periodo:</label>
                <select name="periodo" id="periodo" required>
                    <option value="">Selecciona un periodo</option>
                    <?php
                    mysqli_data_seek($periodosResult, 0); // Reiniciar el puntero para reutilizar
                    while ($periodo = mysqli_fetch_assoc($periodosResult)) {
                        echo "<option value='{$periodo['id']}'>{$periodo['descripcion']}</option>";
                    }
                    ?>
                </select>

                <label for="materia">Materia:</label>
                <select name="materia" id="materia" required>
                    <option value="">Selecciona una materia</option>
                    <?php
                    mysqli_data_seek($materiasResult, 0); // Reiniciar el puntero para reutilizar
                    while ($materia = mysqli_fetch_assoc($materiasResult)) {
                        echo "<option value='{$materia['id']}'>{$materia['nombre']}</option>";
                    }
                    ?>
                </select>

                <label for="fechaEntrega">Fecha de Entrega:</label>
                <input type="date" name="fechaEntrega" id="fechaEntrega" required>

                <label for="tareaNombre">Nombre de la Tarea:</label>
                <input type="text" name="tareaNombre" id="tareaNombre" required>

                <button type="submit" name="saveTarea" class="save">Guardar</button>
                <button type="button" id="cancelTaskBtn" class="cancel">Cancelar</button>
            </form>
        </div>
    </div>

    <!-- Tabla de Tareas -->
    <table class="table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Periodo</th>
                <th>Materia</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($tarea = mysqli_fetch_assoc($tareasResult)) {
                echo "<tr>
                        <td>{$tarea['nombre']}</td>
                        <td>{$tarea['periodo']}</td>
                        <td>{$tarea['materia']}</td>
                        <td>{$tarea['fecha_entrega']}</td>
                        <td>
                            <button class='editBtn' data-id='{$tarea['id']}'>Editar</button>
                            <form method='POST' action='tareas.php' style='display:inline;'>
                                <input type='hidden' name='tarea_id' value='{$tarea['id']}'>
                                <button type='submit' name='deleteTarea' class='deleteBtn'>Eliminar</button>
                            </form>
                        </td>
                    </tr>";
            }
            ?>
        </tbody>
    </table>

    <script>
        // Lógica del modal
        const modal = document.getElementById("taskModal");
        const newTaskBtn = document.getElementById("newTaskBtn");
        const cancelTaskBtn = document.getElementById("cancelTaskBtn");
        const closeModalBtn = document.querySelector(".close");

        const editButtons = document.querySelectorAll(".editBtn");
        const tareaIdField = document.getElementById("tarea_id");
        const periodoField = document.getElementById("periodo");
        const materiaField = document.getElementById("materia");
        const fechaField = document.getElementById("fechaEntrega");
        const nombreField = document.getElementById("tareaNombre");

        // Mostrar modal
        newTaskBtn.onclick = () => {
            tareaIdField.value = "";
            periodoField.selectedIndex = 0;
            materiaField.selectedIndex = 0;
            fechaField.value = "";
            nombreField.value = "";
            modal.style.display = "block";
        };

        // Editar tarea
        editButtons.forEach((btn) => {
            btn.onclick = (e) => {
                const tareaRow = e.target.closest("tr");
                const cells = tareaRow.children;

                tareaIdField.value = e.target.dataset.id;
                periodoField.value = cells[1].dataset.id;
                materiaField.value = cells[2].dataset.id;
                fechaField.value = cells[3].innerText;
                nombreField.value = cells[0].innerText;

                modal.style.display = "block";
            };
        });

        // Cerrar modal
        cancelTaskBtn.onclick = closeModalBtn.onclick = () => {
            modal.style.display = "none";
        };

        // Cerrar modal si se hace clic fuera
        window.onclick = (e) => {
            if (e.target == modal) modal.style.display = "none";
        };
    </script>
</body>
</html>
