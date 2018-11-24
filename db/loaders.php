<?php
include 'delivery\db\config.php';

function courierList() {
    $query = "select id, name from couriers order by name";
    $query_res = mysql_query($query);
    $couriers = array();
    while ($row = mysql_fetch_assoc($query_res)) {
        $couriers[] = $row;
    }
    return $couriers;
}

function loadLocationsOrders() {
    $query = "select * from locations_orders";
    $query_res = mysql_query($query);
    $locationsOrders = array();
    while ($row = mysql_fetch_assoc($query_res)) {
        $locationsOrders[] = $row;
    }
    return $locationsOrders;
}

function workPlaceList() {
    $query = "select id, address from workplaces order by address";
    $query_res = mysql_query($query);
    $workPlaces = array();
    while ($row = mysql_fetch_assoc($query_res)) {
        $workPlaces[] = $row;
    }
    return $workPlaces;
}

function getIdLocationByIdOrder($idOrder) {
    $query = "select idLocation from orders where id = '$idOrder'";
    $query_res = mysql_query($query);
    $idOrder = array();
    while ($row = mysql_fetch_assoc($query_res)) {
        $idOrder[] = $row;
    }
    return $idOrder[0]['idLocation'];
}

//not need
function getSqlTest() {

    $qur = mysql_query("select name from couriers order by id");
    $result = array();
    while ($r = mysql_fetch_array($qur)) {
        extract($r);
        $result[] = array("name" => $name);
    }
    $json = array("info" => $result);
    //return var_dump($result[0]);
    //$qur = mysql_query("update locations set latitude='$lat', longitude = '$lng' where id = '$id'");
    return json_encode($json, JSON_UNESCAPED_UNICODE);
}
