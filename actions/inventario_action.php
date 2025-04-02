<?php
include '../config/auth.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['nombre'], $_POST['descripcion'], $_POST['precio'], $_POST['stock'])) {
        $_SESSION['error'] = "Todos los campos son obligatorios.";
        header("Location: ../public/inventario.php");
        exit();
    }

    $nombre = sanitizeInput($_POST['nombre']);
    $descripcion = sanitizeInput($_POST['descripcion']);
    $precio = filter_var($_POST['precio'], FILTER_VALIDATE_FLOAT);
    $stock = filter_var($_POST['stock'], FILTER_VALIDATE_INT);

    if ($precio === false || $precio < 0) {
        $_SESSION['error'] = "El precio debe ser un número válido y positivo.";
        header("Location: ../public/inventario.php");
        exit();
    }

    if ($stock === false || $stock < 0) {
        $_SESSION['error'] = "El stock debe ser un número entero válido y positivo.";
        header("Location: ../public/inventario.php");
        exit();
    }

    // Preparar la consulta
    $stmt = $conn->prepare("INSERT INTO productos (nombre, descripcion, precio, stock) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssdi", $nombre, $descripcion, $precio, $stock);

    if ($stmt->execute()) {
        $_SESSION['success'] = "✅ Producto agregado correctamente.";
    } else {
        $_SESSION['error'] = "⚠️ Error al agregar el producto.";
    }

    $stmt->close();
    $conn->close();

    header("Location: ../public/inventario.php");
    exit();
}
?>