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
if (!isLoggedIn() || (!isAdmin() && !isDiseñador())) {
    $_SESSION['error'] = "⛔ No tienes permiso para realizar esta acción.";
    header("Location: login.php");
    exit();
}

// Obtener pedidos con estado "Pendiente"
$stmt = $conn->prepare("SELECT id, cliente, fecha_entrega FROM pedidos WHERE estado = 'Pendiente' ORDER BY fecha_entrega ASC");
if (!$stmt->execute()) {
    die("Error en la consulta: " . $stmt->error);
}
$pedidos = $stmt->get_result();

// Verificar si hay pedidos
if ($pedidos->num_rows === 0) {
    echo "<p>No hay pedidos pendientes.</p>";
}

// Obtener inventario disponible
$result = $conn->query("SELECT * FROM productos WHERE stock > 0");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Seleccionar Artículos</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container">
        <h2 class="text-center">Seleccionar Artículos para un Pedido</h2>

        <form action="../actions/asignar_productos_pedido.php" method="POST">
            <label for="pedido">Selecciona un Pedido:</label>
            <select name="pedido_id" id="pedido" required>
                <option value="">-- Seleccionar Pedido --</option>
                <?php while ($pedido = $pedidos->fetch_assoc()): ?>
                    <option value="<?= $pedido['id']; ?>">
                        Pedido #<?= $pedido['id']; ?> - Cliente: <?= htmlspecialchars($pedido['cliente']); ?> - Entrega: <?= $pedido['fecha_entrega']; ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <h3>Selecciona los Productos:</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Cantidad</th>
                        <th>Seleccionar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['nombre']); ?></td>
                            <td><?= htmlspecialchars($row['descripcion']); ?></td>
                            <td>$<?= number_format($row['precio'], 2); ?></td>
                            <td><?= $row['stock']; ?></td>
                            <td>
                                <input type="number" name="cantidad[<?= $row['id']; ?>]" min="1" max="<?= $row['stock']; ?>">
                            </td>
                            <td>
                                <input type="checkbox" name="productos[]" value="<?= $row['id']; ?>">
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <button type="submit" class="btn btn-success w-100">Asignar Productos al Pedido</button>
        </form>
    </div>
</body>
</html>
