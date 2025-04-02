<?php
require '../config/auth.php';
// Verificar si el usuario está autenticado
if (!isLoggedIn()) {
    $_SESSION['error'] = "Debe iniciar sesión para acceder.";
    header("Location: ../public/login.php");
    exit();
}

// Verificar si se ha recibido un ID válido
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "ID de producto no válido.";
    header("Location: ../public/inventario.php");
    exit();
}

$id = intval($_GET['id']); // Convertir el ID a entero para mayor seguridad

// Preparar la consulta para eliminar el producto
$stmt = $conn->prepare("DELETE FROM productos WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $_SESSION['success'] = "Producto eliminado correctamente.";
} else {
    $_SESSION['error'] = "Error al eliminar el producto.";
}

// Redirigir de vuelta a la página de inventario
header("Location: ../public/inventario.php");
exit();
