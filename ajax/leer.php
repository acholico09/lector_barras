<?php  
// Verificar si se ha enviado el código
if (isset($_POST['codigo']) && isset($_POST['sucursal'])) {
    $codigo = $_POST['codigo'];
    $id_sucursal = 1;
    // Incluir archivos de configuración de la base de datos
    if ($id_sucursal == 1 || $id_sucursal == 3) {
        require('../config/db.php');
        require('../config/conexion.php');
    } elseif ($id_sucursal == 4) {
        require('../config/db2.php');
        require('../config/conexion.php');
    } else {
        die('No se encontró la sucursal seleccionada');
    }

    // Preparar la consulta SQL
    $query = mysqli_query($con, "
        SELECT prod.nombre_producto, prod.codigo_producto, prod.marca_producto, prod.precio, inv.cantidad_producto
        FROM inventeraio as inv
        INNER JOIN productos AS prod
        ON inv.id_producto = prod.id_producto
        WHERE inv.id_sucursal = '$id_sucursal'
        AND inv.codigo_producto = '$codigo'
    ");

    // Verificar si la consulta se ejecutó correctamente
    if (!$query) {
        die('Error en la consulta: ' . mysqli_error($con));
    }

    // Obtener los resultados de la consulta
    $row = mysqli_fetch_assoc($query);

    // Verificar si se obtuvo algún resultado
    if ($row) {
        $response = array(
            'nombre_producto' => $row['nombre_producto'] ?? 'No disponible',
            'codigo_producto' => $row['codigo_producto'] ?? 'No disponible',
            'marca' => $row['marca_producto'] ?? 'No disponible',
            'precio' => $row['precio'] ?? 'No disponible',
            'cantidad' => $row['cantidad_producto'] ?? 'No disponible'
        );
    } else {
        $response = array(
            'error' => 'No se encontraron resultados para el código proporcionado.'
        );
    }

    // Enviar la respuesta en formato JSON
    header('Content-Type: application/json');
    echo json_encode($response);

} else {
    echo json_encode(['error' => 'Código o sucursal no proporcionados']);
}
?>
