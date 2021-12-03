## DX Core

Core version: `1.3.004`

Supported version of Geometry Dash: 1.0 - 1.3

Required version of PHP: 5.4+ (tested up to 7.4)

---

### Setup `(ENG)`

1. Upload the files on a webserver
2. Import `database.sql` into a MySQL/MariaDB database
3. Modify your database connection details in a `config/database.php` file
4. Configure the core in a `config/settings.php` file


### Установка `(РУС)`

1. Загрузите файлы на веб-сервер
2. Загрузите `database.sql` до базы данных MySQL/MariaDB
3. Измените занчения для подключения к базе данных в файле `config/database.php`
4. Настройте ядро в файле `config/settings.php`

---

### How to update? `(ENG)`

1. Replace all folders except `data`
2. Update the database using the files in the `__updates` folder. Follow the version carefully for correct updates

### Как обновится? `(РУС)`

1. Замените все папки, кроме `data`
2. Обновите базу данных с помощью файлов в папке `__updates`. Внимательно следите за версией для правильного обновления

---

### Commands in comments
* !rate \<difficulty\> \<stars\> - rate level
  * `Example 1:` !rate easy
  * `Example 2:` !rate hard 5
* !unrate - unrate level
* !suggest \<difficulty\> \<stars\> - send level for rating
  * `Example 1:` !suggest easy
  * `Example 2:` !suggest hard 5
* !delete - delete level
* !setacc \<userName\> - transfer level to another account
  * `Example:` !setacc DeXotik

---

### Credits

Developer: [DeXotik](https://vk.com/dexotik)
