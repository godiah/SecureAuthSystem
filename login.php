<?php 
session_start();
if(isset($_SESSION['authenticated'])){
    $_SESSION['status'] = "You are already logged in"; 
    header('Location: dashboard.php');
    exit(0);
}
$page_title = "Log In Page";
include('includes/header.php');
include('includes/navbar.php');
?>

<div class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">

            <?php
            if(isset($_SESSION['status'])){
                ?>
                <div class="alert alert-success">
                    <h5><?= $_SESSION['status']; ?></h5>
                </div>
                <?php
                unset($_SESSION['status']);
            }
            ?>
                <div class="card shadow">
                    <div class="card-header">
                        <h5>Log In Form</h5>
                    </div>
                    <div class="card-body">
                        <form action="code_login.php" method="POST">                            
                            <div class="form-group mb-3">
                                <label for="">Email Address</label>
                                <input type="email" name="user_email" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Password</label>
                                <input type="password" name="user_password" class="form-control">
                            </div>                            
                            <div class="form-group mb-3">
                                <button type="submit" class="btn btn-success text-center" name="login_btn">Log In</button>
                                <a href="password_reset.php" class="float-end">Forgot your password?</a>
                            </div>
                        </form>

                        <hr>
                        <h6>
                            Did not receive your Verification Email?
                            <a href="resend_email_verification.php">Resend</a>
                        </h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>