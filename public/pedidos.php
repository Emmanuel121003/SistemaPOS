<?php
require '../includes/header.php';

// Verifica si el usuario ha iniciado sesión
if (!isLoggedIn()) {
    $_SESSION['error'] = "Debe iniciar sesión para acceder.";
    header("Location: login.php");
    exit();
}

// Verifica si el usuario tiene permiso (solo administradores y diseñadores)
if (!isset($_SESSION['rol']) || ($_SESSION['rol'] != 'administrador' && $_SESSION['rol'] != 'diseñador')) {
    $_SESSION['error'] = "No tienes permisos para acceder a esta página.";
    header("Location: login.php");
    exit();
}

// Muestra mensajes de éxito o error
if (isset($_SESSION["success"])) {
    echo "<div class='alert alert-success text-center'>{$_SESSION['success']}</div>";
    unset($_SESSION["success"]);
}

if (isset($_SESSION["error"])) {
    echo "<div class='alert alert-danger text-center'>{$_SESSION['error']}</div>";
    unset($_SESSION["error"]);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Gestión de Pedidos</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
<div class="container mt-4">
    <h2 class="text-center">Lista de Pedidos</h2>
    <table class="table table-bordered table-striped">
    <thead class="table-dark">
    <tr>
            <th>Cliente</th>
            <th>Fecha de Entrega</th>
            <th>Detalles</th>
            <th>Estado</th>
        </tr>
        </thead>
        <tbody>
        <?php
        require '../config/db.php'; // Asegura que la conexión esté disponible

        $result = $conn->query("SELECT * FROM pedidos");

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['cliente']}</td>
                        <td>{$row['fecha_entrega']}</td>
                        <td>{$row['detalles']}</td>
                        <td>
                            <select class='form-select estado' data-id='{$row['id']}'>
                                <option value='Pendiente' ".($row['estado'] == 'Pendiente' ? 'selected' : '').">Pendiente</option>
                                <option value='Proceso' ".($row['estado'] == 'Proceso' ? 'selected' : '').">Proceso</option>
                                <option value='Aprobado' ".($row['estado'] == 'Aprobado' ? 'selected' : '').">Aprobado</option>
                                <option value='Produccion' ".($row['estado'] == 'Produccion' ? 'selected' : '').">Produccion</option>
                                <option value='Listo para entregar' ".($row['estado'] == 'Listo para entregar' ? 'selected' : '').">Listo para entregar</option>
                                <option value='Entregado' ".($row['estado'] == 'Entregado' ? 'selected' : '').">Entregado</option>
                                <option value='Cancelado' ".($row['estado'] == 'Cancelado' ? 'selected' : '').">Cancelado</option>
                            </select>
                        </td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='4' class='text-center'>No hay pedidos registrados.</td></tr>";
        }

        $conn->close();
        ?>
        </tbody>
    </table>
</div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function(){
            $(".estado").change(function(){
                let id = $(this).data("id");
                let estado = $(this).val();
                
                $.post("../actions/actualizar_pedido.php", { id: id, estado: estado }, function(response){
                    if (response.trim() === "success") {
                        alert("Estado actualizado correctamente.");
                    }
                });
            });
        });
        
    </script>
    
</body>
</html>
