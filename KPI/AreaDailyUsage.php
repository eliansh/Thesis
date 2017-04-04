<?php
require_once "../classes/template.class.php";
require_once "../classes/utilities.class.php";
require_once "../classes/database.class.php";

Utilities::CheckAuthentication("../","KPI/AreaDailyUsage.php");
$TimeSlot = "00-23";
$Area ="1";
$Date="2015/09/04";


$Template = new Template ( "Area Daily Usage Report", "../" );
$Template->getHeader ();



if (isset ( $_GET ["selectedDate"] ))
	$Date = Utilities::SanitizeString ( $_GET ["selectedDate"] ); 
if (isset ( $_GET ["area"] ))
	$Area = Utilities::SanitizeString ( $_GET ["area"] );
if (isset ( $_GET ["timeslot"] ))
	$TimeSlot = Utilities::SanitizeString ( $_GET ["timeslot"] );


echo $Template->getDateTimePickerLibraries();
	
echo"
<!-- /.row -->
	<div class='row'>
		
			";
$dbCity = new dbCity ();
if ($dbCity->SelectCity() != NULL) {
	$City = $dbCity->FetchDataRow ();
	echo"			<div class='col-lg-10'>
                    <div class='panel panel-default'>
                        <div class='panel-heading'>
                        <i class='glyphicon glyphicon-map-marker'></i>
						Map of " . $City ["CityName"] . "
                        </div>
                        <!-- /.panel-heading -->
                        <div class='panel-body'>

                       <iframe height='500px' width='100%' src='../Locations/quartieri.php'> </iframe>
								<div class='table-responsive'>
                                <table class='table table-striped table-bordered table-hover'>
                                    <thead>
                                        <tr>
                                            <th>Area ID</th>
											<th>0</th>
                                            <th>1</th>
											<th>2</th>
                                            <th>3</th>
											<th>4</th>
                                            <th>5</th>
											<th>6</th>
                                            <th>7</th>
											<th>8</th>
                                            <th>9</th>
											<th>10</th>
                                            <th>11</th>
											<th>12</th>
                                            <th>13</th>
											<th>14</th>
                                            <th>15</th>
											<th>16</th>
						

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><b>Markers</b></td>

                                            <td><img src='../Images/marker/1.png' style='max-width: 150px; width: 60% height: 60%' /></td>

                                            <td><img src='../Images/marker/2.png' style='max-width: 150px; width: 60% height: 60%' /></td>

                                            <td><img src='../Images/marker/3.png' style='max-width: 150px; width: 60% height: 60%' /></td>

                                            <td><img src='../Images/marker/4.png' style='max-width: 150px; width: 60% height: 60%' /></td>
					
                                            <td><img src='../Images/marker/5.png' style='max-width: 150px; width: 60% height: 60%' /></td>

                                            <td><img src='../Images/marker/6.png' style='max-width: 150px; width: 60% height: 60%' /></td>

                                            <td><img src='../Images/marker/7.png' style='max-width: 150px; width: 60% height: 60%' /></td>

                                            <td><img src='../Images/marker/8.png' style='max-width: 150px; width: 60% height: 60%' /></td>

                                            <td><img src='../Images/marker/9.png' style='max-width: 150px; width: 60% height: 60%' /></td>

                                            <td><img src='../Images/marker/10.png' style='max-width: 150px; width: 60% height: 60%' /></td>

                                            <td><img src='../Images/marker/11.png' style='max-width: 150px; width: 60% height: 60%' /></td>
					
                                            <td><img src='../Images/marker/12.png' style='max-width: 150px; width: 60% height: 60%' /></td>
					
                                            <td><img src='../Images/marker/13.png' style='max-width: 150px; width: 60% height: 60%' /></td>
					
                                            <td><img src='../Images/marker/14.png' style='max-width: 150px; width: 60% height: 60%' /></td>
					
                                            <td><img src='../Images/marker/15.png' style='max-width: 150px; width: 60% height: 60%' /></td>
					
                                            <td><img src='../Images/marker/16.png' style='max-width: 150px; width: 60% height: 60%' /></td>

                                            <td><img src='../Images/marker/17.png' style='max-width: 150px; width: 60% height: 60%' /></td>
										</tr>
                                        <tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->

                </div>
				";

}	
echo <<<END

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
                            <i class="fa fa-bar-chart-o fa-fw"></i> Usage Chart: Area $Area on Date $Date
                            
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
      					<iframe height="1600px" width="100%" frameborder="0" scrolling="no" id='frameChart2' onload="iframeLoaded()"
						src="SelectedAreaTimeSlotUsage.php?date=$Date&area=$Area&timeslot=$TimeSlot"></iframe>                     
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