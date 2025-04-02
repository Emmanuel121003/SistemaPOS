<?php
require '../config/auth.php';

if (!isLoggedIn() || (!isAdmin() && !isDiseñador())) {
    $_SESSION['error'] = "⛔ No tienes permiso para realizar esta acción.";
    header("Location: ../public/diseñar_pedidos.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["pedido_id"]) && isset($_POST["productos"])) {
    $pedido_id = intval($_POST["pedido_id"]);
    $productos = $_POST["productos"];
    $cantidades = $_POST["cantidad"];

    foreach ($productos as $producto_id) {
        $cantidad = isset($cantidades[$producto_id]) ? intval($cantidades[$producto_id]) : 0;

        if ($cantidad > 0) {
            // Insertar en la tabla intermedia
            $stmt = $conn->prepare("INSERT INTO pedido_productos (pedido_id, producto_id, cantidad) VALUES (?, ?, ?)");
            $stmt->bind_param("iii", $pedido_id, $producto_id, $cantidad);
            if ($stmt->execute()) {
                $_SESSION['success'] = "✅ Productos asignados al pedido.";
            } else {
                $_SESSION['error'] = "⚠️ No se seleccionaron productos.";
            }
        
            $stmt->execute();
            $stmt->close();

            header("Location: ../public/diseñar_pedidos.php");
    
            // Reducir stock del inventario
            $conn->query("UPDATE productos SET stock = stock - $cantidad WHERE id = $producto_id");
        }
    }
    }