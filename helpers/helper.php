<?php

include '../db/config.php';
include '../dto/Order.php';
include '../dto/ProductInOrder.php';

//$conn = mysql_connect("localhost", "046797347_root", "2.718281828");
//mysql_select_db('standfer231_delivery', $conn);
//$conn = mysql_connect("localhost", "root", "root");
//mysql_select_db('delivery', $conn);


$post_action = filter_input(INPUT_POST, 'action');
$get_action = filter_input(INPUT_GET, 'action');
if ($post_action != NULL) {
    $action = $post_action;
}
if ($get_action != NULL) {
    $action = $get_action;
}

switch ($action) {
    case NULL: //если форма без параметров вызвана и action остался null
        echo '';
        break;
    case 'updateCourierLocation':
        echo updateCourierLocation();
        break;
    case 'updateDTOCourierLocation':
        echo updateDTOCourierLocation();
        break;
    case 'getCourierIdByCredentials':
        echo getCourierIdByCredentials();
        break;
    case 'getCourierDataById':
        echo getCourierDataById();
        break;
    case 'getOrdersUnassigned':
        echo getOrdersUnassigned();
        break;
    case 'assignOrdersToCourier':
        echo assignOrdersToCourier();
        break;
    case 'updateCourierData':
        echo updateCourierData();
        break;
    case 'getCourier':
        echo updateCourierData();
        break;
    default: //не описанные параметры
        echo 'э бля, чето я не знаю такого параметра';
        break;
}

function updateCourierLocation() {// not need now
    $courierRequest = json_decode($_POST['courier']);

    $courier = (isset($courierRequest->id)) ? $courierRequest : '';
    $id = $courier->id;
    $lat = $courier->currentCoordinate->lat;
    $lng = $courier->currentCoordinate->lng;

    $sql = "update locations set latitude = '$lat', longitude = '$lng' where id = '$id'";
    $queryResult = mysql_query($sql);

    //apply orders to courier->orders
    $qOrdersList = mysql_query("select * from locations_orders where idCourier = '$id' and isAssigned = 1");
    $ordersList = array();
    while ($qOrder = mysql_fetch_array($qOrdersList)) {
        extract($qOrder);
//                $courier->orders->address = $addressOrder;
//                $courier->orders->phoneNumber = $phoneNumber;
//                $courier->orders->cost = cost;
        $ordersList[] = array("idOrder" => $idOrder,
            "address" => $addressOrder,
            "lat" => $latOrder,
            "lng" => $lngOrder,
            "phoneNumber" => $phoneNumber,
            "cost" => $cost,
            "isDelivered" => $isDelivered,
            "idWorkplace" => $idWorkplace,
            "addressWorkplace" => $addressWorkplace,
            "latWorkPlace" => $latWorkPlace,
            "lngWorkPlace" => $lngWorkPlace,
            "priority" => $priority,
            "odd" => $odd,
            "notes" => $orderNotes,
            "idClient" => $idClient,
            "clientName" => $clientName,
            "clientPhone" => $clientPhone
        );
    }
    $json = array("infos" => $ordersList);


    if ($queryResult) {
        $response = array("status" => 1, "msg" => "Courier location updated");
    } else {
        $response = array("status" => 0, "msg" => "Error location updating");
    }



    return json_encode($json, JSON_UNESCAPED_UNICODE); //$response;
}

function getOrdersUnassigned() {
    $courierRequest = json_decode($_POST['courier']);

    $courier = (isset($courierRequest->id)) ? $courierRequest : '';
    $id = $courier->id;
    $lat = $courier->currentCoordinate->lat;
    $lng = $courier->currentCoordinate->lng;

    $queryResult = mysql_query("select * from locations_orders where idCourier = '$id' and (isAssigned is null or isAssigned = 0)");
    while ($rowOrder = mysql_fetch_array($queryResult)) {
        extract($rowOrder);
        $order = new Order($idOrder, $addressOrder, $latOrder, $lngOrder, $phoneNumber, $cost, $isAssigned, $isDelivered, $idWorkplace, $addressWorkplace, $latWorkPlace, $lngWorkPlace, $priority, $odd, $orderNotes, $idClient, $clientName, $clientPhone
        );
        getOrderProducts($order);
        $orders[] = $order;
    }
    $json = array("orders" => $orders);

    if ($queryResult) {
        $response = array("status" => 1, "msg" => "Courier location updated");
    } else {
        $response = array("status" => 0, "msg" => "Error location updating");
    }
    return json_encode($json, JSON_UNESCAPED_UNICODE);
}

function updateDTOCourierLocation() {
    $courierRequest = json_decode($_POST['courier']);

    $courier = (isset($courierRequest->id)) ? $courierRequest : '';
    $id = $courier->id;
    $lat = $courier->currentCoordinate->lat;
    $lng = $courier->currentCoordinate->lng;
    $idCourierLocation = getCourierLocationId($id);

    $sql = "update locations set latitude = '$lat', longitude = '$lng', update_ts = now() where id = '$idCourierLocation'";
    $queryResult = mysql_query($sql);
	saveLog($sql, $id);
    
    $gotInCycle = false;

    //apply orders to courier->orders
    $queryResult = mysql_query("select * from locations_orders where idCourier = '$id' and isAssigned = 1");//if assignment switch off
    //$queryResult = mysql_query("select * from locations_orders where idCourier = '$id'");//if assignment switch off
        while ($rowOrder = mysql_fetch_array($queryResult)) {
            extract($rowOrder);
            $order = new Order($idOrder, $addressOrder, $latOrder, $lngOrder, $phoneNumber, $cost, $isAssigned, $isDelivered, $idWorkplace, $addressWorkplace, $latWorkPlace, $lngWorkPlace, $priority, $odd, $orderNotes, $idClient, $clientName, $clientPhone
            );
            //$order->productsInOrder[] = getOrderProducts($order);
            getOrderProducts($order);
            $orders[] = $order; //add $order to $ordersList array
            $gotInCycle = true;
    }
        if ($gotInCycle == false) return null;
        $json = array("orders" => $orders);

        if ($queryResult) {
            $response = array("status" => 1, "msg" => "Courier location updated");
        } else {
            $response = array("status" => 0, "msg" => "Error location updating");
        }
    return json_encode($json, JSON_UNESCAPED_UNICODE);
}

function getOrderProducts(Order $order) {
    $id = $order->id;

    $sql = "select * from productsByOrders where idOrder = '$id'";
    $queryResult = mysql_query($sql);

    while ($rowProductInOrder = mysql_fetch_array($queryResult)) {
        extract($rowProductInOrder);
        $productInOrder = new ProductInOrder($idProductInOrder, $quantity, $order, $idProduct, $name, $price
        );

        //$productsInOrder[] = $productInOrder;
        $order->productsInOrder[] = $productInOrder;
    }
    //$arrayProductsInOrder = array("productsInOrder" => $productsInOrder);

    return $order->productsInOrder;
}

function getCourierIdByCredentials() {
    $courierRequest = json_decode($_POST['courier']);

    $courier = (isset($courierRequest->login)) ? $courierRequest : '';
    $login = $courier->login;
    $password = $courier->password;

    $result = mysql_query("select id from couriers where login = '$login' and password = '$password'");

    //$id = mysql_re //mysql_fetch_array($idResult);
    $id = mysql_result($result, 0);
    if ($result) {
        $response = array("status" => 1, "msg" => "Login successfull");
    } else {
        $response = array("status" => 0, "msg" => "Login failed");
    }

    return $id; //$response;
}

function getCourierDataById() {
    $courierRequest = json_decode($_POST['courier']);

    $courier = (isset($courierRequest->id)) ? $courierRequest : '';
    $id = $courier->id;

    $result = mysql_query("select * from couriers where id = '$id'");

    while ($qOrder = mysql_fetch_array($result)) {
        extract($qOrder);

        $courier = array(
            "id" => $id,
            "name" => $name,
            "surname" => $surname,
            "phone" => $phone,
            "login" => $login,
            "password" => $password
        );
    }
    $json = array("courier" => $courier);

    return json_encode($json, JSON_UNESCAPED_UNICODE);
}

function getCourierLocationId($idCourier) {
    $result = mysql_query("select idLocation from couriers where id = '$idCourier'");
    $id = mysql_result($result, 0);

    if ($result) {
        $response = array("status" => 1, "msg" => "Got location successfull");
    } else {
        $response = array("status" => 0, "msg" => "Got location failed");
    }

    return $id;
}

function assignOrdersToCourier() {
    $courierRequest = json_decode($_POST['courier']);

    $courier = $courierRequest; // (isset($courierRequest->id)) ? $courierRequest : '';
    $idCourier = $courierRequest->id;
    $ordersToAssign = $courierRequest->ordersToAssign;
    $idOrderFirst = $ordersToAssign[0]->id;
    
    
//    saveLog("courier='$courierRequest',\r\n" .
//            "idOrderFirst='$idOrderFirst',\r\n", "assignOrdersToCourier");

    $queryBuilder = "";

    if ($ordersToAssign != null) {
        $queryBuilder = "UPDATE orders set isAssigned = 1 where";
        foreach ($ordersToAssign as $orderToAssign) {
            $idOrder = $orderToAssign->id;


            if ($idOrder == $idOrderFirst) {
                $queryBuilder .= " id = '$idOrder'";
            } else {
                $queryBuilder .= " or id = '$idOrder'";
            }
            saveLog("queryBuilder='$queryBuilder',\r\n", "assignOrdersToCourier");
        }
    }

    saveLog("queryBuilder='$queryBuilder'", "assignOrdersToCourier");
    $result = $queryBuilder != "" ? mysql_query($queryBuilder) : "";

    if ($result) {
        $response = array("status" => 1, "msg" => "Orders assigning successfull");
    } else {
        $response = array("status" => 0, "msg" => "Orders assigning failed");
    }
    
    return $response; //"courier='$courier',\r\n" .
//           "idCourier='$idCourier',\r\n" .
//            "idOrderFirst='$idOrderFirst',\r\n";
}

function updateCourierData() {
    $courierRequest = json_decode($_POST['courier']);

    $courier = (isset($courierRequest->id)) ? $courierRequest : '';
    $idCourier = $courier->id;

    $query = "";

    if ($idCourier != NIL) {
        $query = "UPDATE couriers set "
                . "name = '$courier->name', "
                . "surname = '$courier->surName', "
                . "phone = '$courier->phone' where id = '$idCourier'";
    }

    $result = $query != "" ? mysql_query($query) : "";

    if ($result) {
        $response = array("status" => 1, "msg" => "Courier data changing successful");
    } else {
        $response = array("status" => 0, "msg" => "Courier data changing failed");
    }

    return $response;
}

function saveLog($logString, $name) {
    $fileName = $_SERVER['DOCUMENT_ROOT'] . "/delivery/logs/couriers/courier_" . $name . "_log.txt";
    $currentDateTime = date("Y-m-d H:i:s");
    file_put_contents($fileName, "[" . $currentDateTime . "] " . $logString . "\r\n", FILE_APPEND);
}

@mysql_close($conn);


