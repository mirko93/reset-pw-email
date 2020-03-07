<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'config.php';

if (isset($_POST['email'])) {

    $emailTo = $_POST['email'];

    $code = uniqid(true);

    $sql = "INSERT INTO resetPassword(code, email) VALUES (:code, :email)";
    $query = $con->prepare($sql);
    $query->bindParam(":code", $code);
    $query->bindParam(":email", $emailTo);
    $query->execute();

    if (!$query) {
        exit("Error");
    }

    // Instantiation and passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'email@gmail.com';                     // SMTP username
        $mail->Password   = 'password';                               // SMTP password
        $mail->SMTPSecure = 'ssl';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 465;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

        //Recipients
        $mail->setFrom('email@gmail.com', 'Test');
        $mail->addAddress($emailTo);     // Add a recipient
        $mail->addReplyTo('no-reply@gmail.com', 'No reply');

        // Content
        $url = "http://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]) . "/resetPassword.php?code=$code";
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Your password reset link';
        $mail->Body    = "<h1>You requested a password reset</h1>
                            Click <a href='$url'>this link</a> to do so";
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();
        echo 'Reset password link has been sent to your email';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
    exit();
}


?>

<form method="POST">
    <input type="text" name="email" placeholder="Email" autocomplete="off">
    <br>
    <input type="submit" name="submit" value="Reset email">
</form>