<?php
include '../db/config.php';

header('Content-Type: text/xml');

function parseToXML($htmlStr) {
    $xmlStr = str_replace('<', '&lt;', $htmlStr);
    $xmlStr = str_replace('>', '&gt;', $xmlStr);
    $xmlStr = str_replace('"', '&quot;', $xmlStr);
    $xmlStr = str_replace("'", '&#39;', $xmlStr);
    $xmlStr = str_replace("&", '&amp;', $xmlStr);
    return $xmlStr;
}

//$conn = mysql_connect("localhost", "046797347_root", "2.718281828");
//mysql_select_db('standfer231_delivery', $conn);
//$conn = mysql_connect("localhost", "root", "root");
//mysql_select_db('delivery', $conn);

// Select all the rows in the markers table
$query = "SELECT * FROM couriers_positions";
$result = mysql_query($query);
if (!$result) {
    die('Invalid query: ' . mysql_error());
}

// Start XML file, echo parent node
echo "<?xml version='1.0' ?>";
echo '<markers>';

// Iterate through the rows, printing XML nodes for each
while ($row = @mysql_fetch_assoc($result)) {
    // Add to XML document node
    echo '<marker ';
    echo 'name="' . parseToXML($row['name']) . '" ';
    echo 'phone="' . parseToXML($row['phone']) . '" ';
    echo 'lat="' . $row['latitude'] . '" ';
    echo 'lng="' . $row['longitude'] . '" ';
	echo 'update_ts="' . $row['update_ts'] . '" ';
    echo '/>';
}

// End XML file
echo '</markers>';
?>