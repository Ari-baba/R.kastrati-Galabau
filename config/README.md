Config module

This folder contains central configuration for the application:

- `constants.php` — global constants (paths, upload settings, app name/version).
- `config.php` — high-level config and `config()` accessor (timezone, formats, upload settings).
- `database.php` — PDO factory `get_pdo()` and `db_query()` helper. Reads DB credentials from environment variables: `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`.

Usage example in other modules:

```php
require_once __DIR__ . '/config/constants.php';
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

$pdo = get_pdo();
// or
$stmt = db_query('SELECT * FROM reservations WHERE id = :id', ['id' => 1]);
```
