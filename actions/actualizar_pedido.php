<?php
include '../config/auth.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['id'], $_POST['estado'])) {
        $_SESSION['error'] = "⚠️ Faltan datos requeridos.";
        header("Location: ../public/pedidos.php");
        exit();
    }

    $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
    $estado = sanitizeInput($_POST['estado']);

    if ($id === false || $id <= 0) {
        $_SESSION['error'] = "⚠️ ID de pedido no válido.";
        header("Location: ../public/pedidos.php");
        exit();
    }

    $estados_permitidos = ['Pendiente', 'Proceso', 'Aprobado', 'Produccion', 'Listo para entregar', 'Entregado', 'Cancelado'];
    if (!in_array($estado, $estados_permitidos)) {
        $_SESSION['error'] = "⚠️ Estado no válido.";
        header("Location: ../public/pedidos.php");
        exit();
    }

    if (!isLoggedIn() || (!isAdmin() && !isDiseñador())) {
        $_SESSION['error'] = "⛔ No tienes permiso para realizar esta acción.";
        header("Location: ../public/pedidos.php");
        exit();
    }

    // Verificar conexión a la BD
    if (!$conn) {
        $_SESSION['error'] = "⚠️ Error de conexión a la base de datos.";
        header("Location: ../public/pedidos.php");
        exit();
    }

    // Registrar depuración
    $file = '../logs/error_log.txt';
    file_put_contents($file, "Intentando actualizar estado del pedido ID $id a '$estado'\n", FILE_APPEND);

    // Ejecutar actualización
    $stmt = $conn->prepare("UPDATE pedidos SET estado=? WHERE id=?");
    $stmt->bind_param("si", $estado, $id);

    if (!$stmt->execute()) {
        $error_msg = "⚠️ Error al actualizar el estado: " . $stmt->error . "\n";
        file_put_contents($file, $error_msg, FILE_APPEND);
        $_SESSION['error'] = $error_msg;
    } else {
        file_put_contents($file, "✅ Estado actualizado correctamente.\n", FILE_APPEND);
        $_SESSION['success'] = "✅ Estado actualizado correctamente.";
    }

    $stmt->close();
    $conn->close();

    header("Location: ../public/pedidos.php");
    exit();
}
?>
