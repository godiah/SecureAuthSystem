<?php
    $hostname = 'localhost';
    $user = 'root';
    $pword = '';
    $db= 'mailAuthentication';

 $con = mysqli_connect($hostname,$user,$pword,$db );
    $mysqli= $con;
     if(!$con){
            echo  "unsuccessful" ; 
     }
?>