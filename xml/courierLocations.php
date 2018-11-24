<?php

function parseToXML($htmlStr) {
    $xmlStr = str_replace('<', '&lt;', $htmlStr);
    $xmlStr = str_replace('>', '&gt;', $xmlStr);
    $xmlStr = str_replace('"', '&quot;', $xmlStr);
    $xmlStr = str_replace("'", '&#39;', $xmlStr);
    $xmlStr = str_replace("&", '&amp;', $xmlStr);
    return $xmlStr;
}

$conn = mysql_connect("localhost", "root", "root");
mysql_select_db('delivery', $conn);

// Select all the rows in the markers table
$query = "SELECT * FROM couriers_positions";
$result = mysql_query($query);
if (!$result) {
    die('Invalid query: ' . mysql_error());
}

// Start XML file, echo parent node
echo '<markers>';

// Iterate through the rows, printing XML nodes for each
while ($row = @mysql_fetch_assoc($result)) {
    // Add to XML document node
    echo '<marker ';
    echo 'name="' . parseToXML($row['name']) . '" ';
    echo 'lat="' . $row['latitude'] . '" ';
    echo 'lng="' . $row['longitude'] . '" ';
    echo '/>';
}

// End XML file
echo '</markers>';
?>