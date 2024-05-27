<?php

include_once 'functions.php';

if (!checkConnected()) {
    die('Not connected');
}

$sql = $db->prepare("SELECT token FROM users WHERE id = :id");
$sql->execute(['id' => $_SESSION["user_id"]]);
$result = $sql->fetch();
$_SESSION["token"] = $result["token"];

if (isset($_POST["token"]) || isset($_SESSION["token"])) {
    die("No CSRF token");
}

if ($_POST["token"] != $_SESSION["token"]) {
    die("Invalid CSRF token");
}
