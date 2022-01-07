## DX Core

Core version: `2.0.003`

Supported version of Geometry Dash: 1.0 - 1.1

Required version of PHP: 5.4+ (tested up to 7.4)

---

### Setup `(ENG)`

1. Upload the files on a webserver
2. Import `database.sql` into a MySQL/MariaDB database
3. Modify your database connection details in a `config/database.php` file
4. Configure the core in files in the `config/` folder


### Установка `(РУС)`

1. Загрузите файлы на веб-сервер
2. Загрузите `database.sql` до базы данных MySQL/MariaDB
3. Измените занчения для подключения к базе данных в файле `config/database.php`
4. Настройте ядро в файлаз папки `config/`

---

### How to update? `(ENG)`

1. Replace all folders except `data`
2. Update the database using the files in the `__updates` folder. Follow the version carefully for correct updates

### Как обновится? `(РУС)`

1. Замените все папки, кроме `data`
2. Обновите базу данных с помощью файлов в папке `__updates`. Внимательно следите за версией для правильного обновления

---

### Commands list `(ENG)`
* !delete `This command deletes the level`
* !rate \<difficulty\> \<featured\> `This command rates the level`
  * Values for `<difficulty>`: na, easy, normal, hard, harder, insane
  * Values for `<featured>`: 0 or 1
  * `Exapmle 1`: !rate easy
  * `Exapmle 2`: !rate hard 1
* !unrate `This command unrates the level`
* !featured `This command toggle featured at the level`
* !rename \<levelName\> `This command renames the level`
  * The value `<levelName>` can be written in Latin letters, numbers and spaces
  * `Exapmle 1`: !rename itsBest
  * `Exapmle 2`: !rename Unreal Level

---

### Credits

Developer: [DeXotik](https://vk.com/dexotik)
