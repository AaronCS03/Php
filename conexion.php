<?php
$conexion = new mysqli("localhost", "root", "", "BD_FacturacionPruebas");

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
?>
