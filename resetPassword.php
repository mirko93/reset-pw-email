<?php

include 'config.php';

if (!isset($_GET["code"])) {
    exit("Can't fine page");
}

$code = $_GET["code"];

$sql = "SELECT email FROM resetpassword WHERE code = :code";
$query = $con->prepare($sql);
$query->bindParam(":code", $code);
$query->execute();

if ($reset = $query->fetchAll(PDO::FETCH_COLUMN) === 0) {
    exit("Can't find page");
}

if (isset($_POST["password"])) {
    $pw = $_POST["password"];
    $pw = md5($pw);

    // Notice: Trying to access array offset on value of type bool
    $email = $reset["email"];
    var_dump($email);
    // 

    $sql = "UPDATE users SET password = '$pw' WHERE email = '$email'";
    $query = $con->prepare($sql);
    $query->execute();

    if ($query) {
        $sql = "DELETE FROM resetpassword WHERE code = '$code'";
        $query = $con->prepare($sql);
        $query->execute();

        exit("Password update");
    } else {
        exit("Something went wrong");
    }
}

?>

<form method="POST">
    <input type="password" name="password" placeholder="New password">
    <br>
    <input type="submit" name="submit" value="Update password">
</form>