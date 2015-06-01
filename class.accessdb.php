<?php
/**
 * Class to access and manipulate data on our customers database
 *
 */
 
class Customers 
{
	private $servername;
	private $dbname;
	private $username;
	private $password;
	private $conn;
	
	public function init ($server, $db, $user, $pwd)
	{
		$this->servername = $server;
		$this->dbname = $db;
		$this->username = $user;
		$this->password = $pwd;
	}
	
	public function connect() 
	{
		// Create connection
		$this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
		// Check connection
		if ($this->conn->connect_error) 
		{
			 die("Connection failed: " . $this->conn->connect_error);
		}
		
		// Make sure we are on the current database
		$sql = "use ".$this->dbname;
		$result = $this->conn->query($sql);
		//
		// Error checking
		//
	}
		
	// this function returns the full user name "FirstName LastName" 
	// this function takes in as param either the username or the user_id, specified by first param (userid or username)
	public function fullusername($type, $userinfo)
	{
		if (strcmp($type, "userid") == 0)
		{
			$sql = 'select concat(users.firstname, " ", users.lastname) as name 
				from users 
				where users.user_id="'.$userinfo.'"';
		}
		else if (strcmp($type, "username") == 0)
		{
			$sql = 'select concat(users.firstname, " ", users.lastname) as name 
				from users 
				where users.username="'.$userinfo.'"';			
		}
		
		$result = $this->conn->query($sql);

		if ($result->num_rows > 0) 
		{
			$row = $result->fetch_assoc();
			return $row["name"];
		}
		else return "";
	}	
		
	public function retrieveportfolio($username)
	{
		$stocks = array();
		$sql = 'select stocks.stock_name, stocks.stock_ticker as sym, concat(portfolios.stock_weight, "%") as weight 
				from stocks, portfolios, users 
				where stocks.stock_id=portfolios.stock_id and portfolios.user_id=users.user_id and users.username="'.$username.'"';
		$result = $this->conn->query($sql);

		if ($result->num_rows > 0) 
		{
			// output data of each row
			while($row = $result->fetch_assoc()) 
			{
				$stocks[]= array($row["sym"], $row["stock_name"], $row["weight"]);
			}
		}
		return $stocks;
	}	
	
	// Given user_id retrieve username
	public function retrieveusername($userid)
	{
		$sql = 'select users.username as name 
			from users 
			where users.user_id="'.$userid.'"';
	
		$result = $this->conn->query($sql);

		if ($result->num_rows > 0) 
		{
			$row = $result->fetch_assoc();
			return $row["name"];
		}
		else return "";	
	}
	
	// This function retrieves all friends of a given username
	public function retrievefriends($username)
	{
		$friends = array();
		$user = $this->fullusername("username", $username);
		
		$sql = 'select friends.friend_user_id as id from friends, users where users.user_id=friends.user_id and users.username="'.$username.'"';

		$result = $this->conn->query($sql);
		if ($result->num_rows > 0) 
		{
			// output data of each row
			while($row = $result->fetch_assoc()) 
			{
				$friends[] = array($this->fullusername("userid", $row["id"]), $this->retrieveusername($row["id"]));
			}
		}
		return $friends;
	}
		
	public function disconnect()
	{
		$this->conn->close();
	}
	
	// ----
	// Diagnostic functions only
	// ----
	//
	// This function only prints from table: users. $table="users" or the output cannot be processed.
	public function showalldata($table)
	{	
		$sql = "SELECT * FROM ".$table;
		$result = $this->conn->query($sql);
		//
		// Error checking
		//
		
		if ($result->num_rows > 0) 
		{
			 // output data of each row
			while($row = $result->fetch_assoc()) 
			{
				echo "<p>------------------------------------------------------";
				echo "<br>UserID: ".$row["user_id"]."<br>username: ".$row["username"]."<br>Name: ".$row["firstname"]." ".$row["lastname"]."<br>Email: ".$row["emailaddr"];
				echo "<br>------------------------------------------------------<p>";
			}
		} 
		else 
		{
			 echo "0 results";
		}
	}

	public function printvars()
	{
		echo "<p>Server-- ".$this->servername."<br>Database-- ".$this->dbname."<br>user-- ".$this->username."<br>password-- ".$this->password."<br>";
	}
	
	
}

?>