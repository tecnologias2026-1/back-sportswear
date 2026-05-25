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
require_once __DIR__ . '/../../database/conection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->usuario_id)) {

        // Calcula el total desde el carrito del usuario
        $carrito = verCarritoModel($data->usuario_id);

        if (empty($carrito)) {
            http_response_code(400);
            echo json_encode(["mensaje" => "El carrito está vacío"]);
            exit();
        }

        $total = array_reduce($carrito, function($sum, $item) {
            return $sum + $item['subtotal'];
        }, 0);

        $resultado = crearPedidoModel($data->usuario_id, $total);

        if ($resultado) {
            // Reducir el stock de cada producto
            global $conn;
            foreach ($carrito as $item) {
                $sql = "UPDATE productos SET stock = stock - ? WHERE id = ? AND stock >= ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("iii", $item['cantidad'], $item['producto_id'], $item['cantidad']);
                $stmt->execute();
            }

            // Vacía el carrito después de crear el pedido
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