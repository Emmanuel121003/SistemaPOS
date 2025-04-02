<?php
include '../config/auth.php'; 

if (!isLoggedIn()) {
    $_SESSION['error'] = "Debe iniciar sesión.";
    header("Location: ../public/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];

    $stmt = $conn->prepare("UPDATE productos SET nombre=?, descripcion=?, precio=?, stock=? WHERE id=?");
    $stmt->bind_param("ssdii", $nombre, $descripcion, $precio, $stock, $id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Producto actualizado correctamente.";
    } else {
        $_SESSION['error'] = "Error al actualizar producto.";
    }

    header("Location: ../public/inventario.php");
    exit();
}
?>
