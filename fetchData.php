<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "map";

$mysqli = mysqli_connect($servername,$username,$password,$dbname);
if($mysqli->connect_error){
    die('Connect Error:' .$mysqli->connect_error. ': '. $mysqli->connect_error);
}
$sql = 'SELECT longlat,population,center,parking,id FROM mapping';
$result = mysqli_query($mysqli,$sql) or die("error occured");

		$i = 0;
        while($row = mysqli_fetch_array($result)){
            $array[$i][0] = $row[0];
            $array[$i][1] = $row[1];
            $array[$i][2] = $row[2];
            $array[$i][3] = $row[3];
            $array[$i][4] = $row[4];
            $i++;
        }
         echo json_encode($array);
?>