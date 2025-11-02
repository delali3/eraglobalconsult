# Eraaxis Global Consult Website

A modern, professional consulting website built with Tailwind CSS and vanilla JavaScript.

## Features

- **Responsive Design**: Fully responsive across all devices (mobile, tablet, desktop)
- **Modern UI/UX**: Clean, professional design with smooth animations
- **Multiple Pages**:
  - Home (index.html)
  - About Us (about.html)
  - Services (services.html)
  - Book Consultation (book.html)
  - Contact (contact.html)
  - Privacy Policy (privacy.html)
  - Terms of Service (terms.html)

## Services Offered

1. **Business Consulting** - Strategic planning, market expansion, operational excellence
2. **Academic Advisory** - University admissions, scholarships, study abroad planning
3. **Career Coaching** - Career strategy, resume optimization, interview preparation
4. **Corporate Training** - Leadership development, team building, skills enhancement

## Technology Stack

- **HTML5** - Semantic markup
- **Tailwind CSS** - Utility-first CSS framework (via CDN)
- **Vanilla JavaScript** - No dependencies, pure JS
- **Google Fonts** - Poppins font family

## Brand Colors

- **Primary**: #002B5B (Deep Blue)
- **Gold**: #D4AF37 (Light Gold)
- **White**: #FFFFFF
- **Gray shades**: For text and backgrounds

## Setup Instructions

1. **Local Development**:
   - Place files in your web server directory (e.g., `xampp/htdocs/era/eraconsult/`)
   - Access via `http://localhost/era/eraconsult/`

2. **Assets**:
   - Add your logo image to `/assets/favicon.png`
   - Add social preview image to `/assets/social-preview.jpg`

3. **Customization**:
   - Update contact information in all pages
   - Replace placeholder images with actual photos
   - Configure form submissions to your backend/email service

## Form Integration

✅ **FULLY INTEGRATED!** All forms are connected to PHP backend with email notifications.

The website includes three forms:
- **Booking Form** (book.html) - For consultation appointments
- **Contact Form** (contact.html) - For general inquiries
- **Newsletter Form** (footer on all pages) - For email subscriptions

### How It Works:
1. User submits form via AJAX (no page reload)
2. PHP handler validates and processes data
3. Emails sent to both admin addresses
4. Confirmation email sent to user
5. Submission logged to `/logs/submissions.log`
6. User sees success message

### Email Recipients:
- **Primary:** support@eraaxisglobalconsult.com
- Both primary and secondary receive all form submissions

### Testing Your Forms:
Visit: **http://localhost/era/eraconsult/test-email.php**

This page tests your email configuration and shows system status.

### Email Setup:
See **[SETUP_GUIDE.md](SETUP_GUIDE.md)** for detailed email configuration instructions.

## File Structure

```
eraconsult/
├── index.html              # Home page
├── about.html              # About Us page
├── services.html           # Services page
├── book.html               # Booking form
├── contact.html            # Contact form
├── privacy.html            # Privacy Policy
├── terms.html              # Terms of Service
├── config.php              # Email configuration
├── book-handler.php        # Booking form handler
├── contact-handler.php     # Contact form handler
├── newsletter-handler.php  # Newsletter handler
├── test-email.php          # Email testing tool (delete in production)
├── .htaccess               # Apache configuration
├── SETUP_GUIDE.md          # Email setup instructions
├── README.md               # This file
├── css/
│   └── custom.css          # Custom animations & styles
├── js/
│   └── script.js           # Main JavaScript with AJAX
├── assets/
│   ├── favicon.png         # (add your favicon)
│   └── social-preview.jpg  # (add your og:image)
├── logs/                   # Form submission logs
│   └── submissions.log
└── data/                   # Newsletter subscribers
    └── subscribers.json
```

## Features Implemented

- ✅ Mobile-first responsive design
- ✅ Smooth scroll animations
- ✅ Testimonial carousel
- ✅ Form validation with real-time feedback
- ✅ Mobile navigation menu
- ✅ SEO-optimized meta tags
- ✅ Accessible markup
- ✅ Fast loading (CDN-based)
- ✅ **Email integration with PHP backend**
- ✅ **AJAX form submissions (no page reload)**
- ✅ **Automated email notifications**
- ✅ **Form submission logging**
- ✅ **Newsletter subscriber management**

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers

## Performance Optimization

- Tailwind CSS loaded via CDN
- Optimized images (use WebP format for production)
- Lazy loading ready
- Minimal JavaScript dependencies

## SEO Features

- Semantic HTML5 markup
- Meta descriptions on all pages
- Open Graph tags for social sharing
- Structured heading hierarchy
- Alt text for images

## Next Steps for Production

1. **Configure Gmail SMTP** - See [SETUP_GUIDE.md](SETUP_GUIDE.md) for instructions
2. **Delete test-email.php** - Remove testing file before deployment
3. **Add actual images** - Replace Unsplash placeholders with real photos
4. **Add favicon** - Place your logo in `/assets/favicon.png`
5. **Update config.php** - Change SITE_URL to your production domain
6. **Add SSL certificate** - Enable HTTPS
7. **Add Google Analytics** - For visitor tracking
8. **Optimize images** - Compress and convert to WebP
9. **Add sitemap.xml** and robots.txt
10. **Test forms thoroughly** - Verify all emails are received
11. **Set file permissions** - Secure logs/ and data/ directories

## Support

For questions or customization requests, contact the development team.

---

**Eraaxis Global Consult** - Empowering ideas. Elevating excellence.
