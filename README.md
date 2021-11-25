## DX Core

Core version: `1.2.001`

Supported version of Geometry Dash: 1.0 - 1.2

Required version of PHP: 5.4+ (tested up to 7.4)

---

### Setup

1. Upload the files on a webserver
2. Import `database.sql` into a MySQL/MariaDB database
3. Modify your database connection details in a `config/database.php`
```php
$dbHost = '127.0.0.1';
$dbUser = 'root';
$dbPassword = '';
$dbName = 'dxcore';
```
4. Configure the core in a `config/settings.php`
```php
$checkGameVersion = true; // false - disable game version check; true - enable game version check
$totalGameVersion = 3; // 3 - version 1.2

$disabledNames = ["Player", "RobTop"]; // Disabled names
$uniqueNames = true; // false - users can be with the same name; true - only unique names
$usersLimiting = true; // false - disable user limit; true - enable user limit
$usersLimitingCount = 3; // The number of users for the limit
$usersLimitingTime = 3600; // Time of counting users for the limit (in seconds)

$levelLimiting = true; // false - disable level limit; true - enable level limit
$levelLimitingCount = 3; // The number of levels for the limit
$levelLimitingTime = 3600; // Time of counting levels for the limit (in seconds)

$commentLimiting = true; // false - disable comment limit; true - enable comment limit
$commentLimitingCount = 10; // The number of comments for the limit
$commentLimitingTime = 3600; // Time of counting comments for the limit (in seconds)
$commentLimitingAtLevel = true; // false - disable comment limit at level; true - enable comment limit at level
$commentLimitingAtLevelCount = 3; // The number of comments per level for the limit

$likesLimiting = true; // false - disable likes limit; true - enable likes limit
$likesLimitingCount = 20; // The number of likes for the limit
$likesLimitingTime = 3600; // Time of counting likes for the limit (in seconds)
```

### Credits

Developer: [DeXotik](https://vk.com/dexotik)
