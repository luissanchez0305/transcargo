<?php

class Checklogin_Controller 
{
    public function main(array $getVars)
    {
		//ob_start();
    	session_start();
		$host="localhost"; // Host name 
		$username="espheras_dbuser"; // Mysql username 
		$password="Goingup123"; // Mysql password 
		$db_name="espheras_transcargo"; // Database name 
		$tbl_name="users"; // Table name 

		// Connect to server and select databse.
		mysql_connect("$host", "$username", "$password")or die("cannot connect"); 
		mysql_select_db("$db_name")or die("cannot select DB");
		
		// Define $myusername and $mypassword 
		$myusername=$getVars['myusername'];
		$mypassword=$getVars['mypassword'];
		
		// To protect MySQL injection (more detail about MySQL injection )
		$myusername = stripslashes($myusername);
		$mypassword = stripslashes($mypassword);
		$myusername = mysql_real_escape_string($myusername);
		$mypassword = mysql_real_escape_string($mypassword);
		$sql="SELECT * FROM $tbl_name WHERE username='$myusername' and password='$mypassword'";
		$result=mysql_query($sql);
		// Mysql_num_row is counting table row
		$count=mysql_num_rows($result);
		
		// If result matched $myusername and $mypassword, table row must be 1 row
		if($count==1){
			// Register $myusername, $mypassword and redirect to file "login_success.php"
			$_SESSION['myusername'] = $myusername;
			echo 'success';
		}
		else {
			echo 'Wrong Username or Password.';
		}
		//ob_end_flush();
    }
}