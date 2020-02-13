<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "map";

$mysqli = mysqli_connect($servername,$username,$password,$dbname);
if($mysqli->connect_error){
    die('Connect Error:' .$mysqli->connect_error. ': '. $mysqli->connect_error);
}
$sql = 'DELETE FROM mapping';
$result = mysqli_query($mysqli,$sql) or die("error occured");

header('Location: /adminpage.php');

?>