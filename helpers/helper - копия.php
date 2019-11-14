<?php
include '../dto/Order.php';
include '../dto/ProductInOrder.php';

$conn = mysql_connect("localhost", "root", "root");
mysql_select_db('delivery', $conn);


$post_action = filter_input(INPUT_POST, 'action');
if ($post_action!=NULL) {
    $action = $post_action;
    
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


function updateCourierLocation() {
	$courierRequest = json_decode($_POST['courier']);
	
	$courier = (isset($courierRequest->id)) ? $courierRequest : '';
	$id = $courier->id;
	$lat = $courier->currentCoordinate->lat;
	$lng = $courier->currentCoordinate->lng;
	
	$sql = "update locations set latitude = '$lat', longitude = '$lng' where id = '$id'";
	$queryResult = mysql_query($sql);
        
        //apply orders to courier->orders
	$qOrdersList = mysql_query("select * from locations_orders where idCourier = '$id'");
	$ordersList = array();
	while($qOrder = mysql_fetch_array($qOrdersList)){
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
        
	 
	if($queryResult){
		$response = array("status" => 1, "msg" => "Courier location updated");
	}else{
		$response = array("status" => 0, "msg" => "Error location updating");
	}
	
	
	 
	return json_encode($json, JSON_UNESCAPED_UNICODE);//$response;
}

function getOrdersUnassigned1() {
    $qOrdersUnassigned = mysql_query("SELECT * FROM ordersByWorkplaces WHERE idCourier IS NULL"); // AND deliver_ts > NOW()");

    while ($qOrder = mysql_fetch_array($qOrdersUnassigned)) {
        extract($qOrder);

        $workPlaceLocation = array(
            "latitude" => $latWorkPlace,
            "longitude" => $lngWorkPlace
        );

        $orderLocation = array(
            "latitude" => $latOrder,
            "longitude" => $lngOrder
        );

        $workPlace = array(
            "id" => $idWorkPlace,
            "address" => $addressWorkPlace,
            "location" => $workPlaceLocation
        );

        $orders[] = array(
            "id" => $idOrder,
            "address" => $addressOrder,
            "phoneNumber" => $phoneNumber,
            "cost" => $cost,
            "create_ts" => $create_ts,
            "deliver_ts" => $deliver_ts,
            "location" => $orderLocation,
            "workPlace" => $workPlace
        );
    }
    $json = array("orders" => $orders);

    return json_encode($json, JSON_UNESCAPED_UNICODE);
}

function getOrdersUnassigned() {
    $orders = null;
    $queryResult = mysql_query("SELECT * FROM ordersByWorkplaces WHERE idCourier IS NULL"); // AND deliver_ts > NOW()");
    while ($rowOrder = mysql_fetch_array($queryResult)) {
        extract($rowOrder);
        $order = new Order($idOrder, 
                $addressOrder, $latOrder, $lngOrder, 
                $phoneNumber, $cost, $isDelivered, 
                $idWorkPlace, $addressWorkPlace, 
                $latWorkPlace, $lngWorkPlace, 
                $priority, $odd, $orderNotes, 
                null, null, null
        );
        $orders[] = $order;
    }
    $json = array("orders" => $orders);

    if ($queryResult) {
        $response = array("status" => 1, "msg" => "Unassigned order successfully got");
    } else {
        $response = array("status" => 0, "msg" => "Error getting unassigned order");
    }
    return json_encode($json, JSON_UNESCAPED_UNICODE);
}

function updateDTOCourierLocation() {
    $courierRequest = json_decode($_POST['courier']);

    $courier = (isset($courierRequest->id)) ? $courierRequest : '';
    $id = $courier->id;
    $lat = $courier->currentCoordinate->lat;
    $lng = $courier->currentCoordinate->lng;

    $sql = "update locations set latitude = '$lat', longitude = '$lng' where id = '$id'";
    $queryResult = mysql_query($sql);

    //apply orders to courier->orders
    $queryResult = mysql_query("select * from locations_orders where idCourier = '$id'");
    while ($rowOrder = mysql_fetch_array($queryResult)) {
        extract($rowOrder);
        $order = new Order($idOrder,
                  $addressOrder, $latOrder, $lngOrder,
                  $phoneNumber, $cost, $isDelivered,
                  $idWorkplace, $addressWorkplace,
                  $latWorkPlace, $lngWorkPlace,
                  $priority, $odd, $orderNotes,
                  $idClient, $clientName, $clientPhone
                );
        //$order->productsInOrder[] = getOrderProducts($order);
        getOrderProducts($order);
        $orders[] = $order; //add $order to $ordersList array
        
        
    }
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
        $productInOrder = new ProductInOrder($idProductInOrder, $quantity, $order,
                $idProduct, $name, $price
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

function assignOrdersToCourier() {
    $courierRequest = json_decode($_POST['courier']);

    $courier = $courierRequest; // (isset($courierRequest->id)) ? $courierRequest : '';
    $idCourier = $courierRequest->id;
    $ordersToAssign = $courierRequest->ordersToAssign;
    $idOrderFirst = $ordersToAssign[0]->id;

    $queryBuilder = "";

    if ($ordersToAssign != NIL) {
        $queryBuilder = "UPDATE orders set idCourier = '$idCourier' where";
        foreach ($ordersToAssign as $orderToAssign) {
            $idOrder = $orderToAssign->id;


            if ($idOrder == $idOrderFirst) {
                $queryBuilder .= " id = '$idOrder'";
            } else {
                $queryBuilder .= " or id = '$idOrder'";
            }
        }
    }

    $result = $queryBuilder != "" ? mysql_query($queryBuilder) : "";

    if ($result) {
        $response = array("status" => 1, "msg" => "Orders assigning successfull");
    } else {
        $response = array("status" => 0, "msg" => "Orders assigning failed");
    }

    return $response;
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


@mysql_close($conn);


