<?php
session_start();
$_SESSION["loggedin"] = false;
$_SESSION["id"] = null;
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;