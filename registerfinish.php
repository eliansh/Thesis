<?php
require_once "classes/template.class.php";
require_once "classes/utilities.class.php";


if(isset($_POST["SubmitLogin"]))
	if(Utilities::SanitizeString($_POST["SubmitLogin"])=="yes")
		Utilities::RedirectToPage('index.php');

$Template=new Template("Register finish");

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
            <div class="col-md-5 col-md-offset-3">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <p class="panel-title">Registeration Is Finished Successfully!</p>
                    </div>
                    <div class="panel-body">
   					 <p>Please press Login for logging in with your username and password.</p>
					<form action="registerfinish.php" method="post">
		    		  <div class="row">
			        <div align="center">
			          <button  type="submit" class="btn btn-success">Login</button>
			        </div>
      		</div>
			<input type="hidden" name="SubmitLogin" value="yes" />
		</form>
                    
                  
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- /.register-box -->
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
