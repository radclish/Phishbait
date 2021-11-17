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
		
        $result = mysqli_query($con, $sql);  
        $row = mysqli_fetch_array($result);
		$current_password = $row['password'];
          
        if (password_verify($password, $current_password)) {  
            echo "<h1><center> Login successful </center></h1>";  
        }  
        else{  
            echo "<h1> Login failed. Invalid email or password.</h1>";  
        }     
?>
