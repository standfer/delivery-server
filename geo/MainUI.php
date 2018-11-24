<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script type="text/javascript"
            src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDL3x6fuef-LHFGqipd_itXaO4xwQevoYA&sensor=FALSE">
        </script>
        <script type="text/javascript">
          function initialize() {
            var mapOptions = {
              center: new google.maps.LatLng(-34.397, 150.644),
              zoom: 8,
              mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            var map = new google.maps.Map(document.getElementById("map_canvas"),
                mapOptions);
          }
        </script>
    </head>
    <body> action="" method=""post
    <form action="" method="post">
        <input type='text' name='address' placeholder='Enter any address here' />
        <input type='submit' value='Geocode!' />
    </form>    
        
    <div id="map_canvas" style="width:100%; height:100%"></div>
        <button onclick="test_getOrder()">getOrder</button>
        <script>
        
            function test_getOrder() {
                $.ajax({
                    dataType: 'json',
                    type: "POST",
                    data: "address=Samara" //"action=getOrder&id=51979",
                    url: "MainUI.php",
                    cache: false,
                    timeout: 30000,
                    success: function (data) {
                        $("#result").text(JSON.stringify(data));
                        
                        console.log(data);
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        alert("status: " + xhr.status + " | " + thrownError);
                    }
                });
            }
        </script>
        
        
    </body>
    <?php
if($_POST){
 
    // get latitude, longitude and formatted address
    $data_arr = geocode($_POST['address']);
 
    // if able to geocode the address
    if($data_arr){
         
        $latitude = $data_arr[0];
        $longitude = $data_arr[1];
        $formatted_address = $data_arr[2];
                     
    ?>
 
    <!-- google map will be shown here -->
    <div id="gmap_canvas">Loading map...</div>
    <div id='map-label'>Map shows approximate location.</div>
 
    <!-- JavaScript to show google map -->
    <script type="text/javascript" src="http://maps.google.com/maps/api/js"></script>    
    <script type="text/javascript">
        function init_map() {
            var myOptions = {
                zoom: 14,
                center: new google.maps.LatLng(<?php echo $latitude; ?>, <?php echo $longitude; ?>),
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            map = new google.maps.Map(document.getElementById("gmap_canvas"), myOptions);
            marker = new google.maps.Marker({
                map: map,
                position: new google.maps.LatLng(<?php echo $latitude; ?>, <?php echo $longitude; ?>)
            });
            infowindow = new google.maps.InfoWindow({
                content: "<?php echo $formatted_address; ?>"
            });
            google.maps.event.addListener(marker, "click", function () {
                infowindow.open(map, marker);
            });
            infowindow.open(map, marker);
        }
        google.maps.event.addDomListener(window, 'load', init_map);
    </script>
 
    <?php
 
        // if unable to geocode the address
        }else{
            echo "No map found.";
        }
    }
    
    // function to geocode address, it will return false if unable to geocode address
        function geocode($address){

            // url encode the address
            $address = urlencode($address);

            // google map geocode api url
            $url = "http://maps.google.com/maps/api/geocode/json?address={$address}&key=AIzaSyDIAQzYuYP9w52_aQ7IvlNMLJebm1SItkA";

            // get the json response
            $resp_json = file_get_contents($url);

            // decode the json
            $resp = json_decode($resp_json, true);

            // response status will be 'OK', if able to geocode given address 
            if($resp['status']=='OK'){

                // get the important data
                $lati = $resp['results'][0]['geometry']['location']['lat'];
                $longi = $resp['results'][0]['geometry']['location']['lng'];
                $formatted_address = $resp['results'][0]['formatted_address'];

                // verify if data is complete
                if($lati && $longi && $formatted_address){

                    // put the data in the array
                    $data_arr = array();            

                    array_push(
                        $data_arr, 
                            $lati, 
                            $longi, 
                            $formatted_address
                        );

                    return $data_arr;

                }else{
                    return false;
                }

            }else{
                return false;
            }
        }
    ?>
</html>
