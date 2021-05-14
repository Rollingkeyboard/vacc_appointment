<?php 
session_start();
unset($_SESSION['user']);
//unset($_COOKIE['user']);
session_destroy();

if (isset($_COOKIE['user'])) {
    unset($_COOKIE['user']);
//    setcookie('user', null, -1, '/');
    setcookie('user', "", time()-3601);
    echo "<script>alert('You have successfully logged out.');location.href = '../main.php'</script>";
    exit();
}
exit();
