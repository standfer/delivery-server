<?php

$conn = mysql_connect("localhost", "root", "root");
mysql_select_db('delivery', $conn);
mysql_query("SET NAMES utf8");
mysql_query("SET CHARACTER SET utf8");
$mysqli = new mysqli("localhost", "root", "root", "delivery");
