<?php
require_once "../classes/utilities.class.php";
require_once "../classes/database.class.php";


$timeslot = "";
$Date = "";
$area = "";

if (isset ( $_GET ["timeslot"] ))
	$timeslot = Utilities::SanitizeString ( $_GET ["timeslot"] );
if (isset ( $_GET ["date"] ))
	$Date = Utilities::SanitizeString ( $_GET ["date"] );
if (isset ( $_GET ["area"] ))
	$area = Utilities::SanitizeString ( $_GET ["area"] );


$headerRow="['Hour','Pickup','Return','Total Usage'],";
$dataRow = "";

$db = new dbAvailability ();
if ($db->selectNewAreaUsage_TimeSlot( $Date, $timeslot,$area ) != NULL) {
//if ($db->selectAvailability_TimeSlot ( $Date, $station, $timeslot ) != NULL) {
	
	$Count = $db->GetNumOfRows ();
	
	//if($Count>1){
	if($Count==0){
		Utilities::Print_NoDataAvailable();
		exit(0);
	}
	/*
	$dbTemp["Free"] =0;
	$dbUsage["Free"]=0;
	$dbPrem["Free"] =0;
	
	$dbTemp["Bikes"] =0;
	$dbUsage["Bikes"]=0;
	$dbPrem["Bikes"] =0;
	
	$dbUsage["Total"]=0;
	$dbTemp["Total"] = 0;
	$currentHour = -1;
	$equal = 0;
	for($i = 0; $i < $Count; $i ++) {
		$dbRow = $db->FetchDataRow ();
		
		$dbTemp["Free"] = abs($dbPrem["Free"] - $dbRow ["Free"]);
		$dbTemp["Bikes"] = abs($dbPrem["Bikes"] - $dbRow ["Bikes"]);
		$dbTemp["Total"] = $dbTemp["Free"]+$dbTemp["Bikes"];
		
		$dbUsage["Free"]=$dbUsage["Free"]+ $dbTemp["Free"];
		$dbUsage["Bikes"]=$dbUsage["Bikes"]+ $dbTemp["Bikes"];
		$dbUsage["Total"]=$dbUsage["Total"]+ $dbTemp["Total"];
		
		if($currentHour != $dbRow["Hour"]){
			//echo" <b>{'$currentHour' != '".$dbRow['Hour']."' }</b>";
			$dataRow=$dataRow."['".$dbRow ["Hour"]."',".$dbUsage["Free"].",".$dbUsage["Bikes"].",".$dbUsage["Total"]."]";
		}else{
			//echo" <b>('.$currentHour.' == '".$dbRow['Hour']."' )</b>";
			$dataRow=$dataRow."['',".$dbUsage["Free"].",".$dbUsage["Bikes"].",".$dbUsage["Total"]."]";
		}

		
			
		if($i+1<$Count)
			$dataRow=$dataRow.",";
		
		$currentHour = $dbRow ["Hour"];
		$dbPrem["Free"] = $dbRow ["Free"];
		$dbPrem["Bikes"] = $dbRow ["Bikes"];
	} */
		
	/*	$dbTemp["Free"] =0;
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
		
		
			$dataRow=$dataRow."['".$dbRow ["Hour"]."',".$dbUsage["Free"].",".$dbUsage["Bikes"].",".$dbUsage["Total"]."]";
			if($i+1<$Count)
				$dataRow=$dataRow.",";
		
				$dbPrem["Free"] = $dbRow ["Free"];
				$dbPrem["Bikes"] = $dbRow ["Bikes"];
					
		} */
	
	
	$cPickup=0;
	$cReturn=0;
	$currentHour=0;
	$preVal=0;
	
	for($i = 0; $i < $Count; $i ++) {
		$dbRow = $db->FetchDataRow ();
		
		if($i==0){
			$currentHour=intval($dbRow ["Hour"]);
			$preVal=intval($dbRow ["Free"]);
		}
		else if($currentHour!=intval($dbRow ["Hour"])){

			$dataRow=$dataRow."['".$currentHour."',".$cPickup.",".$cReturn.",".($cPickup+$cReturn)."],";// here we print all the returns and pickups and total usage values for the previous hour
																										//whenever the hour is changed
			
			//if($i+1<$Count)
				//$dataRow=$dataRow.",";
			
			$currentHour=intval($dbRow ["Hour"]);
			$preVal=intval($dbRow ["Free"]);
			$cPickup=0;
			$cReturn=0;
		}
		else{
			$currentVal=intval($dbRow ["Free"]);
			
			if($currentVal<$preVal){
				$cReturn+=abs($preVal-$currentVal);//increase in number of bikes means more bikes available so less bike docks
			}
			else if($currentVal>$preVal){
				$cPickup+=abs($preVal-$currentVal);
			}else if($currentVal==$preVal){
				$cReturn+=abs($preVal-$currentVal);
				$cPickup+=abs($preVal-$currentVal);
				
			}
			
			$preVal=$currentVal;
		}
				
	} 
	
	$dataRow=$dataRow."['".$currentHour."',".$cPickup.",".$cReturn.",".($cPickup+$cReturn)."]";
	
	//}else echo"<p> Null Exception! There is no data to preview! </p>";
		
		//}else echo"<p> Null Exception! There is no data to preview! </p>";

}
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
          title: 'Average Number',
          hAxis: {title: 'Hour'},
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