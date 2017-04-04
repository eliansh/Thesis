<?php
require_once "../classes/template.class.php";
require_once "../classes/utilities.class.php";
require_once "../classes/database.class.php";

Utilities::CheckAuthentication("../","KPI/TimePeriodAvailability.php");
$TimeSlot = "0-23";
$StationName ="Re Umberto";
$DateRange = "2015/09/04 - 2015/09/14";//7th July 2014 until 3rd October 2016
$StartDate ="";
$EndDate = "";

$Template = new Template ( "Time-Period Availability Report", "../" );
$Template->getHeader ();

if (isset ( $_GET ["selectedDateRange"] ))
	$DateRange = Utilities::SanitizeString ( $_GET ["selectedDateRange"] ); 
if (isset ( $_GET ["station"] ))
	$StationName = Utilities::SanitizeString ( $_GET ["station"] );
if (isset ( $_GET ["timeslot"] ))
	$TimeSlot = Utilities::SanitizeString ( $_GET ["timeslot"] );


echo $Template->getDateTimePickerLibraries();
	
echo <<<END
<!-- /.row -->
	<div class="row">
		<div class="col-lg-3 col-md-6">
		<form role='form'>
			<div class="panel panel-primary">
				<div class="panel-heading">
					<div class="row">
						<div class="col-xs-3">
						<div class="huge"><i class="fa fa-calendar"></i></div>
						</div>
					<div class="col-xs-9 text-right">
					<h4>Select Date Range</h4>
					</div>
				</div>
			</div>
	<div class="panel-footer">         		
            <div class='form-group input-group'>
            <input type='text' name='selectedDateRange' class='form-control pull-right' id='txtDate'
			value="$DateRange">
END;
			$StartDate=substr($DateRange,0,10 );
			$EndDate=substr($DateRange,13,10 );
echo <<<END
			<span class='input-group-addon'><i class='fa fa-calendar'></i></span>
        	</div>
	<div class="clearfix"></div>
	</div>
	
	</div>
	</div>
	<div class="col-lg-3 col-md-6">
		<div class="panel panel-green">
		<div class="panel-heading">
			<div class="row">
			<div class="col-xs-3">
			<div class="huge"><i class="glyphicon glyphicon-map-marker"></i></div>
		</div>
		<div class="col-xs-9 text-right">
			<h4>Select Station</h4>
		</div>
		</div>
		</div>
	
	<div class="panel-footer">
	<div class='form-group'>
	<select name=station class="form-control">
END;
$dbAvailability = new dbStations ();
if ($dbAvailability->SelectAvailableStations() != NULL) {
	$Count = $dbAvailability->GetNumOfRows ();
		for($i = 0; $i < $Count; $i ++) {
		$station = $dbAvailability->FetchDataRow ();
		echo "<option id=" . $station ["StationName"] . " "; if($StationName=="". $station ["StationName"] ."" ) echo "selected"; echo">" . $station ["StationName"] . "</option>";
			}
		}
echo <<<END
		</select>
		</div>
	<div class="clearfix"></div>

	</div>
	
	</div>
	</div>
	
	<div class="col-lg-3 col-md-6">
	<div >
	<br>
	<br>
	<button type="submit" class="btn btn-danger btn-circle btn-xl"><i class="fa fa-check"></i></button>	
	</div>
	</div>
	<!-- /.row -->
	</form>
END;
	

?>


<?php
echo <<<END
		
                <div class="col-lg-10">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i> Average Number Chart: Station $StationName between Dates $DateRange</div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
	  						<iframe height='1600px' width='100%' frameborder='0' scrolling='no' id='frameChart2' onload='iframeLoaded()'
						src='SelectedTimePeriodAvailability.php?selectedDateRange=$DateRange&station=$StationName'></iframe>                     
      					  </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
 				</div>
				<div class="col-lg-10">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i> Deviation Chart: Station $StationName between Dates $DateRange</div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
	  						<iframe height='800px' width='100%' frameborder='0' scrolling='no' id='frameChart2' onload='iframeLoaded()'
						src='SelectedTimePeriodDeviation.php?selectedDateRange=$DateRange&station=$StationName'></iframe>                     
      					  </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
 				</div>
		
	
END;
?>

<?php
$Template->getFooter ();
?>
<script>

$(function() {

	//Date range picker 7th July 2014 until 3rd October 2016
    $('#txtDate').daterangepicker({format: 'YYYY/MM/DD', 
    	minDate: "2014/07/07", startDate: "<?php echo $StartDate ?>",
    	maxDate: "2016/10/03", endDate: "<?php echo $EndDate ?>"
    		
	}
    );
  });
  
function iframeLoaded() {
    var iFrameID = document.getElementById('frameChart2');
    if(iFrameID) {
          // here you can make the height, I delete it first, then I make it again
          iFrameID.height = "";
          iFrameID.height = iFrameID.contentWindow.document.body.scrollHeight + "px";
    }   
}
</script>