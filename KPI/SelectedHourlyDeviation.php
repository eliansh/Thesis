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
if (isset ( $_GET ["station"] ))
	$station = Utilities::SanitizeString ( $_GET ["station"] );

$dataRowDeviationFree = "";
$dataRowDeviationBikes = "";

$db = new dbAvailability ();

if ($db->selectDeviation_Hour( $Date, $station, $hour ) != NULL) {
	
	$Count = $db->GetNumOfRows ();
	
	//if($Count>1){
	if($Count==0){
		Utilities::Print_NoDataAvailable();
		exit(0);
	}
	for($i = 0; $i < $Count; $i ++) {
		
		$dbRow = $db->FetchDataRow ();
		
		$maxFree=$dbRow ["maxFree"];
		$minFree=$dbRow ["minFree"];
		$avgFree=$dbRow ["avgFree"];
		
		$maxBikes=$dbRow ["maxBikes"];
		$minBikes=$dbRow ["minBikes"];
		$avgBikes=$dbRow ["avgBikes"];
		
		$tooltipFree="min= ".$minFree.", max= ".$maxFree.", average= ".$avgFree;
		$tooltipBikes="min= ".$minBikes.", max= ".$maxBikes.", average= ".$avgBikes;
		
		$rowDeviationFree="['".$dbRow ["Minute"]."',".$minFree.",".$avgFree.",".$avgFree.",".$maxFree.",'".$tooltipFree."']";
		$rowDeviationBikes="['".$dbRow ["Minute"]."',".$minBikes.",".$avgBikes.",".$avgBikes.",".$maxBikes.",'".$tooltipBikes."']";
		
		if ($i + 1 < $Count){
			$rowDeviationFree = $rowDeviationFree . ",";
			$rowDeviationBikes = $rowDeviationBikes . ",";
		}
		
		$dataRowDeviationFree=$dataRowDeviationFree.$rowDeviationFree;
		$dataRowDeviationBikes=$dataRowDeviationBikes.$rowDeviationBikes;
		 
	}
	
	//}else echo"<p> Null Exception! There is no data to preview! </p>";

}
?>

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

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart(){
    	  drawChartFree();
    	  drawChartBikes();
      }

      function drawChartFree(){
    	  var data = new google.visualization.DataTable();
    	  data.addColumn('string', 'Hour'); // Implicit domain label col.
    	  data.addColumn('number', 'Min');
    	  data.addColumn('number', 'Avg');
    	  data.addColumn('number', 'Avg');
    	  data.addColumn('number', 'Max');
    	  data.addColumn({type:'string', role:'tooltip'});
    	  data.addRows([
		<?php
		echo $dataRowDeviationFree;
		?>
        ]);

    	  var options = {
    			  tooltip:{
        			  textStyle:{ 
    				  fontName: 'Arial',
    				  fontSize: '12',
    				  bold: true,
    				  italic: false}},
    	          legend: 'none',
    	          colors:['blue'],          
    	          bar: { groupWidth: '100%' }, // Remove space between bars.
    	          candlestick:{
    	            fallingColor: { strokeWidth: 5 }, // red
    	            risingColor: { strokeWidth: 5 }   // green
    	          },
    	          title:'Deviation for Docks'
    	        };

        var chart = new google.visualization.CandlestickChart(document.getElementById('chartDeviationFree'));

        chart.draw(data, options);
      }

      function drawChartBikes(){
    	  var data = new google.visualization.DataTable();
    	  data.addColumn('string', 'Hour'); // Implicit domain label col.
    	  data.addColumn('number', 'Min');
    	  data.addColumn('number', 'Avg');
    	  data.addColumn('number', 'Avg');
    	  data.addColumn('number', 'Max');
    	  data.addColumn({type:'string', role:'tooltip'});
    	  data.addRows([
		<?php
		echo $dataRowDeviationBikes;
		?>
        ]);

    	  var options = {
    			  tooltip:{
        			  textStyle:{ 
    				  fontName: 'Arial',
    				  fontSize: '12',
    				  bold: true,
    				  italic: false}},
    	          legend: 'none',
    	          colors:['red'],          
    	          bar: { groupWidth: '100%' }, // Remove space between bars.
    	          candlestick:{
    	            fallingColor: { strokeWidth: 5}, // red
    	            risingColor: { strokeWidth: 5 }   // green
    	          },
    	          title:'Deviation for Bikes'
    	        };

        var chart = new google.visualization.CandlestickChart(document.getElementById('chartDeviationBikes'));

        chart.draw(data, options);
      }
      
    </script>


</head>
<body>
	<div id="chartDeviationFree" style="width: 100%; height: 400px"></div>
	<div id="chartDeviationBikes" style="width: 100%; height: 400px"></div>
</body>
</html>