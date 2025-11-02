<?php
/**
 * Contact Form Handler
 * Handles contact form submissions
 */

require_once 'config.php';

// Set JSON header
header('Content-Type: application/json');

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);

// If JSON parsing failed, try regular POST
if (!$data) {
    $data = $_POST;
}

// Validate required fields
$required = ['name', 'email', 'subject', 'message'];
$errors = [];

foreach ($required as $field) {
    if (empty($data[$field])) {
        $errors[] = ucfirst($field) . ' is required';
    }
}

// Validate email
if (!empty($data['email']) && !validateEmail($data['email'])) {
    $errors[] = 'Invalid email address';
}

// If there are errors, return them
if (!empty($errors)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
    exit;
}

// Sanitize inputs
$name = sanitizeInput($data['name']);
$email = sanitizeInput($data['email']);
$subject = sanitizeInput($data['subject']);
$messageText = sanitizeInput($data['message']);

// Prepare email content for admin
$adminContent = '
    <p>A new contact form submission has been received:</p>
    <div class="info-box">
        <div class="info-row"><span class="info-label">Name:</span> ' . $name . '</div>
        <div class="info-row"><span class="info-label">Email:</span> ' . $email . '</div>
        <div class="info-row"><span class="info-label">Subject:</span> ' . $subject . '</div>
    </div>
    <p><strong>Message:</strong></p>
    <div class="info-box">
        ' . nl2br($messageText) . '
    </div>
    <p>Please respond to this inquiry as soon as possible.</p>
';

// Prepare email content for client
$clientContent = '
    <p>Dear ' . $name . ',</p>
    <p>Thank you for contacting Eraaxis Global Consult. We have received your message and will respond within 24 hours.</p>
    <div class="info-box">
        <h3>Your Message:</h3>
        <p><strong>Subject:</strong> ' . $subject . '</p>
        <p>' . nl2br($messageText) . '</p>
    </div>
    <p>If your inquiry is urgent, please feel free to call us:</p>
    <ul>
        <li>Phone: +233 24 906 0913</li>
        <li>Alt Phone: +233 55 414 8133</li>
        <li>WhatsApp: +233 24 906 0913</li>
    </ul>
    <p>Best regards,<br><strong>Eraaxis Global Consult Team</strong></p>
';

try {
    // Send email to admin
    $adminSubject = 'Contact Form: ' . $subject;
    $adminEmailBody = getEmailTemplate('New Contact Form Submission', $adminContent);
    $adminEmailSent = sendEmail(ADMIN_EMAIL, $adminSubject, $adminEmailBody);

    // Send copy to secondary email
    sendEmail(SECONDARY_EMAIL, $adminSubject, $adminEmailBody);

    // Send confirmation email to client
    $clientSubject = 'We received your message - Eraaxis Global Consult';
    $clientEmailBody = getEmailTemplate('Message Received', $clientContent);
    $clientEmailSent = sendEmail($email, $clientSubject, $clientEmailBody);

    // Log submission
    logSubmission('CONTACT', [
        'name' => $name,
        'email' => $email,
        'subject' => $subject
    ]);

    // Return success response
    if ($adminEmailSent && $clientEmailSent) {
        echo json_encode([
            'success' => true,
            'message' => 'Thank you for your message! We\'ll get back to you within 24 hours.'
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'message' => 'Message received, but there was an issue sending confirmation emails. We will contact you soon.'
        ]);
    }

} catch (Exception $e) {
    error_log('Contact Form Error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred while sending your message. Please try again or email us directly at support@eraaxisglobalconsult.com'
    ]);
}
?>
