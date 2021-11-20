<?php      
    include('connection.php');  
    $email = $_POST['email'];  
    $password = $_POST['pass'];  
      
        //to prevent from mysqli injection  
        $email = stripcslashes($email);  
        $password = stripcslashes($password);  
        $email = mysqli_real_escape_string($con, $email);  
        $password = mysqli_real_escape_string($con, $password);  
      
        $sql = "select *from login where email = '$email'";
		$userid = "select userid from login where email = '$email'";
		$FirstName = "select FirstName from names where userid = '$userid'";
		
        $result = mysqli_query($con, $sql);  
        $row = mysqli_fetch_array($result);
		$current_password = $row['password'];
          
        if (password_verify($password, $current_password)) {  
			$userid = "select userid from login where email = '$email'";
			$uidresult = mysqli_query($con, $userid);  
        	$uidrow = mysqli_fetch_array($uidresult);
			$uid = $uidrow['userid'];
			
			$FirstName = "select FirstName from names where userid = '$uid'";
			$fnresult = mysqli_query($con, $FirstName);  
        	$fnrow = mysqli_fetch_array($fnresult);
			$fn = $fnrow['FirstName'];
            echo "<h1><center>Welcome, </center></h1>", $fn;
			header("Location: https://www.phishbait.org/loginsuccessful.html");
        }  
        else{  
            echo "<h1> Login failed. Invalid email or password.</h1>";  
        }     
?>
