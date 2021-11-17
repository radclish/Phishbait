<?php
// database connection code
// $con = mysqli_connect('localhost', 'database_user', 'database_password','database');

  	$host = "localhost";  
    $email = "phishnml_admin";  
    $password = 'i-!pkK;7x@SF';  
    $db_name = "phishnml_testlogin";  
      
    $con = mysqli_connect($host, $email, $password, $db_name);  
    if(mysqli_connect_errno()) {  
        die("Failed to connect with MySQL: ". mysqli_connect_error());  
    }  

	// get the post records
	$email = $_POST['email'];
	$userpassword = $_POST['Userpassword'];
	$hashed_password = password_hash($userpassword, PASSWORD_DEFAULT);
	$FirstName = $_POST['FirstName'];
	$LastName = $_POST['LastName'];
	$userid = $_POST['userid'];



	// database insert SQL code
	$sql = "INSERT INTO `login` (`email`, `password`, `userid`) VALUES ('$email', '$hashed_password', '$userid')";

	// insert in database 
	$rs = mysqli_query($con, $sql);

	$sql = "INSERT INTO `names` (`FirstName`, `LastName`, `userid`) VALUES ('$FirstName', '$LastName', '$userid')";
	$rs = mysqli_query($con, $sql);

	if($rs)
	{
		echo "Welcome ", $FirstName;
	}

?>