Admin module

Files implemented:
- `index.php` — redirects to `dashboard.php` (protected)
- `dashboard.php` — shows totals and recent reservations
- `reservations.php` — list, search, filter, delete reservations
- `reservation_details.php` — view single reservation
- `about.php` — edit About Us content and optional image upload
- `homepage.php` — edit hero title/description and optional image upload
- `gallery.php` — upload, list, delete gallery images
- `uploads.php` — informational page about uploads folder

Security:
- All pages include `auth/check_auth.php` to enforce authentication.
- CSRF protection via `auth/csrf.php` for state-changing actions.
- Prepared statements used via `config/database.php` helpers.
