<?php
@include 'config.php';
@include '../config.php';

class MySqlConnection
{
	public static function CreateConnection()
	{
		$conn= mysqli_connect(Configuration::$DB_HostName, Configuration::$DB_Username, Configuration::$DB_Password, Configuration::$DB_Database);
		if (!$conn)
			die("Unable to connect to MySQL!<br>Error no: " . mysqli_connect_errno() . '<br>'. mysqli_connect_error());
		return $conn;
	}
}

class DB_RUN
{
	private $QueryResource;

	public $HasTransaction;
	public $DB_Connection;
	
	function __construct()
	{
		$this->HasTransaction=false;
	}
	
	protected function ConnectToDB()
	{
		$this->DB_Connection = MySqlConnection::CreateConnection();
	}
	
	protected function CloseDBConnection()
	{
		mysqli_close($this->DB_Connection);
	}
	
	protected function ExecuteQuery($Query)
	{
		if($this->HasTransaction==false)
			$this->ConnectToDB();
		
		$this->QueryResource = mysqli_query($this->DB_Connection, $Query);
		
		if($this->HasTransaction==false)
			$this->CloseDBConnection();
		
		//echo $Query;
		
		if (!$this->QueryResource)
			die ("<br>Operation failed<br>");
		
		return $this->QueryResource;
	}
	
	public function GetNumOfRows()
	{
		return mysqli_num_rows($this->QueryResource);
	}
	
	public function FetchDataRow()
	{
		return mysqli_fetch_array($this->QueryResource);//function fetches a result row as an associative array, a numeric array, or both
	}
	
	public function FreeQueryResource()//mysql_free_result() will free all memory associated with the result identifier result.
	{
		mysqli_free_result($this->QueryResource);
	}
	
	protected function sanitizeMySQL($var)
	{
		$result='';
		if($this->HasTransaction==false)
			$this->ConnectToDB();
		
		$result=mysqli_real_escape_string($this->DB_Connection,$var);//Escapes special characters in a string for use in an SQL statement, taking into account the current charset of the connection
		
		if($this->HasTransaction==false)
			$this->CloseDBConnection();
		return $result;
	}
}

class dbTransaction
{
	protected $DB_Connection;
	
	function __construct()
	{
		$this->ConnectToDB();
	}
	
	private function ConnectToDB()
	{
		$this->DB_Connection = MySqlConnection::CreateConnection();
	}
	
	private function CloseDBConnection()
	{
		mysqli_close($this->DB_Connection);
	}
	
	public function ADD(DB_RUN $obj)
	{
		$obj->HasTransaction=true;
		$obj->DB_Connection=$this->DB_Connection;
	}
	
	public function BeginSave()
	{
		mysqli_autocommit($this->DB_Connection, false);
	}
	
	public function EndSave()
	{
		mysqli_commit($this->DB_Connection);
		$this->CloseDBConnection();
	}
	
	public function CancelSave()
	{
		mysqli_rollback($this->DB_Connection);
		$this->CloseDBConnection();
	}
}

class dbAvailability extends DB_RUN
{
	function __construct()

	{
		parent::__construct();
	}
	
	public function SelectAvailablity()
	{
		$Query="SELECT * FROM v_availability";
		$result=parent::ExecuteQuery($Query);
		if(!$result)
			return NULL;
			return $result;
	}

	public function SelectAll()
	{
		$Query="SELECT * FROM v_availability JOIN tbl_stations ON v_availability.StationName = tbl_stations.StationName";
		$result=parent::ExecuteQuery($Query);
		if(!$result)
			return NULL;
			return $result;
	}
	
	/*************************************************Area Availability*********************************/
	
	public function selectAvailabilityArea_TimeSlot($Date,$TimeSlot,$Area_ID){
		$Query="SELECT Sum(Free),Sum(Bikes),Hour FROM v_area
			where Date='".$Date."' and Area_ID='".$Area_ID."' and Hour between ".str_replace("-"," and ",$TimeSlot)."  Group BY Hour";
		/*$Query="SELECT sum(Free) as Free,Sum(Bikes) as Bikes,Hour FROM v_availability
		 where Date='".$Date."' and Hour between ".str_replace("-"," and ",$TimeSlot)." and StationName='".$Station."'
		 group by Hour";*/
		//echo $Query;
		$result=parent::ExecuteQuery($Query);
		if(!$result)
			return NULL;
			return $result;
	}
	
	
	
	public function selectAvailabilityArea_TimePeriod($StartDate,$EndDate,$Area_ID){
		$Query="SELECT Sum(Free),Sum(Bikes),Date FROM v_area
			where Date between '".$StartDate."' and '".$EndDate."' and Area_ID='".$Area_ID."'  Group BY Date";
		/*$Query="SELECT sum(Free) as Free,Sum(Bikes) as Bikes,Hour FROM v_availability
		 where Date='".$Date."' and Hour between ".str_replace("-"," and ",$TimeSlot)." and StationName='".$Station."'
		 group by Hour";*/
		//echo $Query;
		$result=parent::ExecuteQuery($Query);
		if(!$result)
			return NULL;
			return $result;
	}
	
	
	
	public function selectAvailabilityArea_Hour($Date,$Hour,$Area_ID){
		$Query="SELECT Sum(Free),Sum(Bikes),Minute FROM v_area
			where Date='".$Date."' and Area_ID='".$Area_ID."' and Hour='".$Hour."' Group BY Minute";
	
		$result=parent::ExecuteQuery($Query);
		if(!$result)
			return NULL;
			return $result;
	}
	
		
	/*************************************************Availability*********************************/
	
		public function selectAvailability_TimeSlot($Date,$Station,$TimeSlot){
			$Query="SELECT Avg(Free),Avg(Bikes),Hour FROM v_availability
			where Date='".$Date."' and Hour between ".str_replace("-"," and ",$TimeSlot)." and StationName='".$Station."' Group BY Hour";
			/*$Query="SELECT sum(Free) as Free,Sum(Bikes) as Bikes,Hour FROM v_availability
			where Date='".$Date."' and Hour between ".str_replace("-"," and ",$TimeSlot)." and StationName='".$Station."' 
			group by Hour";*/
			//echo $Query;
			$result=parent::ExecuteQuery($Query);
			if(!$result)
				return NULL;
			return $result;
		}
		
		
		
		public function selectAvailability_TimePeriod($StartDate,$EndDate,$Station){
			$Query="SELECT Avg(Free),Avg(Bikes),Date FROM v_availability
			where Date between '".$StartDate."' and '".$EndDate."' and StationName='".$Station."' Group BY Date";
			/*$Query="SELECT sum(Free) as Free,Sum(Bikes) as Bikes,Hour FROM v_availability
			 where Date='".$Date."' and Hour between ".str_replace("-"," and ",$TimeSlot)." and StationName='".$Station."'
			 group by Hour";*/
			//echo $Query;
			$result=parent::ExecuteQuery($Query);
			if(!$result)
				return NULL;
				return $result;
		}
		
		
		
		public function selectAvailability_Hour($Date,$Station,$Hour){
			$Query="SELECT Avg(Free),Avg(Bikes),Minute FROM v_availability
			where Date='".$Date."' and Hour='".$Hour."' and StationName='".$Station."' Group BY Minute";
		
			$result=parent::ExecuteQuery($Query);
			if(!$result)
				return NULL;
				return $result;
		}
		
		
		/*************************************************Area Deviation*********************************/
		
		public function selectAreaDeviation_Hour($Date,$Hour,$Area_ID){
			$Query="SELECT Avg(Free) as avgFree,Avg(Bikes) as avgBikes,Min(Free) as minFree,
					Min(Bikes) as minBikes,Max(Free) as maxFree,Max(Bikes) as maxBikes,Minute FROM v_area
			where Date='".$Date."' and Hour='".$Hour."' and Area_ID='".$Area_ID."' Group BY Minute";
		
			$result=parent::ExecuteQuery($Query);
			if(!$result)
				return NULL;
				return $result;
		}
		
		public function selectAreaDeviation_TimePeriod($StartDate,$EndDate,$Area_ID){
			$Query="SELECT Avg(Free) as avgFree,Avg(Bikes) as avgBikes,Min(Free) as minFree,
					Min(Bikes) as minBikes,Max(Free) as maxFree,Max(Bikes) as maxBikes,Date FROM v_availability
			where Date between '".$StartDate."' and '".$EndDate."' and StationName='".$Station."' Group BY Date";
			/*$Query="SELECT sum(Free) as Free,Sum(Bikes) as Bikes,Hour FROM v_availability
			 where Date='".$Date."' and Hour between ".str_replace("-"," and ",$TimeSlot)." and StationName='".$Station."'
			 group by Hour";*/
			//echo $Query;
			$result=parent::ExecuteQuery($Query);
			if(!$result)
				return NULL;
				return $result;
		}
		
		public function selectAreaDeviation_TimeSlot($Date,$TimeSlot,$Area_ID){
			$Query="SELECT Avg(Free) as avgFree,Avg(Bikes) as avgBikes,Min(Free) as minFree,
					Min(Bikes) as minBikes,Max(Free) as maxFree,Max(Bikes) as maxBikes,Hour
					FROM v_availability
			where Date='".$Date."' and Hour between ".str_replace("-"," and ",$TimeSlot)." and StationName='".$Station."' Group BY Hour";
			/*$Query="SELECT sum(Free) as Free,Sum(Bikes) as Bikes,Hour FROM v_availability
			 where Date='".$Date."' and Hour between ".str_replace("-"," and ",$TimeSlot)." and StationName='".$Station."'
			 group by Hour";*/
			//echo $Query;
			$result=parent::ExecuteQuery($Query);
			if(!$result)
				return NULL;
				return $result;
		}	
	/*************************************************Deviation*********************************/	
		
		public function selectDeviation_Hour($Date,$Station,$Hour){
			$Query="SELECT Avg(Free) as avgFree,Avg(Bikes) as avgBikes,Min(Free) as minFree,
					Min(Bikes) as minBikes,Max(Free) as maxFree,Max(Bikes) as maxBikes,Minute FROM v_availability
			where Date='".$Date."' and Hour='".$Hour."' and StationName='".$Station."' Group BY Minute";
		
			$result=parent::ExecuteQuery($Query);
			if(!$result)
				return NULL;
				return $result;
		}
		
		public function selectDeviation_TimePeriod($StartDate,$EndDate,$Station){
			$Query="SELECT Avg(Free) as avgFree,Avg(Bikes) as avgBikes,Min(Free) as minFree,
					Min(Bikes) as minBikes,Max(Free) as maxFree,Max(Bikes) as maxBikes,Date FROM v_availability
			where Date between '".$StartDate."' and '".$EndDate."' and StationName='".$Station."' Group BY Date";
			/*$Query="SELECT sum(Free) as Free,Sum(Bikes) as Bikes,Hour FROM v_availability
			 where Date='".$Date."' and Hour between ".str_replace("-"," and ",$TimeSlot)." and StationName='".$Station."'
			 group by Hour";*/
			//echo $Query;
			$result=parent::ExecuteQuery($Query);
			if(!$result)
				return NULL;
				return $result;
		}
		
		public function selectDeviation_TimeSlot($Date,$Station,$TimeSlot){
			$Query="SELECT Avg(Free) as avgFree,Avg(Bikes) as avgBikes,Min(Free) as minFree,
					Min(Bikes) as minBikes,Max(Free) as maxFree,Max(Bikes) as maxBikes,Hour
					FROM v_availability
			where Date='".$Date."' and Hour between ".str_replace("-"," and ",$TimeSlot)." and StationName='".$Station."' Group BY Hour";
			/*$Query="SELECT sum(Free) as Free,Sum(Bikes) as Bikes,Hour FROM v_availability
			 where Date='".$Date."' and Hour between ".str_replace("-"," and ",$TimeSlot)." and StationName='".$Station."'
			 group by Hour";*/
			//echo $Query;
			$result=parent::ExecuteQuery($Query);
			if(!$result)
				return NULL;
				return $result;
		}
		
		/*************************************************Usage*********************************/
		
		public function selectUsage_TimeSlot($Date,$Station,$TimeSlot){
			$Query="SELECT distinct Free,Bikes,Hour,Minute FROM v_availability
			where Date='".$Date."' and Hour between ".str_replace("-"," and ",$TimeSlot)." and StationName='".$Station."' ORDER by Hour,Minute";
			//echo $Query;
			$result=parent::ExecuteQuery($Query);
			if(!$result)
				return NULL;
			return $result;
		}
		
		
		public function selectUsage_TimePeriod($StartDate,$EndDate,$Station){
			$Query="SELECT distinct Free,Bikes,Hour,Minute,Date FROM v_availability
			where Date between '".$StartDate."' and '".$EndDate."' and StationName='".$Station."' ORDER by Date,Hour";
			$result=parent::ExecuteQuery($Query);
			if(!$result)
				return NULL;
				return $result;
		}
		
		public function selectUsage_Hour($Date,$Station,$Hour){
			$Query="SELECT distinct Free,Bikes,Hour,Minute FROM v_availability
			where Date='".$Date."' and Hour = '".$Hour."' and StationName='".$Station."' ORDER by Minute";
			//echo $Query;
			$result=parent::ExecuteQuery($Query);
			if(!$result)
				return NULL;
				return $result;
		}
		
		/*************************************************Area Usage*********************************/
		
		public function selectAreaUsage_TimeSlot($Date,$TimeSlot,$Area_ID){
			$Query="SELECT  Distinct Free,Bikes,Hour,Minute,StationName FROM v_area
			where Date='".$Date."' and Hour between ".str_replace("-"," and ",$TimeSlot)." and Area_ID='".$Area_ID."' ORDER by StationName,Hour,Minute";
			//echo $Query;
			$result=parent::ExecuteQuery($Query);
			if(!$result)
				return NULL;
				return $result;
		}
		public function selectNewAreaUsage_TimeSlot($Date,$TimeSlot,$Area_ID){
			$Query="SELECT  Distinct Free,Bikes,Hour,Minute,StationName FROM v_area
			where Date='".$Date."' and Hour between ".str_replace("-"," and ",$TimeSlot)." and Area_ID='".$Area_ID."' ORDER by Hour,Minute,StationName";
			//echo $Query;
			$result=parent::ExecuteQuery($Query);
			if(!$result)
				return NULL;
				return $result;
		}
		
		public function selectAreaUsage_TimePeriod($StartDate,$EndDate,$Area_ID){
			$Query="SELECT distinct Free,Bikes,Hour,Minute,Date,StationName FROM v_area
			where Date between '".$StartDate."' and '".$EndDate."' and Area_ID='".$Area_ID."' ORDER by Date,Hour,StationName";
			$result=parent::ExecuteQuery($Query);
			if(!$result)
				return NULL;
				return $result;
		}
		
		/*public function selectAreaUsage_Hour($Date,$Hour,$Area_ID){
			$Query="SELECT  Sum(Free),Sum(Bikes),Hour,Minute,StationName FROM v_area
			where Date='".$Date."' and Hour = '".$Hour."' and Area_ID='".$Area_ID."' Group by Minute";
			//echo $Query;
			$result=parent::ExecuteQuery($Query);
			if(!$result)
				return NULL;
				return $result;
		}*/
		public function selectAreaUsage_Hour($Date,$Hour,$Area_ID){
			$Query="SELECT  Free,Bikes,Hour,Minute,StationName FROM v_area
			where Date='".$Date."' and Hour = '".$Hour."' and Area_ID='".$Area_ID."' Group by Minute,StationName";
			//echo $Query;
			$result=parent::ExecuteQuery($Query);
			if(!$result)
				return NULL;
				return $result;
		}
		
		
		
}


class dbStations extends DB_RUN
{
	function __construct()

	{
		parent::__construct();
	}

	
	public function SelectAll()
	{
		$Query="SELECT PK_ID,StationName,Latitude,Longitude FROM tbl_stations";
		$result=parent::ExecuteQuery($Query);
		if(!$result)
			return NULL;
			return $result;
	}

	public function SelectAvailableStations()
	{
		$Query="SELECT StationName FROM tbl_stations";
		$result=parent::ExecuteQuery($Query);
		if(!$result)
			return NULL;
			return $result;
	}
	
	public function SelectAvailableStationsId()
	{
		$Query="SELECT StationId FROM tbl_stations";
		$result=parent::ExecuteQuery($Query);
		if(!$result)
			return NULL;
			return $result;
	}
	
}

class dbArea extends DB_RUN
{
	function __construct()

	{
		parent::__construct();
	}


	public function SelectAll()
	{
		$Query="SELECT PK_ID,StationName,Latitude,Longitude,Area_ID FROM tbl_stations_area";
		$result=parent::ExecuteQuery($Query);
		if(!$result)
			return NULL;
			return $result;
	}

	public function SelectStations($AreaId)
	{
		$Query="SELECT StationName,Latitude,Longitude,Area_ID FROM tbl_stations_area WHERE Area_ID='".$AreaId."'";
		$result=parent::ExecuteQuery($Query);
		if(!$result)
			return NULL;
			return $result;
	}

	public function SelectDistinctArea()
	{
		$Query="SELECT DISTINCT Area_ID FROM tbl_stations_area ORDER BY Area_ID";
		$result=parent::ExecuteQuery($Query);
		if(!$result)
		return NULL;
		return $result;
	}
	
	public function SelectCountArea()
	{
		$Query="SELECT Count(DISTINCT Area_ID) FROM tbl_stations_area";
		$result=parent::ExecuteQuery($Query);
		if(!$result)
			return NULL;
			return $result;
	}

	public function SelectArea($StationName)
	{
	$Query="SELECT StationName,Latitude,Longitude,Area_ID FROM tbl_stations_area WHERE StationName='".StationName."' ";
	$result=parent::ExecuteQuery($Query);
	if(!$result)
	return NULL;
	return $result;
	}

	}
	

class dbCity extends DB_RUN
{
	function __construct()

	{
		parent::__construct();
	}


	public function SelectCity()
	{
		$Query="SELECT * FROM tbl_city";
		$result=parent::ExecuteQuery($Query);
		if(!$result)
			return NULL;
			return $result;
	}
}

class dbUsers extends DB_RUN
{
	function __construct()
	{
		parent::__construct();
	}
	
	private function EncryptPassword($Password)
	{
		return sha1("qW&hI+*".$Password."@2014"); // encrypt salted password!
	}
	
	public function GeneratePassword($length=9)
	{
		$sets = array();
		$sets[] = 'abcdefghjkmnpqrstuvwxyz';
		$sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
		$sets[] = '23456789';
		
		$all = '';
		$password = '';
		foreach($sets as $set)
		{
			$password .= $set[array_rand(str_split($set))];
			$all .= $set;
		}
		
		$all = str_split($all);
		for($i = 0; $i < $length - count($sets); $i++)
			$password .= $all[array_rand($all)];
		
		$password = str_shuffle($password);
		
		return $password;
	}
	
	public function CheckAvailabilityOfEmail($Email)
	{
		$Email=$this->sanitizeMySQL($Email);
		
		$result=parent::ExecuteQuery("SELECT * FROM tblusers where Email='".$Email."'");
		if(!$result)
			return false;
		if($this->GetNumOfRows()>0)
			return false;
		return true;
	}
	
	public function FindUserByEmail($Email)
	{
		$Email=$this->sanitizeMySQL($Email);
		
		$result=parent::ExecuteQuery("SELECT * FROM tblusers where Email='".$Email."'");
		if(!$result)
			return 0;
		if($this->GetNumOfRows()==0)
			return 0;
		$row = $this->FetchDataRow();
		return $row[0];
	}
	
	public function ChangePassword($UserId,$Password)
	{
		$Password=$this->sanitizeMySQL($Password);
		$UserId=$this->sanitizeMySQL($UserId);
		
		$result= parent::ExecuteQuery("Update tblusers set Password='".$this->EncryptPassword($Password)."' where UserId=".$UserId);
		if(!$result)
			return false;
		return true;
	}
	
	public function CheckLogin($Email,$Password)
	{
		$Email=$this->sanitizeMySQL($Email);
		$Password=$this->sanitizeMySQL($Password);
		
		$result=parent::ExecuteQuery("SELECT * FROM tblusers where Email='".$Email."' and Password='".$this->EncryptPassword($Password)."'");
		if(!$result)
			return false;
		if($this->GetNumOfRows()==0)
			return NULL;
		return $result;
	}
	
	public function Insert($Email,$Password,$FirstName,$LastName)
	{
		$Password=$this->sanitizeMySQL($Password);
		$FirstName=$this->sanitizeMySQL($FirstName);
		$LastName=$this->sanitizeMySQL($LastName);
		$Email=$this->sanitizeMySQL($Email);
		
		$Query="insert into tblusers(Email,Password,FirstName,LastName) Values('".$Email."', '".$this->EncryptPassword($Password)."','".$FirstName."','".$LastName."')";
		$result=parent::ExecuteQuery($Query);
		if(!$result)
			return false;
		return true;
	}
	
	public function SelectAll()
	{
		$result=parent::ExecuteQuery("SELECT * FROM tblusers");
		if(!$result)
			return NULL;
		return $result;
	}

	public function SelectUser($UserId)
	{
		$UserId=$this->sanitizeMySQL($UserId);
		
		$result=parent::ExecuteQuery("SELECT * FROM tblusers where UserId=".$UserId);
		if(!$result)
			return NULL;
		if($this->GetNumOfRows()==0)
			return NULL;
		return $result;
	}
}
?>