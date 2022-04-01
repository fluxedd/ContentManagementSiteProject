<?php 
    $non_admin_warning = "<script>alert('You must be an admin to access this page!'); 
    window.location.href='index.php';</script>";

    if(!isset($_SESSION['loggedin']))
    {
        echo $non_admin_warning;
    } 

    if (isset($_SESSION['loggedin']) && $_SESSION['user_type'] != 1)
    {
        echo $non_admin_warning;
    }
?>