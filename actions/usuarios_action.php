<?php
include '../config/auth.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = sanitizeInput($_POST['nombre']);
    $usuario = sanitizeInput($_POST['usuario']);
    $contraseña = password_hash(sanitizeInput($_POST['contraseña']), PASSWORD_BCRYPT);
    $rol = sanitizeInput($_POST['rol']);

    // Verificar si el usuario ya existe
    $stmt_check = $conn->prepare("SELECT id FROM usuarios WHERE usuario = ?");
    $stmt_check->bind_param("s", $usuario);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        $_SESSION['message'] = "<div class='alert alert-warning text-center'>⚠️ El usuario ya existe. ⚠️</div>";
    } else {
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, usuario, contraseña, rol) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nombre, $usuario, $contraseña, $rol);

        if ($stmt->execute()) {
            $_SESSION['message'] = "<div class='alert alert-success text-center'>✅ Usuario registrado correctamente. ✅</div>";
        } else {
            $_SESSION['message'] = "<div class='alert alert-danger text-center'>⚠️ Error al registrar el usuario. ⚠️</div>";
        }
    }

    $stmt_check->close();
    $stmt->close();
    $conn->close();
    
    header("Location: ../public/usuarios.php");
    exit();
}
?>
