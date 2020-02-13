<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "map";


$mysqli = mysqli_connect($servername,$username,$password,$dbname);
mysqli_set_charset($mysqli,"utf8");
if($mysqli->connect_error){
    die('Connect Error:' .$mysqli->connect_error. ': '. $mysqli->connect_error);
}
$sql = 'SELECT coord FROM coordinates';
$result = mysqli_query($mysqli,$sql) or die("error occured");
$myCoor = [];

        $i = 0;
        while($row = mysqli_fetch_array($result)){
            $array[$i][0] = $row[0];
            $a = explode(" ", $array[$i][0]);
			$myCoor[$i] = [(float)$a[0],(float)$a[1]];
			$i++;
        }

    echo json_encode($myCoor);
	?>