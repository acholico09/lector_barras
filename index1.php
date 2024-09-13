<?php
// Verificar si los datos han sido enviados
$parametros = ['nombre_producto', 'codigo_producto', 'marca', 'precio', 'cantidad'];

// Inicializar variables
foreach ($parametros as $param) {
    $$param = filter_input(INPUT_POST, $param, FILTER_SANITIZE_STRING) ?: 'No disponible';
}

// Debug: Verificar datos recibidos
header('Content-Type: application/json');
echo json_encode([
    'nombre_producto' => $nombre_producto,
    'codigo_producto' => $codigo_producto,
    'marca' => $marca,
    'precio' => $precio,
    'cantidad' => $cantidad
]);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<form action="lector.php" method="POST">
<select class="form-select" aria-label="Default select example" id="sucursal"  name="sucursal"  required>
  <option selected>Seleccione una opción</option>
  <option value="1">Tepic 1</option>
  <option value="2">Tepic 3</option>
</select>

<input type="submit" class="btn btn-primary" value="Escanear" >
</form>

<div class="container mt-4">
        <h1>Detalles del Producto</h1>
        
        <!-- Crear la tabla con Bootstrap -->
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Campo</th>
                    <th>Valor</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Nombre del Producto</td>
                    <td><?php echo htmlspecialchars($nombre_producto); ?></td>
                </tr>
                <tr>
                    <td>Código del Producto</td>
                    <td><?php echo htmlspecialchars($codigo_producto); ?></td>
                </tr>
                <tr>
                    <td>Marca</td>
                    <td><?php echo htmlspecialchars($marca); ?></td>
                </tr>
                <tr>
                    <td>Precio</td>
                    <td><?php echo htmlspecialchars($precio); ?></td>
                </tr>
                <tr>
                    <td>Cantidad</td>
                    <td><?php echo htmlspecialchars($cantidad); ?></td>
                </tr>
            </tbody>
        </table>
    </div>


</body>
</html>