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

	
/*	$headerRow="['Minute','Pickup','Return','Total Usage/Interaction'],";
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
			$totalPickup[intval($s)]=0;
			$totalReturn[intval($s)]=0;
			//$totalUsage[intval($s)]=0;
		}
	
	
	
		for($i = 0; $i < $Count; $i ++) {
			$dbRow = $db->FetchDataRow ();
			if($i == 0){
				$currentVal=0;// number of free docks in the station
				$currentStation=$dbRow["StationName"];
				
			}
			if(strcmp($currentStation,$dbRow["StationName"])==0){

			$currentVal=intval($dbRow ["Free"]);// number of free docks in the station
				//$currentStation=$dbRow["StationName"];
			
			if($currentVal<$preVal){// if the current number of docks was less than the previous docks value(in previous report)
				$cReturn+=abs($preVal-$currentVal);// it means we have some bikes which returns to the stations so increase in number of returns
			}
			else if($currentVal>$preVal){ // it means the number of bikes pickups increases
				$cPickup+=abs($preVal-$currentVal);
				//echo"<b>Current Pickup ".$totalPickup[$cPickup]."</b></br></br>";
			} else if($currentVal==$preVal){ // it means the number of bikes pickups increases
			$cPickup+=abs($preVal-$currentVal);
			$cReturn+=abs($preVal-$currentVal);// it means we have some bikes which returns to the stations so increase in number of returns
	
			}
			
			$preVal=intval($dbRow ["Free"]);// put the current value as a next prevalue
				
				
			$totalPickup[intval($dbRow ["Minute"])]+=intval($cPickup);
			echo"<b>total pickup ".$totalPickup[intval($dbRow ["Minute"])]."</b></br></br>";
			$totalReturn[intval($dbRow ["Minute"])]+=intval($cReturn);
			echo"<b>total return ".$totalReturn[intval($dbRow ["Minute"])]."</b></br></br>";
			}else {
				$currentStation=$dbRow["StationName"];
				$currentVal=0;
				$cPickup=0;
				$cReturn=0;}
			//$totalUsage[$dbRow ["Minute"]]+=($cPickup+$cReturn);
			//$dataRow=$dataRow."['".$dbRow ["Minute"]."',".$cPickup.",".$cReturn.",".($cPickup+$cReturn)."]";
			if($i+1>$Count){
				for($t=0; $t<60; $t++){
				$dataRow=$dataRow."['".$t."',".intval($totalPickup[intval($t)]).",".intval($totalReturn[intval($t)]).",".intval(($totalPickup[intval($t)]+$totalReturn[intval($t)]))."],";
					if($t==59)
						$dataRow=rtrim($dataRow, ",");
				}
			}
			//$currentHour=intval($dbRow ["Hour"]);
			//$preVal=intval($dbRow ["Free"]);
			//$cPickup=0;
			//$cReturn=0;
		}
	
	}*/

//$headerRow="['Minute','Free','Bikes','Total'],";
$headerRow="['Minute','Pickup','Return','Total Usage/Interaction'],";
$dataRow = "";

$db = new dbAvailability ();
if ($db->selectAreaUsage_Hour ( $Date, $hour, $area ) != NULL) {
//if ($db->selectAvailability_TimeSlot ( $Date, $station, $timeslot ) != NULL) {
	
	$Count = $db->GetNumOfRows ();
	
	//if($Count>1){
	if($Count==0){
		Utilities::Print_NoDataAvailable();
		exit(0);
	}
/*	$cPickup=0;
	$cReturn=0;
	//$currentMinute=0;
	$preVal=0;
	
	for($i = 0; $i < $Count; $i ++) {
		$dbRow = $db->FetchDataRow ();
		if($i == 0){
			$currentVal=0;// number of free docks in the station
		}else{
			$currentVal=intval($dbRow ["Sum(Free)"]);// number of free docks in the station
		}
		if($currentVal<$preVal){// if the current number of docks was less than the previous docks value(in previous report)
			$cReturn+=abs($preVal-$currentVal);// it means we have some bikes which returns to the stations so increase in number of returns
		}
		else if($currentVal>$preVal){ // it means the number of bikes pickups increases
			$cPickup+=abs($preVal-$currentVal);
		} else if($currentVal==$preVal){ // it means the number of bikes pickups increases
			$cPickup+=abs($preVal-$currentVal);
			$cReturn+=abs($preVal-$currentVal);// it means we have some bikes which returns to the stations so increase in number of returns
	
		}
	
		$preVal=intval($dbRow ["Sum(Free)"]);// put the current value as a next prevalue
			
		$dataRow=$dataRow."['".$dbRow ["Minute"]."',".$cPickup.",".$cReturn.",".($cPickup+$cReturn)."]";
		if($i+1<$Count)
			$dataRow=$dataRow.",";
				
			//$currentHour=intval($dbRow ["Hour"]);
			//$preVal=intval($dbRow ["Free"]);
			$cPickup=0;
			$cReturn=0;
	}
	
	}
	
*/	$cPickup=0;
	$cReturn=0;
	$currentMinute=0;
	$preVal=0;
	
	for($i = 0; $i < $Count; $i ++) {
		$dbRow = $db->FetchDataRow ();
		
		if($i==0){
			$currentMinute=intval($dbRow ["Minute"]);
			$preVal=intval($dbRow ["Free"]);
		}
		else if($currentMinute!=intval($dbRow ["Minute"])){

			$dataRow=$dataRow."['".$currentMinute."',".$cPickup.",".$cReturn.",".($cPickup+$cReturn)."],";// here we print all the returns and pickups and total usage values for the previous hour
																										//whenever the hour is changed
			
			//if($i+1<$Count)
				//$dataRow=$dataRow.",";
			
			$currentMinute=intval($dbRow ["Minute"]);
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
	
	$dataRow=$dataRow."['".$currentMinute."',".$cPickup.",".$cReturn.",".($cPickup+$cReturn)."]";
	
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