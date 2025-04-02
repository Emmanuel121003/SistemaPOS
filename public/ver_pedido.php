<?php
require '../includes/header.php';
if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: pedidos.php");
    exit();
}

$pedido_id = $_GET['id'];

// Obtener detalles del pedido
$stmt = $conn->prepare("SELECT * FROM pedidos WHERE id=?");
$stmt->bind_param("i", $pedido_id);
$stmt->execute();
$pedido = $stmt->get_result()->fetch_assoc();

if (!$pedido) {
    header("Location: pedidos.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles del Pedido</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container">
        <h2 class="mt-4">Detalles del Pedido #<?= $pedido['id']; ?></h2>

        <p><strong>Cliente:</strong> <?= htmlspecialchars($pedido['cliente']); ?></p>
        <p><strong>Fecha de Entrega:</strong> <?= $pedido['fecha_entrega']; ?></p>
        <p><strong>Total:</strong> $<?= number_format($pedido['total'], 2); ?></p>
        <p><strong>Abono:</strong> $<?= number_format($pedido['abono'], 2); ?></p>
        <p><strong>Detalles:</strong> <?= nl2br(htmlspecialchars($pedido['detalles'])); ?></p>

        <a href="pedidos.php" class="btn btn-secondary">Todos los pedidos</a>
    </div>
</body>
</html>