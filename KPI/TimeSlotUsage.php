<?php
require_once "../classes/template.class.php";
require_once "../classes/utilities.class.php";
require_once "../classes/database.class.php";

Utilities::CheckAuthentication("../","KPI/TimeSlotUsage.php");
$TimeSlot = "17-20";
$StationName ="Re Umberto";
$Date="2015/09/04";


$Template = new Template ( "Time-Slot Interaction Report", "../" );
$Template->getHeader ();



if (isset ( $_GET ["selectedDate"] ))
	$Date = Utilities::SanitizeString ( $_GET ["selectedDate"] ); 
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
					<h4>Select Date</h4>
					</div>
				</div>
			</div>
	<div class="panel-footer">
		<div class='form-group input-group'>
            <input type='text' name='selectedDate' class='form-control datepicker'
			value='$Date'>
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
$dbAvailability = new dbStations();
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
	<div class="panel panel-yellow">
	<div class="panel-heading">
	<div class="row">
	<div class="col-xs-3">
	<div class="huge"><i class="fa fa-clock-o"></i></div>
	</div>
	<div class="col-xs-9 text-right">
	<h4>Select TimeSlot</h4>
	</div>
	</div>
	</div>
	<div class="panel-footer">
		<div class='form-group'>
			<select name='timeslot' class='form-control'>
END;
			echo"<option value='0-11'"; if($TimeSlot=="0-11") echo "selected"; echo" >Morning (from 00:00 until 12:00)</option>
				<option value='12-16'";if($TimeSlot=="12-16") echo "selected"; echo">Afternoon (from 12:00 until 17:00)</option>
				<option value='17-20'";if($TimeSlot=="17-20") echo "selected"; echo">Evening (from 17:00 until 21:00)</option>
				<option value='21-23'";if($TimeSlot=="21-23") echo "selected"; echo">Night (from 21:00 until 00:00)</option>
			</select>
			</div>";
echo <<<END
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
echo"
		
                <div class='col-lg-10'>
                    <div class='panel panel-default'>
                        <div class='panel-heading'>
                            <i class='fa fa-bar-chart-o fa-fw'></i> Station $StationName , Date $Date , TimeSlot "; $tt=str_replace("-",":00 - ",$TimeSlot); echo"$tt:00
                            
                        </div>
                        <!-- /.panel-heading -->
                        <div class='panel-body'>
      					<iframe height='1600px' width='100%' frameborder='0' scrolling='no' id='frameChart2' onload='iframeLoaded()'
						src='SelectedTimeSlotUsage.php?date=$Date&station=$StationName&timeslot=$TimeSlot'></iframe>                     
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
 				</div>
		
	
";
?>

<?php
$Template->getFooter ();
?>
<script>

$(function() {

    $('.datepicker').datepicker({
        format: "yyyy/mm/dd",
        autoclose: true
    });
  });

function selectSensor(sensorId){
	  document.getElementById("frameChart").contentWindow.selectSensor(sensorId);
}
  
function iframeLoaded() {
    var iFrameID = document.getElementById('frameChart2');
    if(iFrameID) {
          // here you can make the height, I delete it first, then I make it again
          iFrameID.height = "";
          iFrameID.height = iFrameID.contentWindow.document.body.scrollHeight + "px";
    }   
}
</script>