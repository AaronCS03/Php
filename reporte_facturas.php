<?php
include 'conexion.php';

$resultado = $conexion->query("SELECT * FROM factura");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Facturas</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <h2>Reporte de Facturas</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>DESCRIPCIÓN</th>
            <th>CATEGORÍA</th>
            <th>CANTIDAD</th>
            <th>PRECIO UNITARIO</th>
            <th>ITEBIS</th>
            <th>DESCUENTO</th>
            <th>TOTAL GENERAL</th>
            <th>ACCIONES</th>
        </tr>
        <?php while($fila = $resultado->fetch_assoc()): ?>
        <tr>
            <td><?= $fila['ID'] ?></td>
            <td><?= $fila['DESCRIPCION'] ?></td>
            <td><?= $fila['CATEGORIA'] ?></td>
            <td><?= $fila['CANTIDAD'] ?></td>   
            <td><?= number_format($fila['PRECIO_UNITARIO'], 2) ?></td>
            <td><?= number_format($fila['ITEBIS'], 2) ?></td>
            <td><?= number_format($fila['DESCUENTO'], 2) ?></td>
            <td><?= number_format($fila['TOTAL_GENERAL'], 2) ?></td>
            <td><a href="imprimir_pdf.php?id=<?= $fila['ID'] ?>" target="_blank">Imprimir PDF</a>| <a href="editar.php?id=<?= $fila['ID'] ?>">Editar</a> |
            <a href="eliminar.php?id=<?= $fila['ID'] ?>" onclick="return confirm('¿Estás seguro de eliminar esta factura?')">Eliminar</a></td>
            
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
