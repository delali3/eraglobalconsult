<?php
/**
 * Booking Form Handler
 * Handles consultation booking form submissions
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
$required = ['firstName', 'lastName', 'email', 'consultationType', 'preferredDate', 'preferredTime'];
$errors = [];

foreach ($required as $field) {
    if (empty($data[$field])) {
        $errors[] = ucfirst(str_replace('_', ' ', $field)) . ' is required';
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
$firstName = sanitizeInput($data['firstName']);
$lastName = sanitizeInput($data['lastName']);
$email = sanitizeInput($data['email']);
$phone = sanitizeInput($data['phone'] ?? '');
$consultationType = sanitizeInput($data['consultationType']);
$preferredDate = sanitizeInput($data['preferredDate']);
$preferredTime = sanitizeInput($data['preferredTime']);
$message = sanitizeInput($data['message'] ?? '');

// Format consultation type
$consultationTypes = [
    'business' => 'Business Consulting',
    'academic' => 'Academic Advisory',
    'career' => 'Career Coaching',
    'corporate' => 'Corporate Training',
    'other' => 'Other'
];
$consultationTypeLabel = $consultationTypes[$consultationType] ?? $consultationType;

// Format preferred time
$timeSlots = [
    'morning' => 'Morning (9:00 AM - 12:00 PM)',
    'afternoon' => 'Afternoon (12:00 PM - 3:00 PM)',
    'evening' => 'Evening (3:00 PM - 6:00 PM)'
];
$preferredTimeLabel = $timeSlots[$preferredTime] ?? $preferredTime;

// Prepare email content for admin
$adminContent = '
    <p>A new consultation booking has been received:</p>
    <div class="info-box">
        <div class="info-row"><span class="info-label">Name:</span> ' . $firstName . ' ' . $lastName . '</div>
        <div class="info-row"><span class="info-label">Email:</span> ' . $email . '</div>
        <div class="info-row"><span class="info-label">Phone:</span> ' . ($phone ?: 'Not provided') . '</div>
        <div class="info-row"><span class="info-label">Service:</span> ' . $consultationTypeLabel . '</div>
        <div class="info-row"><span class="info-label">Preferred Date:</span> ' . date('F j, Y', strtotime($preferredDate)) . '</div>
        <div class="info-row"><span class="info-label">Preferred Time:</span> ' . $preferredTimeLabel . '</div>
    </div>
    ' . ($message ? '<p><strong>Message:</strong><br>' . nl2br($message) . '</p>' : '') . '
    <p>Please contact the client within 24 hours to confirm the appointment.</p>
';

// Prepare email content for client
$clientContent = '
    <p>Dear ' . $firstName . ',</p>
    <p>Thank you for booking a consultation with Eraaxis Global Consult. We have received your request and will contact you within 24 hours to confirm your appointment.</p>
    <div class="info-box">
        <h3>Your Booking Details:</h3>
        <div class="info-row"><span class="info-label">Service:</span> ' . $consultationTypeLabel . '</div>
        <div class="info-row"><span class="info-label">Preferred Date:</span> ' . date('F j, Y', strtotime($preferredDate)) . '</div>
        <div class="info-row"><span class="info-label">Preferred Time:</span> ' . $preferredTimeLabel . '</div>
    </div>
    <p>If you have any questions in the meantime, please don\'t hesitate to reach out:</p>
    <ul>
        <li>Email: support@eraaxisglobalconsult.com</li>
        <li>Phone: +233 24 906 0913</li>
        <li>WhatsApp: +233 24 906 0913</li>
    </ul>
    <p>We look forward to speaking with you!</p>
    <p>Best regards,<br><strong>Eraaxis Global Consult Team</strong></p>
';

try {
    // Send email to admin
    $adminSubject = 'New Consultation Booking - ' . $consultationTypeLabel;
    $adminEmailBody = getEmailTemplate('New Consultation Booking', $adminContent);
    $adminEmailSent = sendEmail(ADMIN_EMAIL, $adminSubject, $adminEmailBody);

    // Send copy to secondary email
    sendEmail(SECONDARY_EMAIL, $adminSubject, $adminEmailBody);

    // Send confirmation email to client
    $clientSubject = 'Consultation Booking Confirmation - Eraaxis Global Consult';
    $clientEmailBody = getEmailTemplate('Booking Confirmation', $clientContent);
    $clientEmailSent = sendEmail($email, $clientSubject, $clientEmailBody);

    // Log submission
    logSubmission('BOOKING', [
        'name' => $firstName . ' ' . $lastName,
        'email' => $email,
        'phone' => $phone,
        'type' => $consultationType,
        'date' => $preferredDate,
        'time' => $preferredTime
    ]);

    // Return success response
    if ($adminEmailSent && $clientEmailSent) {
        echo json_encode([
            'success' => true,
            'message' => 'Thank you! Your booking has been received. We\'ll contact you within 24 hours to confirm your appointment.'
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'message' => 'Booking received, but there was an issue sending confirmation emails. We will contact you soon.'
        ]);
    }

} catch (Exception $e) {
    error_log('Booking Form Error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred while processing your booking. Please try again or contact us directly.'
    ]);
}
?>
