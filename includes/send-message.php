<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = htmlspecialchars($_POST['name']);
    $phone = htmlspecialchars($_POST['phone']);
    $email = htmlspecialchars($_POST['email']);
    $subject = htmlspecialchars($_POST['subject']);
    $message = htmlspecialchars($_POST['message']);

    // Construct the WhatsApp message
    $whatsappNumber = '250784701793'; // WhatsApp number without the '+' sign
    $whatsappMessage = "Name: $name%0APhone: $phone%0AEmail: $email%0ASubject: $subject%0AMessage: $message";
    $whatsappMessage = urlencode($whatsappMessage);

    // Create the WhatsApp URL
    $whatsappURL = "https://wa.me/$whatsappNumber?text=$whatsappMessage";

    // Redirect to the WhatsApp URL
    header("Location: $whatsappURL");
    exit();
}
?>
