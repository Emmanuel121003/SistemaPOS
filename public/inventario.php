<?php
require '../includes/header.php';

if (isset($_SESSION["success"])) {
    echo "<div class='alert alert-success text-center'>{$_SESSION['success']}</div>";
    unset($_SESSION["success"]);
}

if (isset($_SESSION["error"])) {
    echo "<div class='alert alert-danger text-center'>{$_SESSION['error']}</div>";
    unset($_SESSION["error"]);
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
    <title>Gestión de Inventario</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body>
<div class="container">
    <h2 class="text-center mb-4">Agregar Producto</h2>
    
    <form action="../actions/inventario_action.php" method="POST">
        <label for="nombre" class="form-label">Nombre del Producto</label>
        <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Ej. Camisa de algodón" required>

        <label for="descripcion" class="form-label">Descripción</label>
        <textarea name="descripcion" id="descripcion" class="form-control" rows="3" placeholder="Ej. Camisa 100% algodón, disponible en varios colores." required></textarea>

        <label for="precio" class="form-label">Precio</label>
        <input type="number" name="precio" id="precio" class="form-control" placeholder="Ej. 25.99" step="0.01" required>

        <label for="stock" class="form-label">Stock</label>
        <input type="number" name="stock" id="stock" class="form-control" placeholder="Ej. 50" min="1" required>

        <button type="submit" class="btn btn-primary w-100 mt-3">Agregar Producto</button>
    </form>
</div>

<div class="container mt-4">
    <h2 class="text-center">Lista de Productos</h2>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT * FROM productos");
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['nombre']}</td>
                    <td>{$row['descripcion']}</td>
                    <td>$ {$row['precio']}</td>
                    <td>{$row['stock']}</td>
                    <td>
                        <a href='editar_producto.php?id={$row['id']}' class='btn btn-warning btn-sm'>Editar</a>
                        <a href='../actions/eliminar_producto.php?id={$row['id']}' class='btn btn-danger btn-sm'onclick= return confirm('¿Estás seguro de que deseas eliminar este producto?')>Eliminar</a>
                    </td>
                  </tr>";
            }
            ?>
        </tbody>
    </table>
</div>
</body>

</html>
