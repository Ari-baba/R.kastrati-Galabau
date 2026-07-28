Auth module

Files:
- `login.php` — login form with CSRF token
- `authenticate.php` — processes login, verifies password with `password_verify()`, uses prepared statements via `config/database.php`
- `session.php` — secure session helpers: `login_user()`, `logout_user()`, `enforce_session_timeout()`
- `csrf.php` — CSRF token helpers `generate_csrf_token()`, `validate_csrf_token()`, and `csrf_input_field()`
- `check_auth.php` — include in admin pages to require authentication
- `logout.php` — logs out the current user

Notes:
- Database access uses `../config/database.php` (PDO factory `get_pdo()` and `db_query()` helper).
- Configure environment variables for DB credentials: `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`.
- Session timeout default is 1800 seconds (30 minutes) configurable via `SESSION_TIMEOUT` env var.
