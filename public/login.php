<?php
include '../config/auth.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = sanitizeInput($_POST["usuario"]);
    $contraseña = sanitizeInput($_POST["contraseña"]);

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE usuario=?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($contraseña, $row["contraseña"])) {
            $_SESSION["id"] = $row["id"];
            $_SESSION["usuario"] = $row["usuario"];
            $_SESSION["rol"] = $row["rol"];

            registrarActividad("Inicio de sesión exitoso");

            // 🔹 Redirección a dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            $message = "<div class='alert alert-danger text-center'>⚠️ Usuario o contraseña incorrectos.</div>";
            registrarActividad("Intento de inicio de sesión fallido (contraseña incorrecta)");
            header("Location: login.php?error=credenciales_invalidas");
            exit();
        }
    } else {
        $message = "<div class='alert alert-danger text-center'>⚠️ Usuario o contraseña incorrectos.</div>";
        registrarActividad("Intento de inicio de sesión fallido (usuario no encontrado)");
        header("Location: login.php?error=usuario_no_existe");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - POS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="d-flex align-items-center justify-content-center vh-100 bg-light">
    <div class="card p-4 shadow-lg">
        <h2 class="text-center">Iniciar Sesión</h2>

        <!-- 🔹 Mostrar mensajes de error -->
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger text-center">
                <?php 
                if ($_GET['error'] == 'credenciales_invalidas') {
                    echo "Usuario o contraseña incorrectos.";
                } elseif ($_GET['error'] == 'usuario_no_existe') {
                    echo "El usuario no existe.";
                } elseif ($_GET['error'] == 'sesion_expirada') {
                    echo "Tu sesión ha expirado por inactividad.";
                }
                ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <input type="text" class="form-control" name="usuario" placeholder="Usuario" required>
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" name="contraseña" placeholder="Contraseña" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Ingresar</button>
        </form>
    </div>
</body>
</html>
