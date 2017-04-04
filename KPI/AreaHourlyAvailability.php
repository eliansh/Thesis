<?php
require_once "../classes/template.class.php";
require_once "../classes/utilities.class.php";
require_once "../classes/database.class.php";

Utilities::CheckAuthentication("../","KPI/AreaHourlyAvailability.php");
$Hour = "17";
$Area ="1";
$Date="2015/09/04";


$Template = new Template ( "Area Hourly Availability Report", "../" );
$Template->getHeader ();



if (isset ( $_GET ["selectedDate"] ))
	$Date = Utilities::SanitizeString ( $_GET ["selectedDate"] ); 
if (isset ( $_GET ["area"] ))
	$Area = Utilities::SanitizeString ( $_GET ["area"] );
if (isset ( $_GET ["hour"] ))
	$Hour = Utilities::SanitizeString ( $_GET ["hour"] );

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
			<h4>Select Area ID</h4>
		</div>
		</div>
		</div>
	
	<div class="panel-footer">
	<div class='form-group'>
	<select name=area class="form-control">
END;
$dbArea = new dbArea();
if ($dbArea->SelectDistinctArea() != NULL) {
	$Count = $dbArea->GetNumOfRows ();

		for($i = 0; $i < $Count; $i ++) {
		$location = $dbArea->FetchDataRow ();
		echo "<option id=" . $location ["Area_ID"] . " "; if($Area=="". $location ["Area_ID"] ."" ) echo "selected"; echo">" . $location ["Area_ID"] . "</option>";
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
	<h4>Select Hour</h4>
	</div>
	</div>
	</div>
	<div class="panel-footer">
		<div class='form-group'>
			<select name='hour' class='form-control'>
END;
						for ($i = 0; $i < 12; $i ++){
					echo"<option value='$i'"; if($Hour=="$i") echo "selected"; echo" >$i:00 AM</option>";
						}
						for ($i = 12; $i < 24; $i ++){
							echo"<option value='$i'"; if($Hour=="$i") echo "selected"; echo" >$i:00 PM</option>";
						}
						echo"</select>";
echo <<<END
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
                            <i class="fa fa-bar-chart-o fa-fw"></i> Sum Number Chart: Area $Area on Date $Date on Hour $Hour:00
                            
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
      					<iframe height="1600px" width="100%" frameborder="0" scrolling="no" id='frameChart2' onload="iframeLoaded()"
						src="SelectedAreaHourAvailability.php?date=$Date&area=$Area&hour=$Hour"></iframe>                     
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
 				</div>
			 <div class="col-lg-10">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i> Deviation Chart: Area $Area on Date $Date on Hour $Hour:00
                            
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
      					<iframe height="800px" width="100%" frameborder="0" scrolling="no" id='frameChart2' onload="iframeLoaded()"
						src="SelectedAreaHourlyDeviation.php?date=$Date&area=$Area&hour=$Hour"></iframe>                     
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