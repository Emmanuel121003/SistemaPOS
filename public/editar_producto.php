<?php
require '../includes/header.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "Producto no encontrado.";
    header("Location: inventario.php");
    exit();
}

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM productos WHERE id = $id");

if ($result->num_rows === 0) {
    $_SESSION['error'] = "Producto no encontrado.";
    header("Location: inventario.php");
    exit();
}

$producto = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Editar Producto</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
<div class="container">
    <h2 class="text-center">Editar Producto</h2>
    <form action="../actions/actualizar_producto.php" method="POST">
        <input type="hidden" name="id" value="<?= $producto['id']; ?>">

        <label for="nombre" class="form-label">Nombre del Producto</label>
        <input type="text" name="nombre" id="nombre" class="form-control" value="<?= $producto['nombre']; ?>" required>

        <label for="descripcion" class="form-label">Descripción</label>
        <textarea name="descripcion" id="descripcion" class="form-control" rows="3" required><?= $producto['descripcion']; ?></textarea>

        <label for="precio" class="form-label">Precio</label>
        <input type="number" name="precio" id="precio" class="form-control" step="0.01" value="<?= $producto['precio']; ?>" required>

        <label for="stock" class="form-label">Stock</label>
        <input type="number" name="stock" id="stock" class="form-control" min="1" value="<?= $producto['stock']; ?>" required>

        <button type="submit" class="btn btn-success w-100 mt-3">Actualizar Producto</button>
    </form>
</div>
</body>
</html>
