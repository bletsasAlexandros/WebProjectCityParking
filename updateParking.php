<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "map";

$parking=$_POST['parkingspot'];
$center=$_POST['id'];


$mysqli = mysqli_connect($servername,$username,$password,$dbname);
mysqli_set_charset($mysqli,"utf8");
if($mysqli->connect_error){
    die('Connect Error:' .$mysqli->connect_error. ': '. $mysqli->connect_error);
}
$sql = "UPDATE mapping SET parking = '$parking' WHERE center='$center'";
$result = mysqli_query($mysqli,$sql) or die("error occured");

header('Location: /adminpage.php');

?>