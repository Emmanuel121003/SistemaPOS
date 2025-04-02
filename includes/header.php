<?php
require_once '../config/auth.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php">Creaciones SATIN</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
            <?php if (isAdmin() || isGerente()): ?>
                <li class="nav-item"><a class="nav-link" href="ventas.php">Ventas</a></li>
                <?php endif; ?>
                <?php if (isAdmin() || isDiseñador()): ?>
                <li class="nav-item"><a class="nav-link" href="pedidos.php">Pedidos</a></li>
                <li class="nav-item"><a class="nav-link" href="diseñar_pedidos.php">Diseñar</a></li>
                <?php endif; ?>
                <?php if (isAdmin()): ?>
                <li class="nav-item"><a class="nav-link" href="inventario.php">Inventario</a></li>
                <?php endif; ?>
                <?php if (isAdmin()): ?>
                    <li class="nav-item"><a class="nav-link" href="usuarios.php">Usuarios</a></li>
                <?php endif; ?>

                <li class="nav-item"><a class="nav-link" href="logout.php">Cerrar Sesión</a></li>
            </ul>
        </div>
    </div>
</nav>
