# Eraaxis Global Consult - Email Setup Guide

This guide will help you configure the email system for your website forms.

## Current Status

✅ Forms are fully integrated with PHP backend
✅ All forms submit via AJAX (no page reload)
✅ Contact information updated with Ghana numbers
✅ Email handlers created and ready to use

## Email Configuration Options

### Option 1: Using PHP mail() Function (Simplest)

This works out-of-the-box on most servers but emails may go to spam.

**No additional setup required!** The forms will work immediately.

### Option 2: Using Gmail SMTP (Recommended)

For better email delivery, use Gmail SMTP:

#### Step 1: Enable SMTP in config.php

Open `config.php` and change:
```php
define('USE_SMTP', true); // Change from false to true
```

#### Step 2: Create Gmail App Password

1. Go to your Google Account: https://myaccount.google.com/
2. Click **Security** in the left menu
3. Under "How you sign in to Google", enable **2-Step Verification** (if not already enabled)
4. After enabling 2FA, go back to Security
5. Click **2-Step Verification**
6. Scroll down to **App passwords**
7. Click **App passwords**
8. Select **Mail** and **Other (Custom name)**
9. Enter "Eraaxis Website" as the name
10. Click **Generate**
11. Copy the 16-character password (it will look like: `xxxx xxxx xxxx xxxx`)

#### Step 3: Update config.php with App Password

```php
define('SMTP_USERNAME', 'support@eraaxisglobalconsult.com');
define('SMTP_PASSWORD', 'your-16-char-app-password-here'); // Paste app password
```

#### Step 4: Test the Configuration

Submit a test form and check if emails are received.

## Testing Your Forms

### 1. Test Booking Form
- Go to: http://localhost/era/eraconsult/book.html
- Fill out and submit the form
- Check inbox at: support@eraaxisglobalconsult.com

### 2. Test Contact Form
- Go to: http://localhost/era/eraconsult/contact.html
- Fill out and submit the form
- Check both email inboxes

### 3. Test Newsletter
- Find the newsletter form in the footer of any page
- Subscribe with a test email
- Check if you receive welcome email
- Check admin email for notification

## Troubleshooting

### Emails Not Sending

1. **Check PHP error log:**
   - XAMPP: `C:\xampp\apache\logs\error.log`
   - Look for email-related errors

2. **Check submission logs:**
   - Location: `/logs/submissions.log`
   - This shows all form submissions

3. **Test PHP mail function:**
   Create a file `test-email.php`:
   ```php
   <?php
   $to = 'support@eraaxisglobalconsult.com';
   $subject = 'Test Email';
   $message = 'This is a test email from XAMPP';
   $headers = 'From: noreply@eraaxisglobal.com';

   if (mail($to, $subject, $message, $headers)) {
       echo 'Email sent successfully!';
   } else {
       echo 'Email failed to send.';
   }
   ?>
   ```
   Visit: http://localhost/era/eraconsult/test-email.php

### Emails Going to Spam

**Solutions:**
1. Use Gmail SMTP (Option 2 above)
2. Or use a professional email service like:
   - SendGrid (free tier: 100 emails/day)
   - Mailgun (free tier: 5000 emails/month)
   - Amazon SES

### Newsletter Subscribers

All newsletter subscribers are stored in:
- File: `/data/subscribers.json`
- Format: JSON with email, date, and IP address

To view subscribers:
```php
<?php
$subscribers = json_decode(file_get_contents('data/subscribers.json'), true);
echo '<pre>';
print_r($subscribers);
echo '</pre>';
?>
```

## Form Endpoints

Your forms now submit to these PHP files:

- **Booking Form:** `book-handler.php`
- **Contact Form:** `contact-handler.php`
- **Newsletter:** `newsletter-handler.php`

## Email Notifications

### Who Gets Emails?

**Booking Form:**
- Admin receives: Booking details notification
- Client receives: Booking confirmation
- Both sent to: support@eraaxisglobalconsult.com

**Contact Form:**
- Admin receives: Contact inquiry
- Client receives: "We received your message" confirmation
- Both sent to: support@eraaxisglobalconsult.com

**Newsletter:**
- Admin receives: New subscriber notification
- Subscriber receives: Welcome email
- Admin notification to: support@eraaxisglobalconsult.com

## Customizing Email Templates

Edit the email templates in `config.php`:

```php
function getEmailTemplate($title, $content) {
    // Customize HTML template here
}
```

Current template includes:
- Eraaxis branding
- Professional styling
- Responsive design
- Company colors (Blue and Gold)

## Security Features

✅ Input sanitization
✅ Email validation
✅ CSRF protection (can be added)
✅ Rate limiting (can be added)
✅ Submission logging

## Next Steps

### For Production Deployment:

1. **Update config.php:**
   ```php
   define('SITE_URL', 'https://www.eraaxisglobal.com');
   define('FROM_EMAIL', 'noreply@eraaxisglobal.com');
   ```

2. **Add reCAPTCHA** (prevent spam):
   - Get keys: https://www.google.com/recaptcha/admin
   - Update config.php:
     ```php
     define('ENABLE_RECAPTCHA', true);
     define('RECAPTCHA_SITE_KEY', 'your-site-key');
     define('RECAPTCHA_SECRET_KEY', 'your-secret-key');
     ```

3. **Set proper file permissions:**
   - `/logs/` - 755
   - `/data/` - 755
   - `.php files` - 644

4. **Use HTTPS** (SSL certificate)

5. **Professional email service** (SendGrid, Mailgun, etc.)

## Support

If you encounter any issues:

1. Check `/logs/submissions.log` - Shows all form submissions
2. Check Apache error log - Shows PHP errors
3. Test with simple PHP mail script
4. Verify email addresses in config.php are correct

## Contact Information in System

The following contact details are used throughout:

- **Primary Email:** support@eraaxisglobalconsult.com
- **Primary Phone:** +233 24 906 0913
- **Secondary Phone:** +233 55 414 8133
- **WhatsApp:** +233 24 906 0913

---

**Your forms are ready to use!** Just test them and configure SMTP if needed for better delivery.
