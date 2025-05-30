<?php
require '../config/auth.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST["cliente"], $_POST["detalles"], $_POST["cantidad"], $_POST["total"], $_POST["fecha_entrega"])) {
        $_SESSION["error"] = "Todos los campos son obligatorios.";
        header("Location: ../public/ventas.php");
        exit();
    }

    $cliente = sanitizeInput($_POST["cliente"]);
    $detalles = sanitizeInput($_POST["detalles"]);
    $cantidad = filter_var($_POST["cantidad"], FILTER_VALIDATE_INT);
    $total = filter_var($_POST["total"], FILTER_VALIDATE_FLOAT);
    $abono = isset($_POST["abono"]) ? filter_var($_POST["abono"], FILTER_VALIDATE_FLOAT) : 0;
    $fecha_entrega = $_POST["fecha_entrega"];
    $estado = "Pendiente";

    // Validaciones
    if ($cantidad === false || $cantidad <= 0) {
        $_SESSION["error"] = "La cantidad debe ser un número entero positivo.";
        header("Location: ../public/ventas.php");
        exit();
    }

    if ($total === false || $total < 0) {
        $_SESSION["error"] = "El total debe ser un número válido y positivo.";
        header("Location: ../public/ventas.php");
        exit();
    }

    if ($abono === false || $abono < 0) {
        $_SESSION["error"] = "El abono no puede ser negativo.";
        header("Location: ../public/ventas.php");
        exit();
    }

    if ($abono > $total) {
        $_SESSION["error"] = "El abono no puede ser mayor al total.";
        header("Location: ../public/ventas.php");
        exit();
    }

    if (strtotime($fecha_entrega) < strtotime(date("Y-m-d"))) {
        $_SESSION["error"] = "La fecha de entrega no puede ser anterior a hoy.";
        header("Location: ../public/ventas.php");
        exit();
    }

    // Determinar el estado del pedido
    if ($abono == $total) {
        $estado = "Pagado";
    }

    // Preparar y ejecutar la consulta
    $stmt = $conn->prepare("INSERT INTO pedidos (cliente, detalles, cantidad, total, abono, fecha_entrega, estado) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiddss", $cliente, $detalles, $cantidad, $total, $abono, $fecha_entrega, $estado);

    if ($stmt->execute()) {
        $_SESSION["success"] = "✅ Pedido registrado con éxito.";
    } else {
        $_SESSION["error"] = "⚠️ Error al registrar el pedido.";
    }

    $stmt->close();
    $conn->close();

    header("Location: ../public/ventas.php");
    exit();
}

?>
