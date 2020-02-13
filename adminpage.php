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
 #mydata{
  height: 0px;
  width: 0px;
  left: -100px;
 }
 #background {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 150px;
    background-color: #33cccc; 
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
#background1 {
    background-color: #e6ffcc; 
    opacity: 50%;
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
.button {
  background-color: #4CAF50;
  border: none;
  color: white;
  padding: 15px 32px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  cursor: pointer;
}
.button1 {
  background-color: red;
  border: none;
  color: white;
  padding: 15px 32px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  cursor: pointer;
}
</style>


</head>
<body>
  <div id="background2">
  <div id="background">
    <img src="ParkingImage.jpg" width="300" height="150" position="top right">
    <h2 style="position:absolute;top:330px;right:630px;">Επιλογή Ώρας:</h2>
  <button id='simulation' value="Submit" style="position:absolute;top:400px;right:700px;">Εξομοίωση</button>
  <button id='delete' style="position:absolute;top:860px;left:200px;" onclick="window.location.reload();" class="button1">Διαγραφή</button>
  <input id="time" type="time" style="position:absolute;top:400px;right:600px;"/>
  <form action="upload.php" method="POST" enctype="multipart/form-data">
    <input type="file" name="file" style="position:absolute;top:800px;right:600px;">
    <button type="submit" name="submit" style="position:absolute;top:800px;right:540px;">Ανέβασμα Αρχείου</button>
    </form>
    <button onclick="window.location.href = 'mypage.php';" style="position:absolute;top:860px;left:600px;" class="button">Επιστροφή στην σελίδα χρήστη</button>



  <!-- Map -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js" ></script>
<div id="mapid"></div>

  <!-- Live clock -->
  <div id="clock" style="position:absolute;top:150px;right:600px;"><iframe src="https://www.zeitverschiebung.net/clock-widget-iframe-v2?language=en&size=small&timezone=Europe%2FAthens" width="100%" height="90" frameborder="0" seamless></iframe> </div>


  <h1 id="title" >cityParking</h1>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>


  <script>

/* Parking demand by hour 00:00 - 23:00*/
var cityHome = [0.75,0.55,0.46,0.7,0.2,0.39,0.55,0.67,0.8,0.95,0.9,0.2,0.83,0.7,0.62,0.7,0.62,0.74,0.8,0.8,0.7,0.92,0.66,0.69];
var cityCenter = [0.25,0.45,0.46,0.81,0.8,0.7,0.67,0.55,0.49,0.34,0.49,0.9,0.34,0.45,0.48,0.53,0.5,0.56,0.73,0.41,0.4,0.72,0.66,0.69];




    mymap = L.map('mapid').setView([40.640064, 22.944420], 14);

let osmUrl = "https://tile.openstreetmap.org/{z}/{x}/{y}.png";
let osmAttrib =
  'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors';
let osm = L.tileLayer(osmUrl, { attribution: osmAttrib });
mymap.addLayer(osm);

var response;
$.ajax({  
        type: "GET",
        url: "fetchData.php",             
        dataType: "json",            
        success: function(data)
        {
          response = data;
        },
        async:false
});
var center=[];
var parks=[];
  for(var i=0; i<=response.length-1; i++){
  var ans = [];
  var coor = response[i][0].split(" ");
  var population = response[i][1];
  var centerCoor = response[i][2];
  var myCenter = centerCoor.split(",");
  var myLat = parseFloat(myCenter[0]);
  var myLon = parseFloat(myCenter[1]);
  center.push([myLat,myLon]);
  for(var j=0; j<=coor.length-1; j++){
    var longlat = coor[j].split(",");
    ans.push([parseFloat(longlat[1]),parseFloat(longlat[0])]);
  }
  parks.push(response[i][3]);
  var h = response[i][4];
  let points = ans;

  let polygon = L.polygon(points,
  {color:"grey", fillColor: "grey"}).addTo(mymap);
  var template = '<div id="background1">\
  <form action="updateParking.php" method="post">\
  Αριθμός Πολυγώνου: '+h+'<br>\
  Θέσεις Στάθμευσης: '+parks[i]+'<br>\
  <br> Ανανέωση θέσεων Στάθμευσης: <input type="text" name="parkingspot"><br>\
  <br><input type="text" name="id" value="'+center[i]+'" style="height:0px;width:0px;left:-100px;"><br>\
  <input type="submit" name=submit value="Υποβολή">\
  </form>\
  </div>';
  polygon.bindPopup(template);

}
document.getElementById('simulation').addEventListener('click', function(evt) {
  var newData;
 $.ajax({    //create an ajax request to display.php
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
        <form action="updateParking.php" method="post">\
  Αριθμός Πολυγώνου: '+h+'<br>\
  <br> Ανανέωση θέσεων Στάθμευσης: <input type="text" name="parkingspot"><br>\
  <br><input type="text" name="id" value="'+center[i]+'" style="height:0px;width:0px;left:-100px;"><br>\
  <input type="submit" name=submit value="Υποβολή">\
  </form>\
  </div>';

        if (feature.properties && feature.properties.popupContent) {
            popupContent += feature.properties.popupContent;
        }
            layer.bindPopup(popupContent);
};
  
  }, false);


document.getElementById('delete').addEventListener('click', function() {
      $.ajax({
     url: 'delete.php',
     type: 'POST',
     success: function(response){

    }
   });
  }, false);

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
<body> 
</body>
</html>