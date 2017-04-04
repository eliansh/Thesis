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
$StartTime=substr($timeslot,0,2 );
$EndTime=substr($timeslot,3,2 );

$db = new dbAvailability ();
if ($db->selectAreaUsage_TimeSlot ( $Date, $timeslot,$area ) != NULL) {
//if ($db->selectAvailability_TimeSlot ( $Date, $station, $timeslot ) != NULL) {
	
	$Count = $db->GetNumOfRows ();
	
	//if($Count>1){
	if($Count==0){
		Utilities::Print_NoDataAvailable();
		exit(0);
	}

	
	$cPickup=0;
	$cReturn=0;
	$currentHour=0;
	$preVal=0;
	for($s=intval($StartTime); $s<=intval($EndTime); $s++){
		$totalPickup[$s]=0;
		$totalReturn[$s]=0;
		$totalUsage[$s]=0;
	}
	$currentStation="";
	for($i = 0; $i < $Count; $i ++) {
		$dbRow = $db->FetchDataRow ();
		
		if($i==0){
			$currentHour=intval($dbRow ["Hour"]);
			$preVal=intval($dbRow ["Free"]);
		}
		else if($currentHour!=intval($dbRow ["Hour"])){

			$totalPickup[intval($dbRow ["Hour"])]+=intval($cPickup);
			$totalReturn[intval($dbRow ["Hour"])]+=intval($cReturn);
			//$dataRow=$dataRow."['".$currentHour."',".$cPickup.",".$cReturn.",".($cPickup+$cReturn)."],";// here we print all the returns and pickups and total usage values for the previous hour
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
	
	$totalPickup[intval($dbRow ["Hour"])]+=intval($cPickup);
	$totalReturn[intval($dbRow ["Hour"])]+=intval($cReturn);
	
	for($t=intval($StartTime); $t<=intval($EndTime); $t++){
		$dataRow=$dataRow."['".$t."',".intval($totalPickup[intval($t)]).",".intval($totalReturn[intval($t)]).",".intval(($totalPickup[intval($t)]+$totalReturn[intval($t)]))."],";
					if($t==intval($EndTime))
						rtrim($dataRow, ",");
	}
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