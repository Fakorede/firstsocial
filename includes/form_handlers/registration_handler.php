<?php

// Declare registration variables
$fname = "";
$lname = "";
$username = "";
$email = "";
$email2 = "";
$password = "";
$password2 = "";
$date = "";//sign up date
$error_array = array();//holds error messages

if(isset($_POST['register_button'])){

    // Registration form values
   
   //First Name
    $fname = strip_tags($_POST['reg_fname']); //remove html tags
    $fname = str_replace(' ', '', $fname);//remove spaces
    $fname = ucfirst(strtolower($fname));// Capitalize first letter only
    $_SESSION['reg_fname'] = $fname; // store into session

    //Last Name
    $lname = strip_tags($_POST['reg_lname']); //remove html tags
    $lname = str_replace(' ', '', $lname);//remove spaces
    $lname = ucfirst(strtolower($lname));// Capitalize first letter only
    $_SESSION['reg_lname'] = $lname; // store into session

    //Username
    $username = strip_tags($_POST['username']); //remove html tags
    $username = str_replace(' ', '', $username);//remove spaces
    $username = ucfirst(strtolower($username));// Capitalize first letter only
    $_SESSION['reg_lname'] = $username; // store into session

    //Email
    $email = strip_tags($_POST['reg_email']); //remove html tags
    $email = str_replace(' ', '', $email);//remove spaces
    $email = ucfirst(strtolower($email));// Capitalize first letter only
    $_SESSION['reg_email'] = $email; // store into session

    //Email confirmation
    $email2 = strip_tags($_POST['reg_email2']); //remove html tags
    $email2 = str_replace(' ', '', $email2);//remove spaces
    $email2 = ucfirst(strtolower($email2));// Capitalize first letter only
    $_SESSION['reg_email2'] = $email2; // store into session

    //Password
    $password = strip_tags($_POST['reg_password']); //remove html tags

    //Password confirmation
    $password2 = strip_tags($_POST['reg_password2']); //remove html tags

    $date = date("Y-m-d");//Current date

    // Check if the emails match
    if($email == $email2){

        // Check if email format is valid
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){

            $email = filter_var($email, FILTER_VALIDATE_EMAIL);

            // Check if email already exists
            $email_check = mysqli_query($con, "SELECT email FROM users WHERE email='$email'");

            //Count the number of rows returned
            $num_rows = mysqli_num_rows($email_check);

            if($num_rows > 0){
                array_push($error_array, "Email already exists<br />");
            }

        }else{
            array_push($error_array, "Invalid Email format<br />");
        }

    }else{
       array_push($error_array, "Emails don't match<br />");
    }

    if(strlen($fname) >25 || strlen($fname) < 3){
         array_push($error_array, "Your first name must be between 3 and 25 characters<br />");
    }

    if(strlen($lname) >25 || strlen($lname) < 3){
        array_push($error_array, "Your last name must be between 3 and 25 characters<br />");
    }

    if($password != $password2){
        array_push($error_array, "Your passwords do not match<br />");
    }else{
        if(preg_match('/[^A-Za-z0-9]/', $password)){
            array_push($error_array, "Your password can't contain special characters<br />");
        }
    }

    if(strlen($password) < 5 || strlen($password) > 30){
        array_push($error_array, "Your password must be between 5 and 30 characters<br />");
    }

    if(empty($error_array)){
        $password = md5($password);// Encrypt password

        // Generate Username
        // $username = strtolower($fname."_".$lname);
        $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");

        $i = 0;
        // if username exists, add number to username
        while(mysqli_num_rows($check_username_query) != 0){
            $i++;
            $username = $username."_".$i;
            $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");
        }

        //Profile picture
        $rand = rand(1, 2); //Random number btw 1 and 2

        if($rand == 1){
            $profile_pic = "assets/images/profile_pics/defaults/head_deep_blue.png";
        }else if($rand == 2){
            $profile_pic = "assets/images/profile_pics/defaults/head_emerald.png";
        }
    
        $query = mysqli_query($con, "INSERT INTO users VALUES ('', '$fname', '$lname', '$username', '$email', '$password', '$date', '$profile_pic', '0', '0', 'no', ',')");
    
        array_push($error_array, "<span style='color: #14C800;'>Registration Successful!</span><br>");

        //Clear session variables 
		$_SESSION['reg_fname'] = "";
		$_SESSION['reg_lname'] = "";
		$_SESSION['reg_email'] = "";
		$_SESSION['reg_email2'] = "";
    
    }

}

?>