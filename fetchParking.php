<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "map";

$mysqli = mysqli_connect($servername,$username,$password,$dbname);
if($mysqli->connect_error){
    die('Connect Error:' .$mysqli->connect_error. ': '. $mysqli->connect_error);
}
$sql = 'SELECT longlat,population,center,parking FROM mapping';
$result = mysqli_query($mysqli,$sql) or die("error occured");

        $i = 0;
        while($row = mysqli_fetch_array($result)){
            $array[$i][0] = $row[0];
            $array[$i][1] = $row[1];
            $array[$i][2] = $row[2];
            $array[$i][3] = $row[3];
            $i++;
        }

$xmldata = simplexml_load_file("mykatanomes.xml") or die("Failed to load");

 
 for($i=0; $i<count($xmldata->home->hour); $i++)
 {
    $myarray[0][$i]=$xmldata->home->hour['name'];
 }
 for($i=0; $i<count($xmldata->center->hour); $i++)
 {
    $myarray[1][$i]=$xmldata->center->hour[$i];
 }
         echo json_encode(array($array,$myarray));
?>