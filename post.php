<?php
//$q = intval($_GET['q']);
$q="5";
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "map";
$mysqli = mysqli_connect($servername,$username,$password,$dbname);
if($mysqli->connect_error){
    die('Connect Error:' .$mysqli->connect_error. ': '. $mysqli->connect_error);
}
 $sql = "UPDATE mapping SET center VALUES ('$q') WHERE id='31511'";
$result = mysqli_query($mysqli,$sql) or die("error occured");