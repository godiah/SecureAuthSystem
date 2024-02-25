<?php
session_start();
include('./includes/dBConnect.php');
if(isset($_POST['login_btn'])){
    if(!empty(trim($_POST['user_email'])) && !empty(trim($_POST['user_password']))){
        $email  = mysqli_real_escape_string($con,$_POST['user_email']);
        $password  = mysqli_real_escape_string($con,$_POST['user_password']);
        
        //Log in query
        $login_query =  "SELECT * FROM `users` WHERE email='$email' LIMIT 1";
        $login_query_run = mysqli_query($con,$login_query);
        $row_count = mysqli_num_rows($login_query_run);
        $row_data = mysqli_fetch_assoc( $login_query_run );

        if($row_count>0){
           if(password_verify($password,$row_data['user_password'])){
            if($row_data['verify_status'] == 1 ){
                $_SESSION['authenticated'] = TRUE;
                $_SESSION['auth_user'] = [
                    'username' => $row_data['username'],
                    'phone' => $row_data['phone'],
                    'email' => $row_data['email'],
                ];
                $_SESSION['status'] = "Logged in succesfully!"; 
                header("Location: dashboard.php");  
                exit(0);
            }else{
                $_SESSION['status'] = "Kindly Verify Your Email Address"; 
                header("Location: login.php");  
                exit(0); 
            }

        }else{
            $_SESSION['status'] = "Invalid Email or Password"; 
            header("Location: login.php");  
            exit(0);
        }
    }else{
       $_SESSION['status'] = "All Fields are Mandatory"; 
        header("Location: login.php");  
        exit(0);
    }
} }  
?>
