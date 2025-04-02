<?php
require '../config/auth.php';

if (!isLoggedIn() || (!isAdmin() && !isDiseñador())) {
    $_SESSION['error'] = "⛔ No tienes permiso para realizar esta acción.";
    header("Location: ../public/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["productos"])) {
    $productos = $_POST["productos"];
    $cantidades = $_POST["cantidad"];
    
    foreach ($productos as $producto_id) {
        $cantidad = isset($cantidades[$producto_id]) ? intval($cantidades[$producto_id]) : 0;

        if ($cantidad > 0) {
            $stmt = $conn->prepare("INSERT INTO pedidos (cliente, detalles, cantidad, total, fecha_entrega, estado) VALUES (?, ?, ?, ?, ?, 'Pendiente')");
            $cliente = $_SESSION['usuario'];  // Asigna el diseñador como cliente
            $detalles = "Pedido de inventario";
            $precio = $conn->query("SELECT precio FROM productos WHERE id = $producto_id")->fetch_assoc()['precio'];
            $total = $precio * $cantidad;
            $fecha_entrega = date("Y-m-d", strtotime("+3 days"));

            $stmt->bind_param("ssdds", $cliente, $detalles, $cantidad, $total, $fecha_entrega);
            $stmt->execute();
            $stmt->close();

            // Reducir stock del inventario
            $conn->query("UPDATE productos SET stock = stock - $cantidad WHERE id = $producto_id");
        }
    }

    $_SESSION["success"] = "Pedido realizado con éxito.";
    header("Location: ../public/diseñar_pedidos.php");
    exit();
} else {
    $_SESSION["error"] = "No se seleccionaron productos.";
    header("Location: ../public/diseñar_pedidos.php");
    exit();
}
