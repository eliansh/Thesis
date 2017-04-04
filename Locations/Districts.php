<?php
require_once "../classes/template.class.php";
require_once "../classes/utilities.class.php";
require_once "../classes/database.class.php";

Utilities::CheckAuthentication("../","Locations/Districts.php");

$Template = new Template ("Bike Stations","../");
$Template->getHeader ();

$dbCity = new dbCity ();
if ($dbCity->SelectCity() != NULL) {
	//$Count = $dbCity->GetNumOfRows();
	
	//for($i = 0; $i < $Count; $i ++) {
		$City = $dbCity->FetchDataRow ();
echo"
			<div class='row'>
                <div class='col-lg-12'>
                    <div class='panel panel-default'>
                        <div class='panel-heading'>
                           <i class='glyphicon glyphicon-map-marker'></i> 
						Map of " . $City ["CityName"] . "
                        </div>
                        <!-- /.panel-heading -->
                        <div class='panel-body'>
                       <iframe height='500px' width='100%' src='quartieri.php'> </iframe>			  
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
                
            </div>
";
	//}
}
?>

<?php
$Template->getFooter ();
?>