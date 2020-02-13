<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "map";

$distance=$_POST['walkingDistance'];
$centroid=$_POST['id'];


require_once('dbscan.php');

$mysqli = mysqli_connect($servername,$username,$password,$dbname);
mysqli_set_charset($mysqli,"utf8");
if($mysqli->connect_error){
    die('Connect Error:' .$mysqli->connect_error. ': '. $mysqli->connect_error);
}
$sql = 'DELETE  FROM coordinates';
$result = mysqli_query($mysqli,$sql) or die("error occured1");
$sql = 'SELECT center,id,parking FROM mapping';
$result = mysqli_query($mysqli,$sql) or die("error occured2");
$mypoints = [];
$points = [];

		$i = 0;
        while($row = mysqli_fetch_array($result)){
            $center[$i] = $row[0];
            $pointsIds[$i] = $row[1];
            $parking[$i] = $row[2];
            $a = explode(",", $center[$i]);
            $mypoints[$i][0] = (float)$a[0];
            $mypoints[$i][1] = (float)$a[1];
            $i++;
        }
        $myCenter = [];
       $cen = explode(",", $centroid);
       $myCenter[0] = (float)$cen[0];
       $myCenter[1] = (float)$cen[1];
       $j=0;
       for($i=0; $i<sizeof($mypoints); $i++)
       {
       	
       	if(haversineGreatCircleDistance(
  $myCenter[0], $myCenter[1], $mypoints[$i][0], $mypoints[$i][1])<=$distance){
       		$points[$j][0] = $mypoints[$i][0];
       		$points[$j][1] = $mypoints[$i][1];
       		$j++;
       		       }
  }
$pointsIds = [];
for ($i=0; $i<sizeof($points) ; $i++) { 
	$pointsIds[$i] = $i;
}

$distance_matrix = createDistanceMatrix($points,$pointsIds);

$DBSCAN = new DBSCAN($distance_matrix, $pointsIds);
echo '<br />';
$epsilon = 200;
$minpoints = 3;
$clusters = $DBSCAN->dbscan($epsilon, $minpoints);
$i=0;
$mycoor = [];
foreach ($clusters as $cluster)
{
	if (sizeof($cluster) > 0)
	{
		//echo '<ul>';
		foreach ($cluster as $member_point_id)
		{
			//echo '<li>'.$member_point_id.'</li>';
			$mycoor[$i] = $mypoints[$member_point_id];

			$i++;
		}
		//echo '</ul>';
	}
}

for ($i=0; $i<sizeof($points); $i++){
	$a = sprintf("%.15f", $points[$i][0]);
	$b = sprintf("%.15f", $points[$i][1]);
	$c = $a.' '.$b;
	echo $c,'<br />';
	
$sql = "INSERT INTO coordinates (coord) VALUES ('$c')";
$result = mysqli_query($mysqli,$sql) or die("error occured3");
}

header('Location: /mypage.php');


function haversineGreatCircleDistance(
  $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
{
	
  // convert from degrees to radians
  $latFrom = deg2rad($latitudeFrom);
  $lonFrom = deg2rad($longitudeFrom);
  $latTo = deg2rad($latitudeTo);
  $lonTo = deg2rad($longitudeTo);

  $latDelta = $latTo - $latFrom;
  $lonDelta = $lonTo - $lonFrom;

  $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
    cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
  //echo $angle * $earthRadius," ";
  return $angle * $earthRadius;
}

function createDistanceMatrix($points,$pointsIds)
{
	$distanceMatrix = [];
	for($i=0; $i<sizeof($points); $i++)
	{
		set_time_limit(300);
		for($j=$i+1; $j<sizeof($points); $j++){
		$distanceMatrix[$i][$j] = (int)haversineGreatCircleDistance($points[$i][0],$points[$i][1],$points[$j][0],$points[$j][1]);
	}
	
}
	$distanceMatrix[$i-1] = array();
	return $distanceMatrix;
}

