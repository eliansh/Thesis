<?php
require_once "../classes/utilities.class.php";
require_once "../classes/user.class.php";

User::UserLogOut();
Utilities::RedirectToLoginPage("../");

?>