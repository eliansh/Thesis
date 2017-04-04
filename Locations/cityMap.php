<?php
require_once "../classes/utilities.class.php";
require_once "../classes/database.class.php";

$Location="";

$dbCity = new dbCity ();
if ($dbCity->SelectCity() != NULL) {
	
	$City = $dbCity->FetchDataRow ();
	$Location=$City ["Latitude"].','.$City["Longitude"];
}

?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
<meta charset="UTF-8">
<title>Map</title>
<style>
html, body, #mymap {
	height: 100%;
	margin: 0px;
	padding: 0px
}
.labels {
     color: red;
     background-color: white;
     font-family: "Lucida Grande", "Arial", sans-serif;
     font-size: 10px;
     font-weight: bold;
     text-align: center;
     width: 65px;     
     border: 2px solid black;
     white-space: nowrap;
   }
</style>
</head>
<body>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB3eQBhAtCqSKSbejg4Z9DvLNuvEBctWZM&callback=initMap"
  type="text/javascript"></script>
<script>
    function initMap(){
    	var mapDiv = document.getElementById("mymap");
    	var mapOptions = {
    			center : new google.maps.LatLng(<?php echo $Location; ?>), //how can I get latitude and longitude dynamically?
    			zoom : 12
    	};
    	var map = new google.maps.Map(mapDiv, mapOptions);
    	
    	var infowindow = new google.maps.InfoWindow();


	<?php
	
	
	$db = new dbStations();
	if ($db->SelectAll () != NULL) {
		$Count = $db->GetNumOfRows ();
		for($i = 0; $i < $Count; $i ++) {
			$usage = $db->FetchDataRow ();
			echo "var marker_" . $i . " = new google.maps.Marker({
					    position: new google.maps.LatLng(" . substr($usage ["Latitude"],0,2).".".substr($usage ["Latitude"],2,strlen($usage ["Latitude"]-2)) . "," .
					    		substr($usage ["Longitude"],0,1).".".substr($usage ["Longitude"],1,strlen($usage ["Longitude"]-1)).
					    		"),
					    map: map
					});
    				var info_" . $i . "='<b>Station name: </b>" . $usage ["StationName"] .  "';
     				google.maps.event.addListener(marker_" . $i . ", 'click', function() {
						infowindow.setContent(info_" . $i . ");
						infowindow.open(map,marker_" . $i . ");
					  });
				";
		}
	}
	?>
    
   }

//google.maps.event.addDomListener(window, 'load', initMap);

    </script>
	<div id="mymap"></div>
</body>
</html>