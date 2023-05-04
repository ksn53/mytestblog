<?php
$currentLogin = '';
if (isset($_SESSION["loginStatus"])) {
    $currentLogin = $_SESSION["currentLogin"];
    setcookie("currentLogin", $currentLogin, time() + 60 * 60 * 24 * 30, "/");
}