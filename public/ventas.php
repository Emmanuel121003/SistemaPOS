<?php
require '../includes/header.php';

// Mostrar mensajes de éxito o error
if (isset($_SESSION["success"])) {
    echo "<div class='alert alert-success text-center'>{$_SESSION['success']}</div>";
    unset($_SESSION["success"]);
}

if (isset($_SESSION["error"])) {
    echo "<div class='alert alert-danger text-center'>{$_SESSION['error']}</div>";
    unset($_SESSION["error"]);
}

// Verificar sesión
if (!isLoggedIn()) {
    $_SESSION['error'] = "Debe iniciar sesión para acceder.";
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Pedido</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
<div class="container">
        <h2>Registrar Pedido</h2>
        <form action="../actions/ventas_action.php" method="POST">
            <div class="mb-3">
                <label for="cliente" class="form-label">Cliente:</label>
                <input type="text" name="cliente" id="cliente" class="form-control" placeholder="Nombre del cliente" required>
            </div>

            <div class="mb-3">
                <label for="detalles" class="form-label">Detalles del Pedido:</label>
                <textarea name="detalles" id="detalles" class="form-control" rows="3" placeholder="Descripción del pedido" required></textarea>
            </div>

            <div class="mb-3">
                <label for="cantidad" class="form-label">Cantidad:</label>
                <input type="number" name="cantidad" id="cantidad" class="form-control" min="1" required>
            </div>

            <div class="mb-3">
                <label for="total" class="form-label">Precio Total:</label>
                <input type="number" name="total" id="total" class="form-control" step="0.01" placeholder="0.00" required>
            </div>

            <div class="mb-3">
                <label for="abono" class="form-label">Abono:</label>
                <input type="number" name="abono" id="abono" class="form-control" step="0.01" placeholder="0.00">
            </div>

            <div class="mb-3">
                <label for="fecha_entrega" class="form-label">Fecha de Entrega:</label>
                <input type="date" name="fecha_entrega" id="fecha_entrega" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="estado" class="form-label">Estado del Pedido:</label>
                <select name="estado" id="estado" class="form-control">
                    <option value="Pendiente">Pendiente</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary w-100">Registrar Pedido</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
