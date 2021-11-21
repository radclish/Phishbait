<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: welcome.php");
    exit;
}
 
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = $login_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = $username;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            // Redirect user to welcome page
                            header("location: welcome.php");
                        } else{
                            // Password is not valid, display a generic error message
                            $login_err = "Invalid username or password.";
                        }
                    }
                } else{
                    // Username doesn't exist, display a generic error message
                    $login_err = "Invalid username or password.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Modern solutions to phishing attacks. Coming soon.">
    <meta name="developer" content="PhishBait Team">

    <!-- OG Meta Tags to improve the way the post looks when you share the page on LinkedIn, Facebook, Google+ -->
	<meta property="og:site_name" content="PhishBait" />
	<meta property="og:site" content="phishbait.org" />
	<meta property="og:title" content="PhishBait - Sign In"/>
	<meta property="og:description" content="Modern solutions to phishing attacks. Coming soon." />
	<meta property="og:image" content="images/infohook.jpg" />
	<meta property="og:url" content="https://phishbait.org/login.html" />
	<meta name="twitter:card" content="summary_large_image">


    <!-- Webpage Title -->
    <title>PhishBait</title>
    
    <!-- Styles -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&display=swap" rel="stylesheet">
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/fontawesome-all.css" rel="stylesheet">
    <link href="css/swiper.css" rel="stylesheet">
	<link href="css/magnific-popup.css" rel="stylesheet">
	<link href="css/styles.css" rel="stylesheet">
	
	<!-- Favicon  -->
    <link rel="icon" href="images/favicon.png">
</head>
<body data-spy="scroll" data-target=".fixed-top">
    
    <!-- Nav -->
    <nav class="navbar navbar-expand-lg fixed-top navbar-dark">
        <div class="container">
            
            <!-- Text Logo -->
            <a class="navbar-brand logo-text page-scroll" href="index.html">PhishBait</a>

            <!-- Image Logo -->
            <!-- <a class="navbar-brand logo-image" href="index.html"><img src="images/logo.svg" alt="alternative"></a>  -->

            <button class="navbar-toggler p-0 border-0" type="button" data-toggle="offcanvas">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="navbar-collapse offcanvas-collapse" id="navbarsExampleDefault">
                <ul class="navbar-nav ml-auto">			
                    <li class="nav-item">
                        <a class="nav-link page-scroll" href="campaign-guide.html" style="display: none;">Campaign Guide</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link page-scroll" href="email-templates.html">Email Templates</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link page-scroll" href="training-videos.html">Training Videos</a>
                    </li>
					<li class="nav-item">
                        <form class="d-inline">
							<a class="btn btn-outline-success" href="login.html" role="button">Sign In</a>
		  				</form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <!-- Header -->
    <div class="header">
        <div class="ocean">
            <div class="wave"></div>
            <div class="wave"></div>
        </div>
		<div class="w-full text-center p-t-55">
			<h2 style="color: #FFFFFF">Login</h2>
			<p style="color: #FFFFFF">Please fill in your credentials to login.</p>
		</div>
        <div class="container">
			<?php 
				if(!empty($login_err)){
            	echo '<div class="alert alert-danger">' . $login_err . '</div>';
        		}        
        	?>
            <div class="row">
				<form name="f1" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
					<div class="mb-3">
						<label class="form-label" style="color: #FFFFFF">Username</label>
						<input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
						<span class="invalid-feedback"><?php echo $username_err; ?></span>
					</div>
					<div class="mb-3">
						<label class="form-label" style="color: #FFFFFF">Password</label>
						<input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
						<span class="invalid-feedback"><?php echo $password_err; ?></span>
					</div>
            		<div class="mb-3">
                		<input type="submit" class="btn-solid-lg page-scroll" value="Login">
            		</div>
				  	<div class="w-full text-center p-t-55">
						<span class="txt2">
							Not a member?
						</span>
						<a href="register.php" class="txt2 bo1">
							Sign up now
						</a>
				 	</div>
				
				</form>
            </div> <!-- end of row -->
        </div> <!-- end of container -->
    </div> <!-- end of header -->

    <!-- Footer -->
    <div class="footer bg-gray">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
					<p class="p-small">Copyright © <a class="no-line" href="#your-link">PhishBait</a></p>
					<p class="p-small">Assets gathered under Creative Commons License</p>
					<p class="p-small">Bootstrap gathered under Creative Commons License</p>
                </div> <!-- end of col -->
            </div> <!-- end of row -->
        </div> <!-- end of container -->
    </div> <!-- end of footer -->  
    <!-- end of footer -->
    
    	
    <!-- Scripts -->
    <script src="js/jquery.min.js"></script> <!-- jQuery for Bootstrap's JavaScript plugins -->
    <script src="js/bootstrap.min.js"></script> <!-- Bootstrap framework -->
    <script src="js/jquery.easing.min.js"></script> <!-- jQuery Easing for smooth scrolling between anchors -->
    <script src="js/swiper.min.js"></script> <!-- Swiper for image and text sliders -->
    <script src="js/jquery.magnific-popup.js"></script> <!-- Magnific Popup for lightboxes -->
    <script src="js/scripts.js"></script> <!-- Custom scripts -->
</body>
</html>