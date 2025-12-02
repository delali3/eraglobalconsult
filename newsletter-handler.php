<?php
/**
 * Newsletter Subscription Handler
 * Handles newsletter subscription form submissions
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

// Validate email
if (empty($data['email'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Email address is required']);
    exit;
}

if (!validateEmail($data['email'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid email address']);
    exit;
}

// Sanitize email
$email = sanitizeInput($data['email']);

// Check if already subscribed (simple file-based storage)
$subscribersFile = __DIR__ . '/data/subscribers.json';
$subscribers = [];

// Create data directory if it doesn't exist
if (!file_exists(__DIR__ . '/data')) {
    mkdir(__DIR__ . '/data', 0755, true);
}

// Load existing subscribers
if (file_exists($subscribersFile)) {
    $subscribers = json_decode(file_get_contents($subscribersFile), true) ?: [];
}

// Check for duplicate
$emailExists = false;
foreach ($subscribers as $subscriber) {
    if ($subscriber['email'] === $email) {
        $emailExists = true;
        break;
    }
}

if ($emailExists) {
    echo json_encode([
        'success' => true,
        'message' => 'You are already subscribed to our newsletter!'
    ]);
    exit;
}

// Add new subscriber
$subscribers[] = [
    'email' => $email,
    'subscribed_at' => date('Y-m-d H:i:s'),
    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
];

// Save subscribers
file_put_contents($subscribersFile, json_encode($subscribers, JSON_PRETTY_PRINT));

// Prepare email content for admin
$adminContent = '
    <p>A new newsletter subscription has been received:</p>
    <div class="info-box">
        <div class="info-row"><span class="info-label">Email:</span> ' . $email . '</div>
        <div class="info-row"><span class="info-label">Date:</span> ' . date('F j, Y \a\t g:i A') . '</div>
        <div class="info-row"><span class="info-label">Total Subscribers:</span> ' . count($subscribers) . '</div>
    </div>
    <p>You can find all subscribers in: /data/subscribers.json</p>
';

// Prepare email content for subscriber
$subscriberContent = '
    <p>Welcome to the Docera Travel newsletter!</p>
    <p>Thank you for subscribing. You\'ll now receive our latest insights, updates, and exclusive content about:</p>
    <ul>
        <li>Business consulting trends and strategies</li>
        <li>Academic opportunities and guidance</li>
        <li>Career development tips</li>
        <li>Corporate training best practices</li>
        <li>Industry news and updates</li>
    </ul>
    <p>We respect your privacy and will never share your email address with third parties.</p>
    <p>If you wish to unsubscribe at any time, simply reply to this email with "Unsubscribe" in the subject line.</p>
    <p>Stay connected with us:</p>
    <ul>
        <li>Website: www.doceratravel.com</li>
        <li>Email: support@doceratravel.com</li>
        <li>Phone: +233 24 906 0913</li>
    </ul>
    <p>Best regards,<br><strong>Docera Travel Team</strong></p>
';

try {
    // Send notification to admin
    $adminSubject = 'New Newsletter Subscription';
    $adminEmailBody = getEmailTemplate('New Newsletter Subscriber', $adminContent);
    sendEmail(ADMIN_EMAIL, $adminSubject, $adminEmailBody);

    // Send welcome email to subscriber
    $subscriberSubject = 'Welcome to Docera Travel Newsletter';
    $subscriberEmailBody = getEmailTemplate('Newsletter Subscription Confirmed', $subscriberContent);
    $subscriberEmailSent = sendEmail($email, $subscriberSubject, $subscriberEmailBody);

    // Log submission
    logSubmission('NEWSLETTER', ['email' => $email]);

    // Return success response
    if ($subscriberEmailSent) {
        echo json_encode([
            'success' => true,
            'message' => 'Thank you for subscribing! Check your email for confirmation.'
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'message' => 'You\'ve been subscribed! However, we couldn\'t send a confirmation email. You\'ll still receive our updates.'
        ]);
    }

} catch (Exception $e) {
    error_log('Newsletter Subscription Error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred. Please try again later.'
    ]);
}
?>
