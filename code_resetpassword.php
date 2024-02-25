<?php

session_start();
include('./includes/dBConnect.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function send_passwordReset($get_name, $get_email, $token) {
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
    $mail->addAddress($get_email);     //Add a recipient

    //Content
    $mail->isHTML(true);                                 
    $mail->Subject = 'Reset Password Notification';
    $email_template = "
    <h3>You are receiving this email because a password reset request was received from your account.</h3>
    <h4>Ignore if not initiated by you</h4>
    <br/><br/>
    <a href='http://localhost/Login&Registration_verification/passwordChange.php?token=$token&email=$get_email'>Click Here</a>
    ";
    $mail->Body    = $email_template;

    $mail->send();
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

}

if(isset($_POST['reset_btn'])){
     $email = mysqli_real_escape_string($con, $_POST['email']);
     $token = md5(rand());
     
    //check if user exists with this email id
        $check_email =  "SELECT email FROM users WHERE email='$email' LIMIT 1";
        $result_email = mysqli_query($con, $check_email);

        if(mysqli_num_rows($result_email)>0){
            $row = mysqli_fetch_array( $result_email );
            $get_name = $row['username'];
            $get_email = $row['email'];

            $update_token =  "UPDATE users SET verify_token='$token' WHERE email='$get_email' LIMIT 1 ";
            $update_token_run =  mysqli_query($con, $update_token);

            if( $update_token_run ){
                send_passwordReset($get_name, $get_email, $token);
                $_SESSION['status'] =  "Reset Link Emailed. Please check your inbox.";
                header("Location: password_reset.php");
                exit(0);
            }else{
                $_SESSION['status'] =  "Something went wrong. #1";
                header("Location: password_reset.php");
                exit(0);
            }

        }else{
            $_SESSION['status'] =  "Email not registered.";
            header("Location: password_reset.php");
            exit(0);
        }

}

if(isset($_POST['newpwd_btn'])){
    $email  = mysqli_real_escape_string($con, $_POST['email']);
    $newpwd  = mysqli_real_escape_string($con, $_POST['newpwd']);
    $cfmpwd = mysqli_real_escape_string($con, $_POST['cfmpwd']);
    $token = mysqli_real_escape_string($con, $_POST['password_token']);

    //$hashpassword = password_hash($newpwd,PASSWORD_DEFAULT);

    if(!empty($token)){
        if(!empty($email) && !empty($newpwd) && !empty($cfmpwd)){
            //checking validity of token
            $check_token = "SELECT verify_token FROM users WHERE verify_token='$token' LIMIT 1";
            $run_check_token = mysqli_query($con,$check_token);

            if(mysqli_num_rows($run_check_token)>0){
                if($newpwd  == $cfmpwd){
                    $hashpassword = password_hash($newpwd,PASSWORD_DEFAULT);
                    $update_pwd = "UPDATE users SET user_password= '$hashpassword' WHERE verify_token='$token'";
                    $result_pwd = mysqli_query($con ,$update_pwd );

                    if($result_pwd){
                        $newtoken = md5(rand());
                        $update_token = "UPDATE users SET verify_token = '$newtoken' WHERE verify_token='$token'";
                        $result_token = mysqli_query($con ,$update_token );
                        $_SESSION['status'] =  "Password updated succesfully!";
                        header("Location: login.php");
                        exit(0);

                    }else{
                        $_SESSION['status'] =  "Password update fail.Retry";
                        header("Location: passwordChange.php?token=$token&email=$email");
                        exit(0);
                    }
                }else{
                    $_SESSION['status'] =  "Password do not match!.";
                    header("Location: passwordChange.php?token=$token&email=$email");
                    exit(0); 
                }

            }else{
                $_SESSION['status'] =  "Invalid Token.";
                header("Location: password_reset.php");
                exit(0); 
            }

        }
        else{
            $_SESSION['status'] =  "All Fields are Mandatory.";
            header("Location: passwordChange.php?token=$token&email=$email");
            exit(0); 
        }

    }else{
       $_SESSION['status'] =  "Token Unavailable.";
        header("Location: password_reset.php");
        exit(0); 
    }
}
?>