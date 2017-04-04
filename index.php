<?php
require_once "classes/template.class.php";
require_once "classes/utilities.class.php";
require_once "classes/database.class.php";
require_once "classes/user.class.php";


$ReturnUrl="";
if(isset($_GET["retUrl"]))
	$ReturnUrl=Utilities::SanitizeString($_GET["retUrl"]);

if(User::isAuthenticated())
{
	if($ReturnUrl!="")
		Utilities::RedirectToPage($ReturnUrl);
	else
		Utilities::RedirectToPage("Locations/Districts.php");
}

$UserId = $Username = $Password = "";
$FormSubmited=false;

if(isset($_POST["txtUsername"]))
	$Username=Utilities::SanitizeString($_POST["txtUsername"]);
if(isset($_POST["txtPassword"]))
	$Password=Utilities::SanitizeString($_POST["txtPassword"]);
if(isset($_POST["SubmitLogin"]))
	if(Utilities::SanitizeString($_POST["SubmitLogin"])=="yes")
		$FormSubmited=true;

$UsernameValidationSetting="title=\"\" style=\"display: none\"";
$PasswordValidationSetting="title=\"\" style=\"display: none\"";
$ValidationMessage="";
$ShowValidation="display:none;";

if($FormSubmited){
	if(!CheckUsernameValidation($ValidationMessage,$Username,$UsernameValidationSetting) ||
			!CheckPasswordValidation($ValidationMessage,$Password,$PasswordValidationSetting))
		$ShowValidation="display:block";
	else
	{
		$User=new dbUsers();
		$LoginData=$User->CheckLogin($Username, $Password);
		if($LoginData!=NULL){
			$row = $User->FetchDataRow();
			User::setUserLogin($row['UserId'],$row['Username'],$row['FirstName'].' '.$row['LastName']);
			if($ReturnUrl!="")
				Utilities::RedirectToPage($ReturnUrl);
			else
				Utilities::RedirectToPage("Locations/Districts.php");
		}
		else
		{
			$ValidationMessage="Invalid Username or Password!";
			$ShowValidation="display:block";
		}
	}
}

function CheckUsernameValidation(&$ValidationMessage,$Username,&$UsernameValidationSetting)
{
	if(Utilities::ValidateString($Username)==false){
		$UsernameValidationSetting="title=\"No Username was entered\" style=\"display: block\"";
		$ValidationMessage.="No Username was entered.<br />";
		return false;
	}
	else
		$UsernameValidationSetting="title=\"\" style=\"display: none\"";
	return true;
}
function CheckPasswordValidation(&$ValidationMessage,$Password,&$PasswordValidationSetting)
{
	if(Utilities::ValidateString($Password)==false){
		$PasswordValidationSetting="title=\"No Password was entered\" style=\"display: block\"";
		$ValidationMessage.="No Password was entered.<br />";
		return false;
	}
	else
		$PasswordValidationSetting="title=\"\" style=\"display: none\"";

	return true;
}

$Template=new Template();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Bootstrap Core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>


<body>

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Please Sign In</h3>
                    </div>
                    <div class="panel-body">
                    
                    <form action="index.php?retUrl=<?php echo $ReturnUrl; ?>" method="post">
						<div align="center">
						<div class="ErrorPanel" id="PanelError" name="PanelError" style="<?php echo $ShowValidation; ?>">
							<p class="text-danger"><Strong>Error!</Strong></p>
								<p align="Center" id="ErrorMessage">
								<?php echo $ValidationMessage; ?>
								</p>
							</div>
							<br />
							</div>
                              <fieldset>
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Username" name="txtUsername" type="email" autofocus value="<?php echo $Username; ?>">
                                </div>
                                <div class="form-group">
                                <input class="form-control" placeholder="Password" name="txtPassword" type="password" >
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
                                <button type="submit" class="btn btn-lg btn-success btn-block">Login</button>
                                	  <input type="hidden" name="SubmitLogin" value="yes" />
                                    <br>
    								<a href="register.php" class="text-center">Register a new member?</a>

                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="vendor/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="dist/js/sb-admin-2.js"></script>



</body>
</html>
