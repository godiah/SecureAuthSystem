<?php
session_start();
$page_title = "Reset Password";
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
                        <h5>Reset Password</h5>
                    </div>
                    <div class="card-body">
                        <form action="code_resetpassword.php" method="POST">                            
                            <div class="form-group mb-3">
                                <label for="">Email Address</label>
                                <input type="email" name="email" class="form-control" placeholder="Enter  your email address" required>
                            </div>                            
                            <div class="form-group mb-3">
                                <button type="submit" class="btn btn-primary text-center" name="reset_btn">Send Password Reset Link</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>