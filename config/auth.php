<?php
session_start();
include 'db.php'; // Conexión a la base de datos
define('SESSION_TIMEOUT', 1800); // 30 minutos

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > SESSION_TIMEOUT)) {
    registrarActividad("Sesión expirada por inactividad");
    session_unset();
    session_destroy();
    header("Location: login.php?error=sesion_expirada");
    exit();
}
$_SESSION['LAST_ACTIVITY'] = time();
function isLoggedIn() {
    return isset($_SESSION['id']);
}

function isAdmin() {
    return isset($_SESSION['rol']) && $_SESSION['rol'] === 'administrador';
}

function isGerente() {
    return isset($_SESSION['rol']) && $_SESSION['rol'] === 'ventas';
}

function isDiseñador() {
    return isset($_SESSION['rol']) && $_SESSION['rol'] === 'diseñador';
}

function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

/**
 * Registra acciones de los usuarios en la base de datos.
 */
function registrarActividad($accion) {
    global $conn;
    if (isset($_SESSION['id'])) {
        $usuario_id = $_SESSION['id'];
        $stmt = $conn->prepare("INSERT INTO logs (usuario_id, accion) VALUES (?, ?)");
        $stmt->bind_param("is", $usuario_id, $accion);
        $stmt->execute();
    }
}
?>
