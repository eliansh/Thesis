<?php
require_once "../classes/utilities.class.php";
require_once "../classes/database.class.php";



$timeslot = "";
$DateRange = "";
$station = "";
$StartDate="";
$EndDate="";

if (isset ( $_GET ["timeslot"] ))
	$timeslot = Utilities::SanitizeString ( $_GET ["timeslot"] );
if (isset ( $_GET ["selectedDateRange"] ))
	$DateRange = Utilities::SanitizeString ( $_GET ["selectedDateRange"] );
if (isset ( $_GET ["station"] ))
	$station = Utilities::SanitizeString ( $_GET ["station"] );

$StartDate=substr($DateRange,0,10 );
$EndDate=substr($DateRange,13,10 );


$headerRow="['Date','Pickup','Return','Total Usage'],";
$dataRow = "";

$db = new dbAvailability ();
if ($db->selectUsage_TimePeriod ( $StartDate,$EndDate, $station ) != NULL) {
//if ($db->selectAvailability_TimeSlot ( $Date, $station, $timeslot ) != NULL) {
	
	$Count = $db->GetNumOfRows ();
	
	//if($Count>1){
	if($Count==0){
		Utilities::Print_NoDataAvailable();
		exit(0);
	}
	$cPickup=0;
	$cReturn=0;
	$currentDate="";
	$preVal=0;
	
	for($i = 0; $i < $Count; $i ++) {
	
		$dbRow = $db->FetchDataRow ();
		
		if($i==0){
			$currentDate=$dbRow ["Date"];
			$preVal=intval($dbRow ["Free"]);
		}
		else if($currentDate!=$dbRow ["Date"]){

			$dataRow=$dataRow."['".$currentDate."',".$cPickup.",".$cReturn.",".($cPickup+$cReturn)."],";// here we print all the returns and pickups and total usage values for the previous hour
																										//whenever the hour is changed
			
			//if($i+1<$Count)
				//$dataRow=$dataRow.",";
			
			$currentDate=$dbRow ["Date"];
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
	
	$dataRow=$dataRow."['".$currentDate."',".$cPickup.",".$cReturn.",".($cPickup+$cReturn)."]";
	
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
          title: '',
          hAxis: {title: 'Date'},
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