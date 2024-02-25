<?php
session_start();
include('./includes/dBConnect.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function  resend_email($name,$email,$verify_token){
$mail = new PHPMailer(true);
    try {
    //Server settings
    $mail->isSMTP();                                            
    $mail->Host       = 'smtp.gmail.com';                     
    $mail->SMTPAuth   = true;                                  
    $mail->Username   = 'xxxxx';                     //SMTP username
    $mail->Password   = 'xxxxx';                               //SMTP password
    // $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->SMTPSecure = "ssl";                                  //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('mosesgodiah@gmail.com', 'Rent Master Solutions');
    $mail->addAddress($email);     //Add a recipient

    //Content
    $mail->isHTML(true);                                 
    $mail->Subject = 'Resent Email Verification from Rent Master Solutions';
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

if(isset($_POST['resend_btn'])){
    if(!empty(trim(($_POST['email'])))){
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $checkemail =  "SELECT * FROM users WHERE email='$email'";
        $result = mysqli_query($con, $checkemail);

        if(mysqli_num_rows( $result ) > 0){
            $row = mysqli_fetch_array( $result );
            if($row['verify_status'] == "0"){
                //send verification mail to user
                $name = $row['username'];
                $email = $row['email'];
                $verify_token = $row['verify_token'];

                resend_email($name, $email, $verify_token);

                $_SESSION['status'] =  "Verification Email Sent.";
                header("Location: login.php");
                exit(0);
            }else{
                $_SESSION['status'] =  "Email already verified.";
                header("Location: login.php");
                exit(0);
            }
        }else{
            $_SESSION['status'] =  "Email not registered";
            header("Location: registration.php");
            exit(0);

        }
    }

}else{
    $_SESSION['status'] =  "Please fill out all fields.";
    header("Location: resend_email_verification.php");
    exit(0);
}
?>