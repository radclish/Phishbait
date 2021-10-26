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

	// database insert SQL code
	$sql = "INSERT INTO `login` (`email`, `password`) VALUES ('$email', '$hashed_password')";

	// insert in database 
	$rs = mysqli_query($con, $sql);

	if($rs)
	{
		echo "Contact Records Inserted";
	}

?>