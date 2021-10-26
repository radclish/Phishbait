<?php      
    $host = "localhost";  
    $email = "phishnml_admin";  
    $password = 'i-!pkK;7x@SF';  
    $db_name = "phishnml_testlogin";  
      
    $con = mysqli_connect($host, $email, $password, $db_name);  
    if(mysqli_connect_errno()) {  
        die("Failed to connect with MySQL: ". mysqli_connect_error());  
    }  
?>   