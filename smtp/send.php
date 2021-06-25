<?php



// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

// Load Composer's autoloader
require 'vendor/autoload.php';

// Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
$bodyInputs = '';

foreach($_POST as $key => $v) {
    $bodyInputs .= "<p><strong>$key:</strong><span>$v</span></p>";
}

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = getenv('MAIL_HOST');                    // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = getenv('MAIL_LOGIN');                     // SMTP username
    $mail->Password   = getenv('MAIL_PASSWORD');                               // SMTP password
    $mail->SMTPSecure = getenv('MAIL_ENCRIPTION');         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = getenv('MAIL_PORT');                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

    //Recipients
    $mail->setFrom(getenv('MAIL_FROM'), getenv('MAIL_FROM_NAME'));
    $mail->addAddress(getenv('MAIL_TO'), getenv('MAIL_TO_NAME'));     // Add a recipient

    // Attachments
    // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = getenv('MAIL_SUBJECT');
    $mail->Body    = getenv('MAIL_BODY') . $bodyInputs;

    $mail->send();
    echo getenv('FORM_BACK_SUCCESS');
} catch (Exception $e) {
    echo getenv('FORM_BACK_ERROR') . " {$mail->ErrorInfo}";
}