<?php
require '../includes/header.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

// Obtener todos los pedidos ordenados por fecha de entrega más cercana
$stmt = $conn->prepare("SELECT * FROM pedidos ORDER BY FIELD(estado, 'Pendiente', 'Proceso', 'Aprobado', 'Produccion', 'Listo para entregar', 'Entregado', 'Cancelado'), fecha_entrega ASC");
$stmt->execute();
$pedidos = $stmt->get_result();

$pedidosPorEstado = [];
while ($pedido = $pedidos->fetch_assoc()) {
    $pedidosPorEstado[$pedido['estado']][] = $pedido;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2 class="mt-4 text-center">Pedidos</h2>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success']; ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error']; ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php foreach ($pedidosPorEstado as $estado => $listaPedidos): ?>
            <h4 class="estado-title"><?= strtoupper($estado) ?></h4>
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Fecha Entrega</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($listaPedidos as $pedido): ?>
                        <tr>
                            <td><?= $pedido['id']; ?></td>
                            <td><?= htmlspecialchars($pedido['cliente']); ?></td>
                            <td><?= date("d/m/Y", strtotime($pedido['fecha_entrega'])); ?></td>
                            <td>$<?= number_format($pedido['total'], 2); ?></td>
                            <td>
                                <a href="ver_pedido.php?id=<?= $pedido['id']; ?>" class="btn btn-info btn-sm">Ver Detalles</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endforeach; ?>
    </div>
</body>
</html>
