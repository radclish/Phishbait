<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values

$username = $password = $confirm_password = $firstname = $lastname = $email = $org = "";
$username_err = $password_err = $confirm_password_err = $firstname_err = $lastname_err = $email_err = $org_err = "";

//$username = $password = $confirm_password = "";
//$username_err = $password_err = $confirm_password_err = "";

 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "Username can only contain letters, numbers, and underscores.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong with the username. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }	
	
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
	
	
    // Validate email
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter an email address.";
    //} elseif(filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)){
    //    $email_err = "Please enter a valid email address.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE email = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            
            // Set parameters
            $param_email = trim($_POST["email"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $email_err = "This email is already taken.";
                } else{
                    $email = trim($_POST["email"]);
                }
            } else{
                echo "Oops! Something went wrong with the email. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

	
    // Validate firstname
    if(empty(trim($_POST["firstname"]))){
        $firstname_err = "Please enter your first name.";     
    } elseif(strlen(trim($_POST["firstname"])) < 2){
        $firstname_err = "First names must have at least 2 characters.";
    } else{
        $firstname = trim($_POST["firstname"]);
    }
	

    // Validate lastname
    if(empty(trim($_POST["lastname"]))){
        $lastname_err = "Please enter your last name.";     
    } elseif(strlen(trim($_POST["lastname"])) < 2){
        $lastname_err = "Last names must have at least 2 characters.";
    } else{
        $lastname = trim($_POST["lastname"]);
    }
	
	
    // Validate org
    if(empty(trim($_POST["org"]))){
        $org_err = "Please enter an organization name.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE org = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_org);
            
            // Set parameters
            $param_org = trim($_POST["org"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
               
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $org_err = "This organization is already registered.";
                } else{
                    $org = trim($_POST["org"]);
                }
            } else{
                echo "Oops! Something went wrong with the org. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
	
	
	
	
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($email_err) && empty($firstname_err) && empty($lastname_err) && empty($org_err)){
	//if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password, email, firstname, lastname, org) VALUES (?, ?, ?, ?, ?, ?)";
		//$sql = "INSERT INTO users (username, password) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssss", $param_username, $param_password, $param_email, $param_firstname, $param_lastname, $param_org);
			//mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
            
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
			$param_email = $email;
			$param_firstname = $firstname;
			$param_lastname = $lastname;
			$param_org = $org;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Oops! Something went wrong when executing. Please try again later.";
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
	<meta property="og:title" content="PhishBait - Create an Account"/>
	<meta property="og:description" content="Modern solutions to phishing attacks. Coming soon." />
	<meta property="og:image" content="images/infohook.jpg" />
	<meta property="og:url" content="https://phishbait.org/signup.html" />
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
							<a class="btn btn-outline-success" href="login.php" role="button">Sign In</a>
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
			<h2 style="color: #FFFFFF">Sign Up</h2>
		</div>
        <div class="container centerTextDiv">
				<form name="f1" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
					
					<div class="form-row">
    					<div class="form-group col-md-6">
      						<label class="form-label" style="color: #FFFFFF">First Name</label>
							<input type="text" name="firstname" class="form-control <?php echo (!empty($firstname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $firstname; ?>">
							<span class="invalid-feedback"><?php echo $firstname_err; ?></span>
    					</div>
						<div class="form-group col-md-6">
      						<label class="form-label" style="color: #FFFFFF">Last Name</label>
							<input type="text" name="lastname" class="form-control <?php echo (!empty($lastname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $lastname; ?>">
							<span class="invalid-feedback"><?php echo $firstname_err; ?></span>
    					</div>
  					</div>
					
					
					<div class="form-row">
						<div class="form-group col-md-6">
							<label class="form-label" style="color: #FFFFFF">Username</label>
							<input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
							<span class="invalid-feedback"><?php echo $username_err; ?></span>
						</div>
						<div class="form-group col-md-6">
							<label class="form-label" style="color: #FFFFFF">Email Address</label>
							<input type="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
							<span class="invalid-feedback"><?php echo $email_err; ?></span>
						</div>
					</div>
					
					<div class="form-group">
						<label class="form-label" style="color: #FFFFFF">Organization</label>
						<input type="text" name="org" class="form-control <?php echo (!empty($org_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $org; ?>">
						<span class="invalid-feedback"><?php echo $org_err; ?></span>
					</div>
					
					<div class="form-row">
						<div class="form-group col-md-6">
							<label class="form-label" style="color: #FFFFFF">Password</label>
							<input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
							<span class="invalid-feedback"><?php echo $password_err; ?></span>
						</div>
						<div class="form-group col-md-6">
							<label class="form-label" style="color: #FFFFFF">Confirm Password</label>
							<input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
							<span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
						</div>
					</div>
					<div class="form-row centerTextDiv">
				    	<button type="submit" class="btn-solid-lg page-scroll" value="Submit">Sign Up</button>
					</div>
					<div class="form-row centerTextDiv">
				  		<div class="w-full text-center p-t-55">
							<span class="txt2">
								Already a member?
							</span>

							<a href="login.php" class="txt2 bo1">
								Sign in now
							</a>
				  		</div>
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