<?php
require_once "../classes/utilities.class.php";
require_once "../classes/database.class.php";


$hour = "";
$Date = "";
$area="";

if (isset ( $_GET ["hour"] ))
	$hour = Utilities::SanitizeString ( $_GET ["hour"] );
if (isset ( $_GET ["date"] ))
	$Date = Utilities::SanitizeString ( $_GET ["date"] );
if (isset ( $_GET ["area"] ))
	$area = Utilities::SanitizeString ( $_GET ["area"] );


$headerRow="['Minute','Docks','Bikes'],";
$dataRow = "";

$db = new dbAvailability ();

if ($db->selectAvailabilityArea_Hour($Date, $hour, $area) != NULL) {
	
	$Count = $db->GetNumOfRows ();
	if($Count==0){
		Utilities::Print_NoDataAvailable();
		exit(0);
	}
	//if($Count>1){
		
	for($i = 0; $i < $Count; $i ++) {

		$dbRow = $db->FetchDataRow ();
		 

		$dataRow=$dataRow."['".$dbRow ["Minute"]."',".$dbRow ["Sum(Free)"].",".$dbRow ["Sum(Bikes)"]."]";
		if($i+1<$Count)
			$dataRow=$dataRow.",";
		 
	}
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