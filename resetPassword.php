<?php

include 'config.php';

if (!isset($_GET["code"])) {
    exit("Can't fine page");
}

$code = $_GET["code"];

$sql = "SELECT email FROM resetPassword WHERE code = :code";
$query = $con->prepare($sql);
$query->bindParam(":code", $code);
$query->execute();


if ($reset = $query->fetchColumn() === 0) {
    exit("Can't find page");
}

if (!isset($_POST["password"])) {
    $pw = $_POST["password"];
    $pw = md5($pw);

    $row = $query->fetch(PDO::FETCH_ASSOC);
    $email = $row["email"];

    $sql2 = "UPDATE users SET password = $pw WHERE email = $email";
    
}

?>

<form action="" method="POST">
    <input type="password" name="password" placeholder="New password">
    <br>
    <input type="submit" name="submit" value="Update password">
</form>