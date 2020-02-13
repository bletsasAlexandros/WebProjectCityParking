<?php
if(isset($_POST['submit'])){
	$file = $_FILES['file'];

	$fileName = $_FILES['file']['name'];
	$fileTmpName = $_FILES['file']['tmp_name'];
	$fileSize = $_FILES['file']['size'];
	$fileError = $_FILES['file']['error'];
	$fileType = $_FILES['file']['type'];

	$fileExt = explode('.', $fileName);
	$fileActualExt = strtolower(end($fileExt));

	$allowed = array('kml','xml');
	if(in_array($fileActualExt, $allowed)){
		if ($fileError === 0){
				$fileNameNew = uniqid('', true).".".$fileActualExt;
				$fileDestination = 'uploads/'.$fileName;
				move_uploaded_file($fileTmpName, $fileDestination);
		}else{
			echo "There was an error uploading the file.";
		}

	}else{
		echo "You cannot upload files of this type.";
	}

}
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "map";

$mysqli = mysqli_connect($servername,$username,$password,$dbname);

mysqli_set_charset($mysqli,"utf8");
if($mysqli->connect_error){
    die('Connect Error:' .$mysqli->connect_error. ': '. $mysqli->connect_error);
}
$kml = simplexml_load_file('uploads/'.$fileName);
foreach( $kml->Document->Folder->Placemark as $pm){
    if(isset($pm->LookAt))
    {
        $centerLong = $pm->LookAt->longitude;
        $centerLat = $pm->LookAt->latitude;
        $centroid = $centerLat.','.$centerLong;

  if(isset($pm->MultiGeometry->Polygon)){
        // Process polygon datas
        // Get coordinates for 'outerBoundaryIs', other possible data not considered is 'innerBoundaryIs'
        $coordinates = $pm->MultiGeometry->Polygon->outerBoundaryIs->LinearRing->coordinates;
        $cordsData = trim(((string) $coordinates));
        //print_r($cordsData);
        $longlang = explode(" ", $cordsData);
        //print_r($longlang);
        //print_r(count($longlang));
        for($i=0; $i<=count($longlang)-1; $i++){

            $bothlonglang[$i] = explode(",", $longlang[$i]);
            $onlylong[$i]=$bothlonglang[$i][1];
            $onlylat[$i]=$bothlonglang[$i][0];
        }
        $dataBlock = $pm->description;
		$page = htmlspecialchars_decode($dataBlock);
		//POPULATION FROM KML
		if (strpos($page, 'Population')) {
    		$string = explode("Population", $page);
    		$a = $string[1];
    		$b = explode("class=\"atr-value\">", $a);
    		$c = $b[1];
    		$d = explode("<", $c);
    		$e = $d[0];
    		$Population = intval($e);
		}else{
			$Population=0;
		}
    }
       $sql = "INSERT INTO mapping (longlat,population,center,parking) VALUES ( '$cordsData','$Population','$centroid','150')";
       $result = mysqli_query($mysqli,$sql) or die("error occured");
                
    }
};


header('Location: /adminpage.php');
