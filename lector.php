<?php if (isset($_POST['sucursal'])) {
    $sucursal = $_POST['sucursal'];
} ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lectura de código de barras</title>
    <link rel="stylesheet" href="style.css">
    <style>
      #contenedor video {
        max-width: 100%;
        width: 100%;
      }
      #contenedor {
        max-width: 100%;
        position: relative;
      }
      canvas {
        max-width: 100%;
      }
      canvas.drawingBuffer {
        position: absolute;
        top: 0;
        left: 0;
      }
    </style>
</head>
<body>
    <h1>Escanear producto de sucursal</h1>
    <div id="contenedor"></div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="js/quagga.min.js"></script>
    <script src="js/lector.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const id_sucursal = "<?php echo $sucursal; ?>";

            Quagga.init({
                inputStream: {
                    constraints: {
                        width: 1920,
                        height: 1080,
                    },
                    name: "Live",
                    type: "LiveStream",
                    target: document.querySelector('#contenedor')
                },
                decoder: {
                    readers: ["ean_reader"]
                }
            }, function (err) {
                if (err) {
                    console.log(err);
                    return;
                }
                console.log("Initialization finished. Ready to start");
                Quagga.start();
            });

            Quagga.onDetected(function (data) {
                var parametros = {
                    codigo: data.codeResult.code,
                    sucursal: id_sucursal
                };

                $.ajax({
                    type: "POST",
                    url: "./ajax/leer.php",
                    data: parametros,
                    success: function(response) {
                        console.log('Respuesta del servidor:', response);
                        try {
                            let data = JSON.parse(response); // Asegúrate de que la respuesta sea JSON
                            if (data.error) {
                                console.error("Error desde el servidor:", data.error);
                                return;
                            }

                            let nombre_producto = encodeURIComponent(data.nombre_producto);
                            let codigo_producto = encodeURIComponent(data.codigo_producto);
                            let marca = encodeURIComponent(data.marca);
                            let precio = encodeURIComponent(data.precio);
                            let cantidad = encodeURIComponent(data.cantidad);

                            window.location.href = `index.php?nombre_producto=${nombre_producto}&codigo_producto=${codigo_producto}&marca=${marca}&precio=${precio}&cantidad=${cantidad}`;
                        } catch (e) {
                            console.error("Error al analizar JSON:", e);
                            console.error("Respuesta del servidor:", response);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error("Error en la solicitud AJAX: " + textStatus, errorThrown);
                    }
                });
            });

            Quagga.onProcessed(function (result) {
                var drawingCtx = Quagga.canvas.ctx.overlay,
                    drawingCanvas = Quagga.canvas.dom.overlay;

                if (result) {
                    if (result.boxes) {
                        drawingCtx.clearRect(0, 0, parseInt(drawingCanvas.getAttribute("width")), parseInt(drawingCanvas.getAttribute("height")));
                        result.boxes.filter(function (box) {
                            return box !== result.box;
                        }).forEach(function (box) {
                            Quagga.ImageDebug.drawPath(box, { x: 0, y: 1 }, drawingCtx, { color: "green", lineWidth: 2 });
                        });
                    }

                    if (result.box) {
                        Quagga.ImageDebug.drawPath(result.box, { x: 0, y: 1 }, drawingCtx, { color: "#00F", lineWidth: 2 });
                    }

                    if (result.codeResult && result.codeResult.code) {
                        Quagga.ImageDebug.drawPath(result.line, { x: 'x', y: 'y' }, drawingCtx, { color: 'red', lineWidth: 3 });
                    }
                }
            });
        });
    </script>
</body>
</html>
