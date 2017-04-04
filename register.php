<?php
require_once "classes/template.class.php";
require_once "classes/utilities.class.php";
require_once "classes/database.class.php";
require_once "classes/user.class.php";

if(User::isAuthenticated())
	Utilities::RedirectToPage("Locations/Area.php");

$Name = $Password = $ConfirmPassword = $Email = "";
$FormSubmited=false;

if(isset($_POST["txtEmail"]))
	$Email=Utilities::SanitizeString($_POST["txtEmail"]);
if(isset($_POST["txtPassword"]))
	$Password=Utilities::SanitizeString($_POST["txtPassword"]);
if(isset($_POST["txtConfirmPassword"]))
	$ConfirmPassword=Utilities::SanitizeString($_POST["txtConfirmPassword"]);
if(isset($_POST["txtName"]))
	$Name=Utilities::SanitizeString($_POST["txtName"]);
if(isset($_POST["SubmitRegister"]))
	if(Utilities::SanitizeString($_POST["SubmitRegister"])=="yes")
		$FormSubmited=true;

$EmailValidationSetting="title=\"\" style=\"display: none\"";
$PasswordValidationSetting="title=\"\" style=\"display: none\"";
$ConfirmPasswordValidationSetting="title=\"\" style=\"display: none\"";
$NameValidationSetting="title=\"\" style=\"display: none\"";
$ValidationMessage="";
$ShowValidation="display:none;";


if($FormSubmited){
	if(!CheckEmailValidation($ValidationMessage,$Email,$EmailValidationSetting) ||
		!CheckPasswordValidation($ValidationMessage,$Password,$ConfirmPassword,$PasswordValidationSetting,$ConfirmPasswordValidationSetting) ||
		!CheckNameValidation($ValidationMessage,$Name,$NameValidationSetting))
			$ShowValidation="display:block";
	else
	{
		$User=new dbUsers();
		if($User->Insert($Email, $Password, $Name))
			Utilities::RedirectToPage("registerfinish.php");
		else
		{
			$ValidationMessage="Error in saving data!";
			$ShowValidation="display:block";
		}
	}
}

function CheckPasswordValidation(&$ValidationMessage,$Password,$ConfirmPassword,&$PasswordValidationSetting,&$ConfirmPasswordValidationSetting)
{
	if(Utilities::ValidateString($Password)==false){
		$PasswordValidationSetting="title=\"No Password was entered\" style=\"display: block\"";
		$ValidationMessage.="No Password was entered.<br />";
		return false;
	}
	else{
		$PasswordValidationSetting="title=\"\" style=\"display: none\"";

		if(Utilities::ValidateString($ConfirmPassword)==false){
			$ConfirmPasswordValidationSetting="title=\"No Confirm Password was entered\" style=\"display: block\"";
			$ValidationMessage.="No Confirm Password was entered.<br />";
			return false;
		}
		else if($ConfirmPassword!=$Password)
		{
			$ConfirmPasswordValidationSetting="title=\"Password was not equal to Confirm Password\" style=\"display: block\"";
			$ValidationMessage.="Password was not equal to Confirm Password.<br />";
			return false;	
		}
		else
			$ConfirmPasswordValidationSetting="title=\"\" style=\"display: none\"";
	}
	return true;
}
function CheckNameValidation(&$ValidationMessage,$Name,&$NameValidationSetting)
{
	if(Utilities::ValidateString($Name)==false){
		$NameValidationSetting="title=\"No Name was entered\" style=\"display: block\"";
		$ValidationMessage.="No Name was entered.<br />";
		return false;
	}
	else
		$NameValidationSetting="title=\"\" style=\"display: none\"";
	return true;
}
function CheckEmailValidation(&$ValidationMessage,$Email,&$EmailValidationSetting)
{
	if(Utilities::ValidateString($Email)==false){
		$EmailValidationSetting="title=\"No Email was entered\" style=\"display: block\"";
		$ValidationMessage.="No Email was entered.<br />";
		return false;
	}
	
	else if (!Utilities::ValidateEmail($Email)){
		$EmailValidationSetting="title=\"The Email address is invalid\" style=\"display: block\"";
		$ValidationMessage.="The Email address is invalid.<br />";
		return false;
	}
	else{
		$User=new dbUsers();
		if($User->CheckAvailabilityOfEmail($Email)==false){
			$EmailValidationSetting="title=\"Email was already registerd\" style=\"display: block\"";
			$ValidationMessage.="Email was already registerd.<br />";
			return false;
		}
		else
			$EmailValidationSetting="title=\"\" style=\"display: none\"";
	}
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
                        <h3 class="panel-title">Register a new member</h3>
                    </div>
                    <div class="panel-body">
                    
                   		 <form action="register.php" method="post">
							<div align="center">
							<div class="ErrorPanel" id="PanelError" name="PanelError" style="<?php echo $ShowValidation; ?>">
							<p class="text-danger"><Strong>Error!</Strong></p>
							<p align="center" id="ErrorMessage">
								<?php echo $ValidationMessage; ?>
							</p>
							</div>
							<br />
							</div>
                              <fieldset>
                             	 <div>
                                <img alt="Error" src="Images/Error.png" name="imgErrPassword" <?php echo $NameValidationSetting; ?>>
                                </div>
                                <div class="form-group">
        						<input type="text" name="txtName" class="form-control" placeholder="Full name" value="<?php echo $Name; ?>">
                                </div>
                                <div>
                                <img alt="Error" src="Images/Error.png" name="imgErrPassword" <?php echo $EmailValidationSetting; ?>>
                                </div>
                                <div class="form-group">
       							 <input type="email" name="txtEmail" class="form-control" placeholder="Email" value="<?php echo $Email; ?>">
       							  <small>*example: john@yahoo.com</small>
                                </div>
                                <div>
                                <img alt="Error" src="Images/Error.png" name="imgErrPassword" <?php echo $PasswordValidationSetting; ?>>
                                </div>
                                <div class="form-group">
      							  <input type="password" name="txtPassword" class="form-control" placeholder="Password">
                                </div>
                                <div>
                                <img alt="Error" src="Images/Error.png" name="imgErrPassword" <?php echo $ConfirmPasswordValidationSetting; ?>>
                                </div>
                                <div class="form-group">
       							 <input type="password" name="txtConfirmPassword" class="form-control" placeholder="Confirm password">
       							
                                </div>
                                
                                
                                <!-- Change this to a button or input when using this as a form -->
                                <button type="submit" class="btn btn-lg btn-success btn-block">Register</button>
                                	  <input type="hidden" name="SubmitRegister" value="yes" />
                                    <br>
    								<a href="index.php" class="text-center">Already a member?</a>

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
