<?php

require_once "user.class.php";
		
class Template
{
	private $mainTitle="Bike Sharing System";
	private $Title;
	private $PathToRoot;
	
	public function __construct($title="",$pathtoroot="")
	{
		$this->Title=$title;
		$this->PathToRoot=$pathtoroot;
	}
	
	public function getPageTitle()
	{
		return "Dashboard - ".$this->Title;
	}
	
	public function getTitle()
	{
		return $this->mainTitle;
	}
	
	public function setTitle($title)
	{
		$this->Title=$title;
	}
	
	public function setPathToRoot($pathtoroot)
	{
		$this->PathToRoot=$pathtoroot;
	}
	
/*	public function getAutoNumericLibraries()
	{
		return "<script type='text/javascript' src='".$this->PathToRoot."Javascripts/jquery-1.11.1.min.js'></script>				
				</script>";
	} */
	
	public function getAutoNumericLibraries()//what is autonumeric????
	{
		return "<script type='text/javascript' src='".$this->PathToRoot."Javascripts/jquery-1.11.1.min.js'></script>
				<script type='text/javascript' src='".$this->PathToRoot."Javascripts/autoNumeric.js'></script>
				<script type='text/javascript'>
					jQuery(function($) {
   						 $('.auto').autoNumeric('init');
					});
				</script>";
	}
	
	public function getDateTimePickerLibraries()
	{
		return "<script type='text/javascript' src='".$this->PathToRoot."Javascripts/jquery-1.11.1.min.js'></script>
			<script type='text/javascript' src='".$this->PathToRoot."Javascripts/jquery-ui.min.js'></script>
			<script type='text/javascript' src='".$this->PathToRoot."Javascripts/jquery-ui-timepicker-addon.js'></script>
			<script type='text/javascript' src='".$this->PathToRoot."Javascripts/jquery-ui-sliderAccess.js'></script>";
	}
	
	public function getTimePickerScript($TimePickerId)
	{
		return "$('#".$TimePickerId."').timepicker({
					timeFormat: \"HH:mm\",
					hourMin: 0,
					hourMax: 23,
					showOn: \"both\",
					buttonImage: \"".$this->PathToRoot."Images/clock.png\",
					buttonImageOnly: true
				});";
	}
	
	private function setHTTPS()
	{
		if(User::isAuthenticated() && Configuration::$User_ActiveHttps){
			if(empty($_SERVER["HTTPS"]) || $_SERVER["HTTPS"]!="on")
			{
				header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
				exit();
			}
		}
	}
	
	private function getMenu()
	{
		
		echo "<li> <a href='".$this->PathToRoot."Locations/Districts.php'><i class='fa fa-dashboard fa-fw'></i> Dashboard</a></li> 
				
		<li><a href='#'><i class='glyphicon glyphicon-saved'></i>	Availabilities <span class='fa arrow'></span></a>
               <ul class='nav nav-second-level'>";
		$dbCity = new dbCity ();
		if ($dbCity->SelectCity() != NULL) {
		
				$City = $dbCity->FetchDataRow (); //fetching informations about the city form tbl_city if we don't have any city inserted into that table no information will be available to show!
echo"
					<li><a href='#'>" . $City ['CityName'] . " <span class='fa arrow'></span></a>
               		<ul class='nav nav-second-level'>
						<li>
							<a href='".$this->PathToRoot."KPI/HourlyAvailability.php'>Hourly</a>
						</li> 
						<li>
							<a href='".$this->PathToRoot."KPI/TimeSlotAvailability.php'>Daily Time-Slot</a>
						</li> 
						<li>
							<a href='".$this->PathToRoot."KPI/DailyAvailability.php'>Daily</a>
						</li> 
						<li>
							<a href='".$this->PathToRoot."KPI/TimePeriodAvailability.php'>Time-Period</a>
						</li> 
					</ul>
				</li>";
				
			}else{
				Utilities::Print_NoDataAvailable();// The City table must not be empty!
				exit(0);
				}	
				echo"</ul>
				</li>
			";
				
				echo"
		<li><a href='#'><i class='glyphicon glyphicon-export'></i>	Interactions <span class='fa arrow'></span></a>
               <ul class='nav nav-second-level'>";
				$dbCity = new dbCity ();
				if ($dbCity->SelectCity() != NULL) {
				
					$City = $dbCity->FetchDataRow (); //fetching informations about the city form tbl_city if we don't have any city inserted into that table no information will be available to show!
					echo"
					<li><a href='#'>" . $City ['CityName'] . " <span class='fa arrow'></span></a>
               		<ul class='nav nav-second-level'>
						<li>
							<a href='".$this->PathToRoot."KPI/HourlyUsage.php'>Hourly</a>
						</li>
						<li>
							<a href='".$this->PathToRoot."KPI/TimeSlotUsage.php'>Daily Time-Slot</a>
						</li>
						<li>
							<a href='".$this->PathToRoot."KPI/DailyUsage.php'>Daily</a>
						</li>
						<li>
							<a href='".$this->PathToRoot."KPI/TimePeriodUsage.php'>Time-Period</a>
						</li> 
					</ul>
				</li>";
				
				}else{
					Utilities::Print_NoDataAvailable();// The City table must not be empty!
					exit(0);
				}
				echo"
				</ul>
				</li>
						";
				
				
				
				echo "
				
		<li><a href='#'><i class='fa fa-signal'></i>	Area-Availabilities <span class='fa arrow'></span></a>
               <ul class='nav nav-second-level'>";
				$dbCity = new dbCity ();
				if ($dbCity->SelectCity() != NULL) {
				
					$City = $dbCity->FetchDataRow (); //fetching informations about the city form tbl_city if we don't have any city inserted into that table no information will be available to show!
					echo"
					<li><a href='#'>" . $City ['CityName'] . " <span class='fa arrow'></span></a>
               		<ul class='nav nav-second-level'>
						<li>
							<a href='".$this->PathToRoot."KPI/AreaHourlyAvailabilityMap.php'>Area - Hourly</a>
						</li>
						<li>
							<a href='".$this->PathToRoot."KPI/AreaTimeSlotAvailability.php'>Area - Daily Time-Slot</a>
						</li>
						<li>
							<a href='".$this->PathToRoot."KPI/AreaDailyAvailability.php'>Area - Daily</a>
						</li>
						<li>
							<a href='".$this->PathToRoot."KPI/AreaTimePeriodAvailability.php'>Area - Time-Period</a>
						</li>
					</ul>
				</li>";
				
				}else{
					Utilities::Print_NoDataAvailable();// The City table must not be empty!
					exit(0);
				}
				echo"</ul>
				</li>
			";
				
				echo"
		<li><a href='#'><i class='glyphicon glyphicon-refresh'></i>	Area-Interactions <span class='fa arrow'></span></a>
               <ul class='nav nav-second-level'>";
				$dbCity = new dbCity ();
				if ($dbCity->SelectCity() != NULL) {
				
					$City = $dbCity->FetchDataRow (); //fetching informations about the city form tbl_city if we don't have any city inserted into that table no information will be available to show!
					echo"
					<li><a href='#'>" . $City ['CityName'] . " <span class='fa arrow'></span></a>
               		<ul class='nav nav-second-level'>
					
						<li>
							<a href='".$this->PathToRoot."KPI/AreaTimeSlotUsage.php'>Area - Daily Time-Slot</a>
						</li>
						<li>
							<a href='".$this->PathToRoot."KPI/AreaDailyUsage.php'>Area - Daily</a>
						</li>
						
					</ul>
				</li>";
				
				}else{
					Utilities::Print_NoDataAvailable();// The City table must not be empty!
					exit(0);
				}
				echo"
				</ul>
				</li>
				
				
				
				
				
						";
				
				
	}

	private function CheckJavaScriptAndCookie()
	{
		$output = '<script type="text/javascript">
					if(isCookieEnabled()==false)
						location.href="'.$this->PathToRoot.'error.php";
					</script>';	
		$output .= '<noscript>
					<meta http-equiv="refresh" content="0; url='.$this->PathToRoot.'error.php">
				</noscript>';
		return $output;
	}

	
	public function getLoginHeader()
	{
		echo "<!DOCTYPE html>
		<html lang='en'>
		<head>		
		<meta charset='utf-8'>
 		<meta http-equiv='X-UA-Compatible' content='IE=edge'>
		<title>".$this->getPageTitle()."</title>
		<meta name='viewport' content='width=device-width, initial-scale=1'>
		<meta name='description' content=''>
		<meta name='author' content=''>
		
		<!-- Bootstrap Core CSS -->
		
		<!-- ../ change to $this->PathToRoot -->
		<link href='".$this->PathToRoot."vendor/bootstrap/css/bootstrap.min.css' rel='stylesheet'>
		
		<!-- MetisMenu CSS -->
		<link href='".$this->PathToRoot."vendor/metisMenu/metisMenu.min.css' rel='stylesheet'>
		
		<!-- Custom CSS -->
		<link href='".$this->PathToRoot."dist/css/sb-admin-2.css' rel='stylesheet'>
		
		<!-- Morris Charts CSS -->
		<link href='".$this->PathToRoot."vendor/morrisjs/morris.css' rel='stylesheet'>
		
		<!-- Custom Fonts -->
		<link href='".$this->PathToRoot."vendor/font-awesome/css/font-awesome.min.css' rel='stylesheet' type='text/css'>
		
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src='https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js'></script>
        <script src='https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js'></script>
    <![endif]-->
	 <link href=\"".$this->PathToRoot."StyleSheet/Main.css\" rel=\"stylesheet\" type=\"text/css\" />
 	 <link href=\"".$this->PathToRoot."StyleSheet/Table.css\" rel=\"stylesheet\" type=\"text/css\" />
  		
 	 <link rel=\"stylesheet\" href=\"//code.jquery.com/ui/1.11.4/themes/start/jquery-ui.css\">
 	 
 	 <!-- Time Picker -->
 	 <link href=\"".$this->PathToRoot."StyleSheet/jquery-ui-timepicker-addon.css\" rel=\"stylesheet\" type=\"text/css\" />
  
	 <!-- Date Picker -->
	 <link rel='stylesheet' href='".$this->PathToRoot."plugins/datepicker/datepicker3.css'>
  	 <!-- Daterange picker -->
 	 <link rel='stylesheet' href='".$this->PathToRoot."plugins/daterangepicker/daterangepicker-bs3.css'> 
	 
  	".$this->CheckJavaScriptAndCookie()."
</head>
";		

	echo "<body>
    <div id='wrapper'>
        <!-- Navigation -->
        <nav class='navbar navbar-default navbar-static-top' role='navigation' style='margin-bottom: 0'>
            <div class='navbar-header'>
                <button type='button' class='navbar-toggle' data-toggle='collapse' data-target='.navbar-collapse'>
                    <span class='sr-only'>Toggle navigation</span>
                    <span class='icon-bar'></span>
                    <span class='icon-bar'></span>
                    <span class='icon-bar'></span>
                </button>
                <a class='navbar-brand'><b>$this->mainTitle</b></a>
            </div>
            <!-- /.navbar-header -->
		
";
                       echo <<<END

		
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        
END;
                      	// $this->getMenu();

                      	 echo <<<END
                      	 
                        </li>
                    	<li class="header" style='text-align:center'>
END;
                      	 echo "<br><img src='".$this->PathToRoot."Images/polito.png' style='max-width: 150px; width: 60% height: 60%' /><br><br>";
                      	 //echo "<img src='".$this->PathToRoot."Images/Logo1.jpg' style='max-width: 200px; width: 100% height: 100%' /><br>";
                      	 //echo "<img src='".$this->PathToRoot."Images/Logo2.png' style='max-width: 200px; width: 100% height: 100%' /><br>";
                      	 echo <<<END
   					</li>
                    </ul>

                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>
		
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">
END;
            echo $this->Title;
            echo <<<END
            </h1>
               
END;
	}
            
	

            
	

	

	
	public function getHeader()
	{
		echo "<!DOCTYPE html>
		<html lang='en'>
		<head>		
		<meta charset='utf-8'>
 		<meta http-equiv='X-UA-Compatible' content='IE=edge'>
		<title>".$this->getPageTitle()."</title>
		<meta name='viewport' content='width=device-width, initial-scale=1'>
		<meta name='description' content=''>
		<meta name='author' content=''>
		
		<!-- Bootstrap Core CSS -->
		
		<!-- ../ change to $this->PathToRoot -->
		<link href='".$this->PathToRoot."vendor/bootstrap/css/bootstrap.min.css' rel='stylesheet'>
		
		<!-- MetisMenu CSS -->
		<link href='".$this->PathToRoot."vendor/metisMenu/metisMenu.min.css' rel='stylesheet'>
		
		<!-- Custom CSS -->
		<link href='".$this->PathToRoot."dist/css/sb-admin-2.css' rel='stylesheet'>
		
		<!-- Morris Charts CSS -->
		<link href='".$this->PathToRoot."vendor/morrisjs/morris.css' rel='stylesheet'>
		
		<!-- Custom Fonts -->
		<link href='".$this->PathToRoot."vendor/font-awesome/css/font-awesome.min.css' rel='stylesheet' type='text/css'>
		<!-- DataTables Responsive CSS -->
   		 <link href='".$this->PathToRoot."vendor/datatables-responsive/dataTables.responsive.css' rel='stylesheet'>
 	
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src='https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js'></script>
        <script src='https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js'></script>
    <![endif]-->
	 <link href=\"".$this->PathToRoot."StyleSheet/Main.css\" rel=\"stylesheet\" type=\"text/css\" />
 	 <link href=\"".$this->PathToRoot."StyleSheet/Table.css\" rel=\"stylesheet\" type=\"text/css\" />
 	 <link href=\"".$this->PathToRoot."StyleSheet/Modal.css\" rel=\"stylesheet\" type=\"text/css\" />
 	 		
  		
 	 <link rel=\"stylesheet\" href=\"//code.jquery.com/ui/1.11.4/themes/start/jquery-ui.css\">
 	 
 	 <!-- Time Picker -->
 	 <link href=\"".$this->PathToRoot."StyleSheet/jquery-ui-timepicker-addon.css\" rel=\"stylesheet\" type=\"text/css\" />
   	 		
	 <!-- Date Picker -->
	 <link rel='stylesheet' href='".$this->PathToRoot."plugins/datepicker/datepicker3.css'>
  	 <!-- Daterange picker -->
 	 <link rel='stylesheet' href='".$this->PathToRoot."plugins/daterangepicker/daterangepicker-bs3.css'>
	 
  	".$this->CheckJavaScriptAndCookie()."
  	
</head>
";		

	echo "<body>
    <div id='wrapper'>
        <!-- Navigation -->
        <nav class='navbar navbar-default navbar-static-top' role='navigation' style='margin-bottom: 0'>
            <div class='navbar-header'>
                <button type='button' class='navbar-toggle' data-toggle='collapse' data-target='.navbar-collapse'>
                    <span class='sr-only'>Toggle navigation</span>
                    <span class='icon-bar'></span>
                    <span class='icon-bar'></span>
                    <span class='icon-bar'></span>
                </button>
                <a class='navbar-brand'><b>$this->mainTitle</b></a>
            </div>
            <!-- /.navbar-header -->
		
            <ul class='nav navbar-top-links navbar-right'>
                <!-- /.dropdown -->
                <li class='dropdown'>
                    <a class='dropdown-toggle' data-toggle='dropdown' href='#'>
                        <i class='fa fa-user fa-fw'></i> <i class='fa fa-caret-down'></i>
                    </a>
                    <ul class='dropdown-menu dropdown-user'>
                       
";                       
                       echo User::getUsername();
                       echo "
                       </a>
                        </li>
                      
                  
                        <li><a href='".$this->PathToRoot."user/logout.php' onClick='return confirm(\'Are you sure you want to Sign out?\');'><i class='fa fa-sign-out fa-fw'></i> Logout</a>
";
                       echo <<<END
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->
		
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        
END;
                      	 $this->getMenu();

                      	 echo <<<END
                      	 
                        </li>
                    	<li class="header" style='text-align:center'>
END;
                      	 echo "<br><img src='".$this->PathToRoot."Images/polito.png' style='max-width: 150px; width: 60% height: 60%' /><br><br>";
                      	 //echo "<img src='".$this->PathToRoot."Images/Logo1.jpg' style='max-width: 200px; width: 100% height: 100%' /><br>";
                      	 //echo "<img src='".$this->PathToRoot."Images/Logo2.png' style='max-width: 200px; width: 100% height: 100%' /><br>";
                      	 echo <<<END
   					</li>
                    </ul>

                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>
		
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">
END;
            echo $this->Title;
            echo <<<END
            </h1>
               
END;
	}
	
	public function getFooter()
	{
		echo "
				
         </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
			</div>
			<!-- /end of page wrapper -->
				
			<!-- end of Header -->
  <footer align='center'>
    Copyright &copy; 2016 <strong>Politecnico di Torino</strong>. All rights reserved.
  </footer>
  
</div>
<!-- ./wrapper -->

    <!-- jQuery -->
    <script src='".$this->PathToRoot."vendor/jquery/jquery.min.js'></script>

    <!-- Bootstrap Core JavaScript -->
    <script src='".$this->PathToRoot."vendor/bootstrap/js/bootstrap.min.js'></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src='".$this->PathToRoot."vendor/metisMenu/metisMenu.min.js'></script>

   

    <!-- Custom Theme JavaScript -->
    <script src='".$this->PathToRoot."dist/js/sb-admin-2.js'></script>
    		
    		
    <!-- daterangepicker -->
	<script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js'></script>
	<script src='".$this->PathToRoot."plugins/daterangepicker/daterangepicker.js'></script>
	<!-- datepicker -->
	<script src='".$this->PathToRoot."plugins/datepicker/bootstrap-datepicker.js'></script>
    <script src='".$this->PathToRoot."vendor/datatables-responsive/dataTables.responsive.js'></script>
			

</body>
</html>
";

	}
	
	public function getPanelOk($Message)
	{
		$Panel="<div align='center'>
						<table class='RoundPanel' style='background-color:#A6FF88' width='350px'>
								<tr>
									<td align='center'>
										<b>".$Message."</b>
									</td>
								</tr>
							</table>
						</div><br />";
		return $Panel;
	}
	
	public function getPanelError($Message)
	{
		$Panel="<div align='center'>
						<table class='RoundPanel' style='background-color:#FCB1B1' width='350px'>
								<tr>
									<td align='center'>
										<b>".$Message."</b>
									</td>
								</tr>
							</table>
						</div><br />";
		return $Panel;
	}
}
?>