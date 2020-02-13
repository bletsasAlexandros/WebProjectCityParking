<!DOCTYPE html>
<html>
<head>
<title>My Page</title>
<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet/v0.7.7/leaflet.css" />
<style type="text/css">
#mapid { height: 700px;
         width: 900px;
         position: absolute;
  		 left: 100px;
  		 top: 150px;
 }
 #background {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 150px;
    background-color: #33cccc; 
}
#background1 {
    background-color: #e6ffcc; 
    opacity: 50%;
}
 img {
  position: absolute;
  top: 0px;
  right: 200px;
}
#title {
	font-family:Courier New;
	position: absolute;
	top:50px;
	left: 90px;
}
#mybutton1
{
	border-bottom-color: green;
}
#background2
{
	position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
	background-color: #e6ffee;
}
p.thick{
	font-weight: bold;
	font-size: large;
	font-family: "Times New Roman", Times, serif;
}
p.normal{
	font-weight: normal;
	font-size: large;
	font-family: "Times New Roman", Times, serif;
}
.button {
  display: inline-block;
  padding: 15px 25px;
  font-size: 24px;
  cursor: pointer;
  text-align: center;
  text-decoration: none;
  outline: none;
  color: #fff;
  background-color: #4CAF50;
  border: none;
  border-radius: 15px;
  box-shadow: 0 9px #999;
}

</style>


</head>
<body>
	<div id="background2">
	<div id="background">

	<img src="ParkingImage.jpg" width="300" height="150" position="top right">
	<!-- Login form for admin --> 
	<div class="login">
			<form action="authenticate.php" method="post">
				<label for="username">
					<i class="fas fa-user"></i>
				</label>
				<input type="text" name="username" placeholder="Διαχειριστής" id="username" required>
				<label for="password">
					<i class="fas fa-lock"></i>
				</label>
				<input type="password" name="password" placeholder="Κωδικός" id="password" required>
				<input type="submit" value="Σύνδεση">
			</form>
		</div>

	<h1 id="title" >cityParking</h1>
	<h2 style="position:absolute;top:330px;right:600px; ">Επιλογή Ώρας:</h2>
	 <input id="time" type="time" value="12:00"style="position:absolute;top:380px;right:670px;"/>
	 <button id='simulation' value="Submit" style="position:absolute;top:420px;right:620px; border-bottom-color: green; border-width:5px;  
    border-bottom-style:outset;" class="button">Υποβολή</button>

	<!-- Live Clock -->
	<div id="mapid"></div>
	<script src="http://cdn.leafletjs.com/leaflet/v0.7.7/leaflet.js"></script>

	<!-- Map -->
	<div id="clock" style="position:absolute;top:150px;right:500px;"><iframe src="https://www.zeitverschiebung.net/clock-widget-iframe-v2?language=en&size=small&timezone=Europe%2FAthens" width="100%" height="90" frameborder="0" seamless></iframe> </div>

	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

	<!-- Οδηγίες -->
	<h2 style="position:absolute;top:200px;right:200px; font-weight: bold;">Οδηγίες Χρήσης</h2>
	<p class="thick" style="position:absolute;top:250px;right:260px;">Βήμα 1:</p>
	<p class="normal" style="position:absolute;top:280px;right:170px;">Επιλέξτε ώρα που θα επιθυμούσατε</p>
	<p class="normal" style="position:absolute;top:300px;right:170px;"> να παρκάρετε και πατήστε υποβολή</p>
	<p class="thick" style="position:absolute;top:350px;right:260px;">Βήμα 2:</p>
	<p class="normal" style="position:absolute;top:380px;right:160px;">Κάντε κλικ στο οικοδομικό τετράγωνο</p>
	<p class="normal" style="position:absolute;top:400px;right:160px;">που επιθυμείτε να παρκάρετε και συμπληρώστε</p>
	<p class="normal" style="position:absolute;top:420px;right:160px;">την απόσταση που θέλετε να περπατήσετε.</p>
	<p class="thick" style="position:absolute;top:470px;right:260px;">Βήμα 3:</p>
	<p class="normal" style="position:absolute;top:500px;right:160px;">Σταθμεύστε στην θέση που αναδεικνύεται!</p>

	<script>


		/* Parking demand by hour 00:00 - 23:00*/
var cityHome = [0.75,0.55,0.46,0.7,0.2,0.39,0.55,0.67,0.8,0.95,0.9,0.2,0.83,0.7,0.62,0.7,0.62,0.74,0.8,0.8,0.7,0.92,0.66,0.69];
var cityCenter = [0.25,0.45,0.46,0.81,0.8,0.7,0.67,0.55,0.49,0.34,0.49,0.9,0.34,0.45,0.48,0.53,0.5,0.56,0.73,0.41,0.4,0.72,0.66,0.69];



		var mymap = L.map('mapid').setView([40.640064, 22.944420], 14);

		L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(mymap);

document.getElementById('simulation').addEventListener('click', function(evt) {
  var newData;
 $.ajax({
        type: "GET",
        url: "fetchParking2.php",             
        dataType: "json",              
        success: function(response)
        {
          newData = response;
        },
        error: function(ex) { alert(ex.responseText);},
        async:false
});
 let polygon = L.geoJson(newData, {onEachFeature: forEachFeature, style: style}).addTo(mymap);
 function forEachFeature(feature, layer) {

        var popupContent = '<div id="background1">\
        <form id="testForm" action="scan.php" method="post">\
        <br><h3 style="font-family: "Times New Roman", Times, serif;">Επιλογή Θέσης</h3></br>\
  <h4>Απόσταση από το σημείο:<input type="text" name="walkingDistance"></h4>\
  <br><input type="text" name="id" value="'+feature.center+'" style="height:0px;width:0px;left:-100px;"><br>\
  <input id="mybutton1" type="submit" name=submit value="Υποβολή!">\
  </form>\
  </div>';

        if (feature.properties && feature.properties.popupContent) {
            popupContent += feature.properties.popupContent;
        }
            layer.bindPopup(popupContent);
};

 }, false);

 var myData;
 $.ajax({
        type: "GET",
        url: "whereToPark.php",             
        dataType: "json",              
        success: function(response)
        {
          myData = response;
        },
        async:false
});
 	var marker;

    if (marker) { 
        mymap.removeLayer(marker); 
    }
    marker = L.marker(myData[0]).addTo(mymap);
    mymap.setView(myData[0],20);
 	marker.bindPopup("<b>Σταθμεύστε εδώ</b>").openPopup();

function timeStringToFloat(time) {
  var hoursMinutes = time.split(/[.:]/);
  var hours = parseInt(hoursMinutes[0], 10);
  var minutes = hoursMinutes[1] ? parseInt(hoursMinutes[1], 10) : 0;
  return hours + minutes / 60;
}



function style(feature) {
  var x = document.getElementById("time").value;
  var b = timeStringToFloat(x);
  var time = Math.round(b);

  var lat = feature.center[1];
  var lon = feature.center[0];
  var dis = distance(lat,lon);
var location;
if(dis <=3.5)
{
  location = "center";
}else{
  location = "home";
}
  var parkingSpots = feature.parking;
  var population = feature.population;
  if (location = "center"){
    var demand = cityCenter[time];
    var free = 1-demand;
    var parkForRest = parkingSpots - 0.2*population;
    if (parkForRest<0){
    parkForRest=0;
    }
    d=free*parkForRest;
  }else{
    var demand = cityHome[time];
    var free = 1-demand;
    var parkForRest = parkingSpots - 0.2*population;
    if (parkForRest<0){
    parkForRest=0;
    }
    d=free*parkForRest;
  }

        
        if(d<16){
          return {color: '#E31A1C'};
        }else if(d<76){
            return {color: '#E4EE3D'};
        }else{
          return {color: '#ADDD8E'};
        }
    }

function distance(lat2, lon2) {
  var lat1 = 40.627257;
  var lon1 = 22.947462;
  var unit = "K";
  if ((lat1 == lat2) && (lon1 == lon2)) {
    return 0;
  }
  else {
    var radlat1 = Math.PI * lat1/180;
    var radlat2 = Math.PI * lat2/180;
    var theta = lon1-lon2;
    var radtheta = Math.PI * theta/180;
    var dist = Math.sin(radlat1) * Math.sin(radlat2) + Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);
    if (dist > 1) {
      dist = 1;
    }
    dist = Math.acos(dist);
    dist = dist * 180/Math.PI;
    dist = dist * 60 * 1.1515;
    if (unit=="K") { dist = dist * 1.609344 }
    if (unit=="N") { dist = dist * 0.8684 }
    return dist;
  }
}


	</script>
</div>
</div>
</body>
</html>
