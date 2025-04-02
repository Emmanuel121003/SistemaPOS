<?php
require '../config/auth.php';

// Verificar si el usuario es administrador
if (!isLoggedIn() || $_SESSION['rol'] != 'administrador') {
    $_SESSION['error'] = "Acceso denegado.";
    header("Location: ../public/usuarios.php");
    exit();
}

// Validar que se recibió un ID y un nuevo estado
if (!isset($_GET['id']) || !isset($_GET['estado'])) {
    $_SESSION['error'] = "Solicitud inválida.";
    header("Location: ../public/usuarios.php");
    exit();
}

$id = intval($_GET['id']);
$nuevoEstado = ($_GET['estado'] === 'activo') ? 'activo' : 'inactivo';

// Preparar la consulta
$stmt = $conn->prepare("UPDATE usuarios SET estado = ? WHERE id = ?");
$stmt->bind_param("si", $nuevoEstado, $id);

if ($stmt->execute()) {
    $_SESSION['success'] = "Estado del usuario actualizado correctamente.";
} else {
    $_SESSION['error'] = "Error al actualizar el estado del usuario.";
}

// Redirigir de nuevo a la gestión de usuarios
header("Location: ../public/usuarios.php");
exit();
