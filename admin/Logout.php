<?php 
session_start();
unset($_SESSION['user']);
unset($_COOKIE['user']);
echo "<script>alert('You have successfully logged out.');location.href = '../main.php'</script>";
exit();
?>