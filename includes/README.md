Includes module

This folder contains shared components and helper utilities used by the application.

Files:
- `header.php` — standardized HTML head and page header includes CSS/JS assets.
- `navbar.php` — navigation menu for public pages.
- `footer.php` — shared footer with contact info and social links.
- `functions.php` — generic helper functions for sanitization, redirects, date formatting, and flash messages.
- `validation.php` — input validation for names, phone numbers, required fields, and image uploads.
- `session.php` — session initialization, flash message handling, and session state helpers.
- `alerts.php` — alert rendering helpers for info, success, warning, and error messages.

Usage:
```php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
require_once __DIR__ . '/includes/footer.php';
``` 

Make sure `config/constants.php` is loaded before `header.php` so base URLs and app constants are available.
