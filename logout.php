<!DOCTYPE html>
<html>
<head><title>Login Admin</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<?php
session_start();
session_destroy();
header("Location: login.php");
?>
