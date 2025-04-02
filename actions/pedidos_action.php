<?php
include '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['cliente'], $_POST['fecha_entrega'], $_POST['estado'])) {
        $_SESSION['error'] = "Todos los campos son obligatorios.";
        header("Location: ../public/pedidos.php");
        exit();
    }

    $cliente = sanitizeInput($_POST['cliente']);
    $fecha_entrega = sanitizeInput($_POST['fecha_entrega']);
    $estado = sanitizeInput($_POST['estado']);

    // Preparar la consulta
    $stmt = $conn->prepare("INSERT INTO pedidos (cliente, fecha_entrega, estado) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $cliente, $fecha_entrega, $estado);

    if ($stmt->execute()) {
        $_SESSION['success'] = "✅ Pedido registrado correctamente.";
    } else {
        $_SESSION['error'] = "⚠️ Error al registrar el pedido.";
    }

    $stmt->close();
    $conn->close();

    header("Location: ../public/pedidos.php");
    exit();
}
?>
