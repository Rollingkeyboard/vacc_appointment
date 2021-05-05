<?php 
session_start();
unset($_SESSION['user']);
unset($_COOKIE['user']);
echo "<script>alert('You Have Successfully Logged Out Of Your Account.');location.href = '/main.php'</script>";
exit();