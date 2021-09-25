<?php 
// hapus cookie
session_start();
session_unset();

// redirect ke halaman login
header("location: login.php");

 ?>