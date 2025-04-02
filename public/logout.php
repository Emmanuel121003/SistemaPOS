<?php
session_start();
include '../config/auth.php';

if (isLoggedIn()) {
    registrarActividad("Cierre de sesión");
}

session_destroy();
header("Location: login.php");
exit();