<?php
class User
{
	public static function isAuthenticated()
	{
		$t=time();
		$diff=0;
		$new=false;
		@session_start();
		if (isset($_SESSION['User_ActiveTime'])){
			$t0=$_SESSION['User_ActiveTime'];
			$diff=($t-$t0);	// inactivity period
		} 
		else
			$new=true;
		if ($new || ($diff > Configuration::$User_SessionActiveTime)) { // new or with inactivity period
			User::UserLogOut();
			return false;
		} 
		else
			$_SESSION['User_ActiveTime']=time(); /* update time */
		return true;
	}
	
	public static function getUserId()
	{
		@session_start();
		if (isset($_SESSION['User_UserId']))
			return $_SESSION['User_UserId'];
		return 0;
	}
	
	public static function getUsername()
	{
		@session_start();
		if (isset($_SESSION['User_Username']))
			return $_SESSION['User_Username'];
		return NULL;
	}
	
	public static function getUserFullName()
	{
		@session_start();
		if (isset($_SESSION['User_FullName']))
			return $_SESSION['User_FullName'];
		return NULL;
	}
	
	public static function setUserLogin($UserId,$Username,$Name)
	{
		@session_start();
		$_SESSION['User_ActiveTime']=time();
		$_SESSION['User_UserId']=$UserId;
		$_SESSION['User_Username']=$Username;
		$_SESSION['User_FullName']=$Name;
	}
	
	public static function UserLogOut()
	{ 
		@session_start();
		$_SESSION['User_ActiveTime']=0;
		@session_unset(); 	// empty session
		@session_destroy();  // destroy session
	}
}
?>