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
	$area= Utilities::SanitizeString ( $_GET ["area"] );


$headerRow="['Hour','Docks','Bikes'],";
$dataRow = "";

$db = new dbAvailability ();

if ($db->selectAvailabilityArea_TimeSlot ( $Date, $timeslot,$area ) != NULL) {
	
	$Count = $db->GetNumOfRows ();
	
	//if($Count>1){
	if($Count==0){
		Utilities::Print_NoDataAvailable();
		exit(0);
	}
	for($i = 0; $i < $Count; $i ++) {
		
		$dbRow = $db->FetchDataRow ();
		 

		$dataRow=$dataRow."['".$dbRow ["Hour"]."',".$dbRow ["Sum(Free)"].",".$dbRow ["Sum(Bikes)"]."]";
		if($i+1<$Count)
			$dataRow=$dataRow.",";
		 
	}
	
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
          title: 'Total Number',
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