<?php
session_start();
include('./includes/dBConnect.php');
if(isset($_GET['token'])){
    $token = $_GET['token'];  // token from url
    $verify_query =  "SELECT verify_token, verify_status FROM users WHERE verify_token='$token' LIMIT 1";
    $verify_run = mysqli_query($con, $verify_query);
    if(mysqli_num_rows($verify_run)>0){
        $row = mysqli_fetch_array( $verify_run);
        if($row['verify_status']=="0"){
            $clicked_token = $row['verify_token'];
            $update_query = "UPDATE users SET verify_status= '1' WHERE verify_token='$clicked_token' LIMIT 1";
            $update_query_run = mysqli_query($con,$update_query);
            if($update_query_run){
                $_SESSION['status']= "Account Verified Successfully!";  
	            header("location:login.php");
                exit(0);
            }else{
                $_SESSION['status']= "Verification Failed";  
	            header("location:login.php");
                exit(0);
            }
        }else{
            $_SESSION['status']= "Email Already Verified.Please Login";  
	        header("location:login.php");
            exit(0);
        }
    }else{
        $_SESSION['status']= "Token Non-existent!";  
	    header("location:login.php");
    }
}else{
    $_SESSION['status']= "Access Denied!";  
	header("location:login.php"); 
}
?>