<?php
session_start();
// if($_SESSION['loggedin'] && $_SESSION['isadmin']){

    session_unset();
    session_destroy();
    $url = 'http://localhost/docter_app/view/login.php';


    echo $url;
// }
?>