<?php
require_once('assets/includes/db_connect.php');

// Load PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // If using Composer

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize and trim inputs
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Validate the inputs
    if (!empty($name) && filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($message)) {
        
        // Prepare and insert into database
        $stmt = $conn->prepare("INSERT INTO messages (name, email, message) VALUES (?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sss", $name, $email, $message);
            if ($stmt->execute()) {
                // Send email using PHPMailer
                $mail = new PHPMailer(true);

                try {
                    // SMTP server settings for Gmail
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com';  // Gmail SMTP server
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'tejaswinimali0326@gmail.com'; // Your Gmail address
                    $mail->Password   = 'lcmoprqvjszvlkxa'; // App password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Use TLS encryption
                    $mail->Port       = 587;  // Gmail SMTP port (587 for TLS)

                    // Set up sender and recipient
                    $mail->setFrom('tejaswinimali0326@gmail.com', 'Tejaswini Website'); // Sender's email
                    $mail->addReplyTo($email, $name); // User's email as reply-to
                    $mail->addAddress('tejaswinimali0326@gmail.com', 'Tejaswini'); // Recipient's email

                    // Email subject
                    $mail->Subject = 'New Contact Message from Tejaswini Website';

                    // Email body content in HTML format
                    $mail->isHTML(true);
                    $mail->Body = "
                        <h2>New Contact Form Submission</h2>
                        <p><strong>Name:</strong> " . htmlspecialchars($name) . "</p>
                        <p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>
                        <p><strong>Message:</strong><br>" . nl2br(htmlspecialchars($message)) . "</p>
                    ";

                    // Plain text version for fallback
                    $mail->AltBody = "
                        New Contact Form Submission\n
                        Name: $name\n
                        Email: $email\n
                        Message:\n$message
                    ";

                    // Send the email
                    $mail->send();
                    echo "<script>alert('Message sent successfully!'); window.location='index.html';</script>";
                } catch (Exception $e) {
                    echo "<script>alert('Message could not be sent. Mailer Error: {$mail->ErrorInfo}'); window.history.back();</script>";
                }
            } else {
                echo "<script>alert('Error executing query: {$stmt->error}'); window.history.back();</script>";
            }
            $stmt->close();
        } else {
            echo "<script>alert('Prepare failed: {$conn->error}'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Invalid input data. Please check your entries.'); window.history.back();</script>";
    }

    $conn->close();
} else {
    echo "<script>alert('Invalid request method.'); window.location='index.html';</script>";
}
?>
