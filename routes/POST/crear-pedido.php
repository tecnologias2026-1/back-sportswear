<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../../models/pedidos.php';
require_once __DIR__ . '/../../models/carrito.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->usuario_id)) {

        $carrito = verCarritoModel($data->usuario_id);

        if (empty($carrito)) {
            http_response_code(400);
            echo json_encode(["mensaje" => "El carrito está vacío"]);
            exit();
        }
$subtotal = array_reduce($carrito, function($sum, $item) {
    return $sum + $item['subtotal'];
}, 0);
$total = round($subtotal * 1.19);

        $nombre       = $data->nombre ?? '';
        $apellido     = $data->apellido ?? '';
        $telefono     = $data->telefono ?? '';
        $direccion    = $data->direccion ?? '';
        $ciudad       = $data->ciudad ?? '';
        $departamento = $data->departamento ?? '';
        $codigo_postal = $data->codigo_postal ?? '';
        $pais         = $data->pais ?? 'Colombia';
        $metodo_pago  = $data->metodo_pago ?? 'contraentrega';

        $resultado = crearPedidoModel(
            $data->usuario_id, $total,
            $nombre, $apellido, $telefono,
            $direccion, $ciudad, $departamento,
            $codigo_postal, $pais, $metodo_pago
        );

        if ($resultado) {
            // Reducir stock
            foreach ($carrito as $item) {
                reducirStockModel($item['producto_id'], $item['cantidad']);
            }

            // Vaciar carrito
            vaciarCarritoModel($data->usuario_id);

            http_response_code(201);
            echo json_encode(["mensaje" => "Pedido creado exitosamente", "total" => $total]);
        } else {
            http_response_code(503);
            echo json_encode(["mensaje" => "No se pudo crear el pedido"]);
        }

    } else {
        http_response_code(400);
        echo json_encode(["mensaje" => "Datos incompletos"]);
    }

} else {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
}
?>