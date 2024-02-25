<?php
session_start();
include('./includes/dBConnect.php');  //connect to the database


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function sendemail_verify($uname,$uemail,$verify_token) {
    $mail = new PHPMailer(true);
    try {
    //Server settings
    $mail->isSMTP();                                            
    $mail->Host       = 'smtp.gmail.com';                     
    $mail->SMTPAuth   = true;                                  
    $mail->Username   = 'xxxx';                     //SMTP username
    $mail->Password   = 'xxxx';                               //SMTP password
    // $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->SMTPSecure = "ssl";                                  //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('xxxx', 'Rent Master Solutions');
    $mail->addAddress($uemail);     //Add a recipient

    //Content
    $mail->isHTML(true);                                 
    $mail->Subject = 'Email Verification from Rent Master Solutions';
    $email_template = "
    <h3>You have succesfully registered with Rent Master Solutions</h3>
    <h4>Verify your email address to login with the below link</h4>
    <br/><br/>
    <a href='http://localhost/Login&Registration_verification/verify_email.php?token=$verify_token'>Click Here</a>
    ";
    $mail->Body    = $email_template;

    $mail->send();
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
}

if(isset($_POST['register_btn'])){
    //Collect data from form
    $uname = $_POST['username']; 
    $uphone = $_POST['tel_phone']; 
    $uemail = $_POST['useremail'];
    $upassword = $_POST['user_password'];
    $confirm_password = $_POST['confirm_password'];
    $hashpassword = password_hash($upassword,PASSWORD_DEFAULT);
    $verify_token = md5(rand());
    
    //Email exists or not
    $check_email_query = "SELECT email FROM `users` WHERE email = '$uemail' LIMIT 1";
    $check_email_query_run = mysqli_query($con, $check_email_query);

    if(mysqli_num_rows($check_email_query_run) > 0){
       $_SESSION['status'] = "Email already Exists!";
       header("Location: registration.php"); 
       //Check Password match
    }else if($upassword!=$confirm_password){
        $_SESSION['status']="Password does not match!";
        header("Location:registration.php");    
    } else{
        //Register New User
        $insert_user = "INSERT INTO `users` (username,phone,email,user_password,verify_token) VALUES ('$uname','$uphone','$uemail','$hashpassword','$verify_token')";
        $insert_user_run =  mysqli_query($con,$insert_user);
        if ($insert_user_run){
            sendemail_verify("$uname","$uemail","$verify_token");
            $_SESSION['status'] = "Registration Successful!</br> Kindly verify your Email Address to continue";
            //header("Location: dashboard.php");

        }else{
            $_SESSION['status'] = "Registration Failed";
            header("Location: registration.php"); 
        }
    }
 
};
?>