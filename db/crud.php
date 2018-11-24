<?php
include 'delivery\db\config.php';

$inputCourier = isset($_POST['dropDownCourier']) ? mysql_real_escape_string($_POST['dropDownCourier']) : "";
$courierId = isset($_POST['courierId']) ? mysql_real_escape_string($_POST['courierId']) : "";
$orderId = filter_input(INPUT_GET, 'orderId');
$action = filter_input(INPUT_GET, 'action');

global $response;

switch ($action) {
    case NULL: 
        echo '';
        break;
    case 'deleteOrderByCourier':
        echo deleteOrderByCourier($orderId);
        break;
}

function deleteOrderByCourier($orderId) {
    $idOrder = intval($orderId);
    $sql = "delete from orders where id = '$idOrder'";
    $queryResult = mysql_query($sql);

    if ($queryResult) {
        $response = array("status" => 1, "msg" => "Order id = '$orderId' deleted");
    } else {
        $response = array("status" => 0, "msg" => "Error order deleting");
    }
    return $response;
}
