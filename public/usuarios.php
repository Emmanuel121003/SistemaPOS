<?php
require '../includes/header.php';
if (isset($_SESSION['message'])) {
    echo $_SESSION['message'];
    unset($_SESSION['message']);
}
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'administrador') {
    header("Location: login.php"); exit();
}
if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    $_SESSION['error'] = "Debe iniciar sesión para acceder.";
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>

    <title>Gestión de Usuarios</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
<div class="container">
    <h2 class="text-center">Registrar Usuario</h2>
    
    <form action="../actions/usuarios_action.php" method="POST">
        <input type="text" name="nombre" class="form-control" placeholder="Nombre Completo" required>
        <input type="text" name="usuario" class="form-control" placeholder="Usuario" required>
        <input type="password" name="contraseña" class="form-control" placeholder="Contraseña" required>
        
        <select name="rol" class="form-select">
            <option value="administrador">Administrador</option>
            <option value="ventas">Ventas</option>
            <option value="diseñador">Diseñador</option>
        </select>
        
        <button type="submit" class="btn btn-primary w-100 mt-2">Registrar</button>
    </form>
</div>

<div class="container mt-4">
    <h2 class="text-center">Lista de Usuarios</h2>
    
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Nombre</th>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Estado</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT * FROM usuarios");
            while ($row = $result->fetch_assoc()) {
                $estado = $row['estado'] == 'activo' ? 'Activo ✅' : 'Inactivo ❌';
                $botonTexto = $row['estado'] == 'activo' ? 'Deshabilitar' : 'Habilitar';
                $botonClase = $row['estado'] == 'activo' ? 'btn-danger' : 'btn-success';
                $nuevoEstado = $row['estado'] == 'activo' ? 'inactivo' : 'activo';
                
                echo "<tr>
                        <td>{$row['nombre']}</td>
                        <td>{$row['usuario']}</td>
                        <td>{$row['rol']}</td>
                        <td>{$estado}</td>
                        <td>
                            <a href='../actions/cambiar_estado_usuario.php?id={$row['id']}&estado={$nuevoEstado}' 
                               class='btn {$botonClase} btn-sm'>
                                {$botonTexto}
                            </a>
                        </td>
                      </tr>";
            }
            ?>
        </tbody>
    </table>
</div>
</body>
</html>
