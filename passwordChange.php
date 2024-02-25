<?php
session_start();
$page_title = "Change Password";
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
                        <h5>Change Password</h5>
                    </div>
                    <div class="card-body">
                        <form action="code_resetpassword.php" method="POST"> 
                            <input type="hidden" name="password_token" value="<?php if(isset($_GET['token'])){echo $_GET['token'];} ?>">                           
                            <div class="form-group mb-3">
                                <label for="">Email Address</label>
                                <input type="email" name="email" value="<?php if(isset($_GET['email'])){echo $_GET['email'];} ?>" class="form-control" placeholder="Enter email address">
                            </div>   
                            <div class="form-group mb-3">
                                <label for="">New Password</label>
                                <input type="password" name="newpwd" class="form-control" placeholder="Enter new password">
                            </div>  
                            <div class="form-group mb-3">
                                <label for="">Confirm New Password</label>
                                <input type="password" name="cfmpwd" class="form-control" placeholder="Confirm new password">
                            </div>                       
                            <div class="form-group mb-3">
                                <button type="submit" class="btn btn-primary text-center w-100" name="newpwd_btn">Update Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>