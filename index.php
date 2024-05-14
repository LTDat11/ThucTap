<?php
session_start();

if(isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    echo "Welcome, $username! You are logged in.";
} else {
    echo "You are not logged in.";
}
?>