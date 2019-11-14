<?php
    include 'delivery/helpers/GeoHelper.php';
//    include 'delivery\templates\list.php';
    include 'delivery/db/config.php';
    include 'delivery/db/loaders.php';
    include 'delivery/db/safemysql.class.php';
    
    
    $db = new SafeMysql(['mysqli' => $mysqli]);
    $tLocationOrders = "locations_orders";
    $tOrders = "orders";
    $tLocations = "locations";
    $tWorkplaces = "workplaces";
    $fieldsLocationsOrders = ['address', 'phoneNumber', 'cost', 'isDelivered', 'id'];
    $fieldsOrders = ['address', 'phoneNumber', 'cost', 'isDelivered'];
    $fieldsLocations = ['latitude', 'longitude'];
    $fieldsWorkplaces = ['address', 'phoneNumber', 'cost'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dataLocationsOrders = $db->filterArray($_POST, $fieldsLocationsOrders);
    $dataOrders = $db->filterArray($_POST, $fieldsOrders);
    $dataLocations = $db->filterArray($_POST, $fieldsLocations);
    if (isset($_POST['delete'])) {
        $db->query("DELETE FROM ?n WHERE id=?i", $tOrders, $_POST['delete']);
    } elseif ($_POST['id']) {
        $idLocation = getIdLocationByIdOrder($_POST['id']);
        $data_arr = geocode($dataLocationsOrders['address']);
        $dataLocations['latitude'] = $data_arr[0];
        $dataLocations['longitude'] = $data_arr[1];
        $dataOrders['address'] = mysql_real_escape_string($data_arr[2]);
        
        $db->query("UPDATE ?n SET ?u WHERE id = ?i", $tOrders, $dataOrders, $_POST['id']);
        $db->query("UPDATE ?n SET ?u WHERE id = ?i", $tLocations, $dataLocations, $idLocation);
    } else {
        //$db->query("INSERT INTO ?n SET ?u", $tLocationOrders, $dataLocationsOrders);
    }
    //header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
    //exit;
}

if (!isset($_GET['idOrder'])) {
    $LIST = $db->getAll("SELECT * FROM ?n", $tLocationOrders);
    include 'delivery/templatesLib/list.php';
} else {
    if ($_GET['idOrder']) {
        $row = $db->getRow("SELECT * FROM ?n WHERE id=?i", $tOrders, $_GET['idOrder']);
    } else {
        $row['name'] = '';
        $row['addressWorkplace'] = '';
        $row['addressOrder'] = '';
        $row['phoneNumber'] = '';
        $row['cost'] = 0;
    }
    include 'delivery\templatesLib\form.php';
}

function e($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'utf-8');
}
?>


<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Сервер доставки</title>
        <style>
            #map {
             height: 400px;
             width: 100%;
            }
        </style>
    </head>
    <body>
        Назначение курьера
        <form action="" method="post">
<!--            <input type='text' name='inputCourier' placeholder='Курьер...' />
            <input type='text' name='inputWorkPlace' placeholder='Организация производитель...' />-->
            <?php
                $couriers = courierList();
                echo "<select name='dropDownCourier'>";
                foreach($couriers as $courier) { 
                    printf("<option value='%s'>%s</option>",$courier["id"],$courier["name"]);
                } 
                echo "</select>";
            ?>
            <?php
                $workPlaces = workPlaceList();
                echo "<select name='dropDownWorkPlace'>";
                foreach($workPlaces as $workPlace) { 
                    printf("<option value='%s'>%s</option>",$workPlace["id"],$workPlace["address"]);
                }
                echo "</select>";
            ?>
            <input type='text' name='inputAddress' placeholder='Адрес заказа...' />
            <input type='submit' value='ОК'/>
        </form>
        
        <h3>Местоположение курьеров</h3>
        <div id="map"></div>
        <script>
          var markers = [];
          var map = null;
          var infoWindow = null;
          
          setInterval(function () {
            deleteMarkers();
            bindMarkers();
          }, 5000);
          
          function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                center: new google.maps.LatLng(53.141435, 50.174255),
                zoom: 12
            });
            infoWindow = new google.maps.InfoWindow;
            
            bindMarkers();
          }
          
          function downloadUrl(url,callback) {
            var request = window.ActiveXObject ?
                new ActiveXObject('Microsoft.XMLHTTP') :
                new XMLHttpRequest;

            request.onreadystatechange = function() {
              if (request.readyState == 4) {
                request.onreadystatechange = doNothing;
                callback(request, request.status);
              }
            };
            request.open('GET', url, true);
            request.send(null);
          }
        
        function doNothing() {}
        
        function deleteMarkers() {
            for (var i = 0; i < markers.length; i++) {
                markers[i].setMap(null);
            }
            markers = [];
        };
        
        function bindMarkers() {
            downloadUrl('delivery/xml/courierLocations.php', function(data) {
                var xml = data.responseXML;
                var markersXml = xml.documentElement.getElementsByTagName('marker');
                Array.prototype.forEach.call(markersXml, function(markerElem) {
                    var name = markerElem.getAttribute('name');
                    var phone = markerElem.getAttribute('phone');
                    var point = new google.maps.LatLng(
                        parseFloat(markerElem.getAttribute('lat')),
                        parseFloat(markerElem.getAttribute('lng')));
                      
                    //build infowindow  
                    var infowincontent = document.createElement('div');
                    var strong = document.createElement('strong');
                    strong.textContent = name
                    infowincontent.appendChild(strong);
                    infowincontent.appendChild(document.createElement('br'));
                    var text = document.createElement('text');
                    text.textContent = phone
                    infowincontent.appendChild(text);

                    var marker = new google.maps.Marker({
                      map: map,
                      position: point,
                      title: name
                      });
                    
                    marker.addListener('click', function() {
                        infoWindow.setContent(infowincontent);
                        infoWindow.open(map, marker);
                    });
                    
                    markers.push(marker);
                });
            });
        }
        </script>
        <script async defer
                src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAuYrvwL2UuYpIJHS7bPQkg4adOUge0xng&callback=initMap">
        </script>
    </body>
</html>

<?php
$idCourier = isset($_POST['name']) ? mysql_real_escape_string($_POST['name']) : ""; //"https://maps.googleapis.com/maps/api/js?key=AIzaSyAuYrvwL2UuYpIJHS7bPQkg4adOUge0xng&callback=initMap" //"https://maps.googleapis.com/maps/api/js?key=AIzaSyDL3x6fuef-LHFGqipd_itXaO4xwQevoYA&callback=initMap"
$response = '';
$id = 1;

$post_action = filter_input(INPUT_POST, 'action');
$inputCourier = isset($_POST['dropDownCourier']) ? mysql_real_escape_string($_POST['dropDownCourier']) : ""; 
$inputWorkPlace = isset($_POST['dropDownWorkPlace']) ? mysql_real_escape_string($_POST['dropDownWorkPlace']) : ""; 
$inputAddress = isset($_POST['inputAddress']) ? mysql_real_escape_string($_POST['inputAddress']) : ""; 

$get_action = filter_input(INPUT_GET, 'action');

$latLocation = null;
$lngLocation = null;
$formattedAddress = null;

$action=null;

//проверим есть ли ПОСТ параметры
if ($post_action!=NULL) {
    $action = $post_action;
    
}
//проверим есть ли ГЕТ параметры
if ($get_action!=NULL) {
    $action = $get_action;
}

if ($inputAddress != '') {
    $data_arr = geocode($inputAddress);
    $latLocation = $data_arr[0];
    $lngLocation = $data_arr[1];
    $formattedAddress = mysql_real_escape_string($data_arr[2]);
    
    $sql = "BEGIN;".
           "INSERT locations (latitude, longitude) VALUES('$latLocation','$lngLocation');".
           "INSERT orders (address, phoneNumber, cost, isDelivered, idLocation, idCourier, idWorkplace, create_ts) ".
                "VALUES ('$formattedAddress', '3300083', 1000, 0, @@last_insert_id, '$inputCourier', '$inputWorkPlace', NOW());".
           "COMMIT;";
    
    $queryResult = $mysqli->multi_query($sql);
    echo $queryResult ? "Курьер успешно назначен" : "Ошибка при назначении курьера";
    //header("Refresh:0");
}

switch ($action) {
    case NULL: //если форма без параметров вызвана и action остался null
        echo '';
        break;
    case 'getText':
        echo 'Это не закодированный в JSON текст';
        break;
    case 'getOrderDetails':
        echo funcGetOrderDetails();
        break;
    case 'getOrdersForCourier':
        echo getOrdersForCourier();
        break;	
    case 'updateCourierLocation':
        echo updateCourierLocation();
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
		$ordersList[] = array("address" => $addressOrder,
                                      "lat" => $latOrder,
                                      "lng" => $lngOrder,
                                      "phoneNumber" => $phoneNumber,
                                      "cost" => $cost,
                                      "isDelivered" => $isDelivered,
                                      "idWorkPlace" => $idWorkPlace,
                                      "addressWorkPlace" => $addressWorkPlace,
                                      "latWorkPlace" => $latWorkPlace,
                                      "lngWorkPlace" => $lngWorkPlace,
                                        );
	}
	$json = array("info" => $ordersList);
        
	 
	if($queryResult){
		$response = array("status" => 1, "msg" => "Courier location updated");
	}else{
		$response = array("status" => 0, "msg" => "Error location updating");
	}
	return json_encode($json, JSON_UNESCAPED_UNICODE);//$response;
}


function funcGetOrderDetails() {
    $arr = [
        "Address" => "Буровик",
        "Phone" => "88463521098",
        "Cost" => 1208,
        "Lat" => 53.1157204,
        "Lng" => 50.0847883,
    ];
    return json_encode($arr, JSON_UNESCAPED_UNICODE);
}

function getOrdersForCourier() {
    $courierRequest = json_decode($_POST['courier']);
    //var_dump($courier)
    $courier = (isset($courierRequest->id)) ? $courierRequest : '';
    return $courier; //$courier->lat;
}



@mysql_close($conn);
//echo $_POST["courier"];
//echo var_dump(json_decode($_POST['courier'], true));
