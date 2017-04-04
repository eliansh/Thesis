<?php
require_once "../classes/utilities.class.php";
require_once "../classes/database.class.php";


$hour = "";
$Date = "";
$station = "";

if (isset ( $_GET ["hour"] ))
	$hour = Utilities::SanitizeString ( $_GET ["hour"] );
if (isset ( $_GET ["date"] ))
	$Date = Utilities::SanitizeString ( $_GET ["date"] );
if (isset ( $_GET ["area"] ))
	$area = Utilities::SanitizeString ( $_GET ["area"] );


//$headerRow="['Minute','Free','Bikes','Total'],";
$headerRow="['Minute','Pickup','Return','Total Usage/Interaction'],";
$dataRow = "";

$db = new dbAvailability ();
if ($db->selectAreaUsage_Hour ( $Date,  $hour,$area ) != NULL) {
	//if ($db->selectAvailability_TimeSlot ( $Date, $station, $timeslot ) != NULL) {

	$Count = $db->GetNumOfRows ();

	if($Count==0){
		Utilities::Print_NoDataAvailable();
		exit(0);
	}
	
	
	$cPickup=0;
	$cReturn=0;
	//$currentMinute=0;
	$preVal=0;
	$currentStation="";
	
	for($s=0; $s<60; $s++){
		$totalPickup[$s]=0;
		$totalReturn[$s]=0;
		$totalUsage[$s]=0;
	}
	
	
	
	for($i = 0; $i < $Count; $i ++) {
		$dbRow = $db->FetchDataRow ();
	//	if(strcmp(currentStation,dbRow["StationName"]==0){
			if($i == 0){
				$currentVal=0;// number of free docks in the station
			}else{
			$currentVal=intval($dbRow ["Free"]);// number of free docks in the station
			}	
			if($currentVal<$preVal){// if the current number of docks was less than the previous docks value(in previous report)
				$cReturn+=abs($preVal-$currentVal);// it means we have some bikes which returns to the stations so increase in number of returns
			}
			else if($currentVal>$preVal){ // it means the number of bikes pickups increases
				$cPickup+=abs($preVal-$currentVal);
				//echo"<b>Current Pickup ".$totalPickup[$cPickup]."</b></br></br>";
			} /*else if($currentVal==$preVal){ // it means the number of bikes pickups increases
				$cPickup+=abs($preVal-$currentVal);
				$cReturn+=abs($preVal-$currentVal);// it means we have some bikes which returns to the stations so increase in number of returns
				
			}*/
				
			$preVal=intval($dbRow ["Free"]);// put the current value as a next prevalue
			
			
			$totalPickup[intval($dbRow ["Minute"])]+=intval($cPickup);
			echo"<b>total pickup ".$totalPickup[intval($dbRow ["Minute"])]."</b></br></br>";
			$totalReturn[intval($dbRow ["Minute"])]+=intval($cReturn);
			echo"<b>total return ".$totalReturn[intval($dbRow ["Minute"])]."</b></br></br>";
				
			//$totalUsage[$dbRow ["Minute"]]+=($cPickup+$cReturn);
			//$dataRow=$dataRow."['".$dbRow ["Minute"]."',".$cPickup.",".$cReturn.",".($cPickup+$cReturn)."]";
			if($i+1>$Count){
				for($t=0; $t<60; $t++){
					$dataRow=$dataRow."['".$t."',".intval($totalPickup[intval($t)]).",".intval($totalReturn[intval($t)]).",".intval(($totalPickup[intval($t)]+$totalReturn[intval($t)]))."],";
					if($t==59)
						rtrim($dataRow, ",");
				}
			}
				//$currentHour=intval($dbRow ["Hour"]);
				//$preVal=intval($dbRow ["Free"]);
				$cPickup=0;
				$cReturn=0;
		}
	
	}
	
//	$dataRow=$dataRow."['".$dbRow ["Minute"]."',".$cPickup.",".$cReturn.",".($cPickup+$cReturn)."]";


/* $db = new dbAvailability();

if ($db->selectAvailability_Hour( $Date, $station, $hour ) != NULL) {
	
	$Count = $db->GetNumOfRows ();
	if($Count==0){
		Utilities::Print_NoDataAvailable();
		exit(0);
	}
	//if($Count>1){
	$dbTemp["Free"] =0;
	$dbUsage["Free"]=0;
	$dbPrem["Free"] =0;
	
	$dbTemp["Bikes"] =0;
	$dbUsage["Bikes"]=0;
	$dbPrem["Bikes"] =0;
	
	$dbUsage["Total"]=0;
	$dbTemp["Total"] = 0;
	
	for($i = 0; $i < $Count; $i ++) {
		$dbRow = $db->FetchDataRow ();
		$dbTemp["Free"] = abs($dbPrem["Free"] - $dbRow ["Free"]);
		$dbTemp["Bikes"] = abs($dbPrem["Bikes"] - $dbRow ["Bikes"]);
		$dbTemp["Total"] = $dbTemp["Free"]+$dbTemp["Bikes"];
		
		$dbUsage["Free"]=$dbUsage["Free"]+ $dbTemp["Free"];
		$dbUsage["Bikes"]=$dbUsage["Bikes"]+ $dbTemp["Bikes"];
		$dbUsage["Total"]=$dbUsage["Total"]+ $dbTemp["Total"];
		
		
		$dataRow=$dataRow."['".$dbRow ["Minute"]."',".$dbUsage["Free"].",".$dbUsage["Bikes"].",".$dbUsage["Total"]."]";
		if($i+1<$Count)
			$dataRow=$dataRow.",";
		
		$dbPrem["Free"] = $dbRow ["Free"];
		$dbPrem["Bikes"] = $dbRow ["Bikes"];
		 
	}
	
	//}else echo"<p> Null Exception! There is no data to preview! </p>";

}*/
?>

<script type="text/javascript"
	src="https://www.google.com/jsapi?autoload={
            'modules':[{
              'name':'visualization',
              'version':'1',
              'packages':['corechart']
            }]
          }"></script>

<style>
html, body {
	margin: 0;
	padding: 0;
	list-style: none;
}

body {
	font: normal 9pt Tahoma, Arial, Helvetica, sans-serif;
}
</style>

<script type="text/javascript">
      google.setOnLoadCallback(drawChart);

      function drawChart(){
        var data = google.visualization.arrayToDataTable([
          <?php echo $headerRow.$dataRow; ?>
        ]);

        var options = {
        		 tooltip:{
       			  textStyle:{ 
   				  fontName: 'Arial',
   				  fontSize: '12',
   				  bold: true,
   				  italic: false}},
          title: 'Number of',
          hAxis: {title: 'Minute'},
          curveType: 'none',
          legend: { position: 'right' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart'));

        chart.draw(data, options);
      }
      
    </script>


</head>
<body>
	<div id="chart" style="width: 100%; height: 400px"></div>
</body>
</html>