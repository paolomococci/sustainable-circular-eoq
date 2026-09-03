# Sustainable Circular Economic Order Quantity

Item Management System

---

![Item Management System landing page](./screenshots/ItemManagementSystem_landing-page.png)

---

Web application developed using the Symfony 8 PHP framework.

The instructions below walk you through:

1. create the directory on the GNU/Linux server
2. init a local Git repository
3. setup of `/etc/hosts` file
4. setup of VSCode
5. HTTPS setup
6. install Symfony CLI
7. scaffolding
8. MariaDB database setup
9. route checking

**Before you begin:**

- First, replace all placeholders like `XXX`, `developer_username`, `developer_passphrase`, and sensitive passwords with your actual local values.
- Second, ensure that the necessary services and tools are installed and running, as required, on both the server and your GNU/Linux laptop or desktop, (for example: `git`, `openssl`, `httpd`/`apache2`, `php-fpm`, `mariadb-client`, etc.).

## `1` create the directory that will host the new web app

**Commands to be typed on the server host.**

```shell
cd /var/www/html/
sudo mkdir items.local
cd items.local/ && sudo chown --recursive $(whoami):apache .
```

---

## `2` init a local Git repository

**Commands to be typed on the development host.**

Create a `.gitignore` file:

```shell
cd ~/Workshop/Projects/fullstack-project/LAMP/items.local
nano .gitignore
```

Edit `.gitignore` file:

```txt
.vscode/
.notes/
.tests/
assets/
bin/
public/assets/
stores/
stubs/
var/
vendor/
.editorconfig
compose.override.yaml
compose.yaml
composer.lock
importmap.php
phpunit.dist.xml
symfony.lock
*.pdf
*.env*
*.log
*.cache
```

Then give the following commands:

```shell
git --help
git init
git branch -m main
git status
developer_username=$(whoami)
git config user.name "${developer_username}"
git config user.email "${developer_username}@example.local"
git add .
git commit -m "initializing the local repository"
git tag -a v0.0.0 -m "starting version of clean repo"
git log
git checkout -b staging
git checkout -b draft
git checkout -b wip
git branch --list | wc -l
git branch --list
```

Examining the `.git/config` file:

```shell
ls -al
cat .git/config
```

you will get something similar to the following:

```txt
[core]
	repositoryformatversion = 0
	filemode = true
	bare = false
	logallrefupdates = true
[user]
	name = developer_username
	email = developer_username@example.local
```

And, after each change, the cycle repeats:

```shell
git status
git add .
git commit -m "further adjustments"
git log -3
git tag -a v0.0.1 -m "further adjustments"
git branch --list
```

Once the changes have been verified as working, the other branches of the repository can also be updated in cascade:

```shell
git checkout staging && \
git merge --no-ff draft -m "merge draft into staging" && \
git checkout main && \
git merge --no-ff staging -m "merge staging into main" && \
git checkout draft
```

If something were to go wrong:

```shell
git reset --hard v0.0.0
```

---

## `3` setup of `/etc/hosts` on the host used for development

I edit the following configuration file:

```shell
sudo nano /etc/hosts
```

and I add this line:

```txt
192.168.XXX.XXX         items.local       www.items.local
```

**Obviously, the correct values of your IP address of interest must be inserted in place of the Xs.**

---

## `4` setup of VSCode

Edit sftp.json like this:

```json
{
  "$schema": "http://json-schema.org/draft-07/schema",
  "name": "items.local",
  "username": "developer_username",
  "privateKeyPath": "/home/developer_username/.ssh/id_rsa",
  "passphrase": "developer_passphrase",
  "host": "192.168.XXX.XXX",
  "remotePath": "/var/www/html/items.local",
  "port": 22,
  "connectTimeout": 20000,
  "uploadOnSave": true,
  "watcher": {
    "files": "dist/*.{js,css}",
    "autoUpload": false,
    "autoDelete": false
  },
  "syncOption": {
    "delete": true,
    "update": false
  },
  "ignore": [
    ".vscode",
    ".notes",
    ".tests",
    ".howto",
    ".setup",
    ".git",
    ".DS_Store",
    "stubs",
    "docs",
    "images",
    "sql",
    ".git",
    "*.rest",
    "*.sql",
    "TEMP",
    "nbproject",
    "probe.http",
    "README.md"
  ]
}
```

**Please note, you will need to remember to set the `username`, `privateKeyPath`, `passphrase` and `host` fields appropriately.**

**Again, the correct values of your IP address of interest must be inserted in place of the Xs.**

Edit settings.json like this:

```json
{
  "cSpell.language": "en",
  "files.associations": {
    "*.css": "tailwindcss",
    "*.twig": "twig"
  },
  "emmet.includeLanguages": {
    "twig": "html"
  },
  "tailwindCSS.includeLanguages": {
    "html": "html",
    "javascript": "javascript",
    "css": "css"
  },
  "editor.quickSuggestions": {
    "strings": true
  },
  "intelephense.diagnostics.undefinedMethods": false,
  "cSpell.words": [],
  "editor.defaultColorDecorators": "auto",
  "editor.colorDecoratorsLimit": 5000
}
```

---

## `5` HTTPS setup with a self-signed certificate

### parameter for generate keys:

Here is just an example of the parameters to keep on hand:

```txt
[national_acronym]
[state]
[city]
items.local
items.local
items.local
[webmaster@localhost]
```

It is obvious that the first three parameters must be appropriately valued.

### generate certificate

Therefore I can proceed with the generation of the self-signed certificate without the passphrase thanks to the `-nodes` flag:

```shell
su -
ls -al /etc/ssl/
```

If a `private` directory doesn't exist, I proceed as follows:

```shell
mkdir /etc/ssl/private
chmod 700 /etc/ssl/private/
```

Otherwise, I focus on generating a fake certificate for development purposes:

```shell
openssl req -new -x509 -nodes -days 365 -newkey rsa:2048 -keyout /etc/ssl/private/items.local.key -out /etc/ssl/certs/items.local.crt
ls -al /etc/ssl/private/
ls -al /etc/ssl/certs/
```

### Apache configuration

**file `/etc/httpd/conf.d/items.local.conf`**

```shell
nano /etc/httpd/conf.d/items.local.conf
```

```xml
<VirtualHost *:80>
        ServerAdmin webmaster@localhost
        ServerName items.local
        ServerAlias www.items.local
        DocumentRoot /var/www/html/items.local/public
        Redirect permanent "/" "https://items.local/"
</VirtualHost>

<VirtualHost *:443>
        ServerAdmin webmaster@localhost
        ServerName items.local
        ServerAlias www.items.local
        DocumentRoot /var/www/html/items.local/public

        <Directory /var/www/html/items.local/public>
                Options Indexes FollowSymLinks MultiViews
                AllowOverride All
                Require all granted
                DirectoryIndex index.php index.html
        </Directory>

        SSLEngine on

        SSLCertificateFile /etc/ssl/certs/items.local.crt
        SSLCertificateKeyFile /etc/ssl/private/items.local.key

        ErrorLog /var/log/httpd/items_local_error_log

        <FilesMatch "\.(cgi|shtml|phtml|php|phar)$">
                SSLOptions +StdEnvVars
        </FilesMatch>
</VirtualHost>
```

**create the index page**

```shell
exit
mkdir public
nano /var/www/html/items.local/public/index.php
```

```php
<? phpinfo();
```

**config test and reload**

```shell
apachectl configtest
sudo systemctl reload httpd
systemctl status httpd --no-pager
```

If I encounter any problems I can investigate with the following command:

```shell
tail -n 5 /var/log/httpd/items_local_error_log
```

If you receive reports about file permission issues, you can proceed with the following commands:

```shell
sudo systemctl restart httpd
systemctl status httpd --no-pager
```

---

## `6` install `Symfony CLI`

**Of course it would be best to follow the instructions documented on the official website.**

I'll try to summarize the use of the `wget` command:

```shell
wget --spider https://get.symfony.com/cli/installer
wget https://get.symfony.com/cli/installer -O - | bash
```

Now, to adhere to modern conventions and respect the `XDG` hierarchical standard it will be necessary to continue as follows:

```shell
ls -l ~/.config/ | grep symfony-cli
mv ~/.symfony5/ ~/.config/symfony-cli/
ls -al ~/.config/symfony-cli/ | grep bin
nano ~/.bashrc
```

Add this line to `~/.bashrc` file:

```txt
export PATH=$HOME/.config/symfony-cli/bin:$PATH
```

In this case, you can also omit the double quotes since there are no special characters in the path.

And now type:

```shell
. ~/.bashrc
symfony --help
symfony check:requirements
```

## `7` scaffolding

With the following commands:

```shell
cd /var/www/html/items.local/
symfony new items_webapp --version="8.1.*" --webapp --no-git
```

Or this command:

```shell
symfony new items_webapp --version="8.1.*" --no-git
```

best suited to building a microservice, console application, or web API.

### setup of web application

Moving forward, we need to generate real files containing all the assets usually managed by Symfony and place them under the `public/assets` path.
This is necessary to replicate a true deployment scenario:

```shell
ls -l
cd items_webapp/
php bin/console --help asset-map:compile
ls -al public/
php bin/console asset-map:compile --verbose
ls -al public/assets/
```

After this, clear the cache to prevent any potential issues with the application loading the new assets:

```shell
php bin/console --help cache:clear
php bin/console cache:clear --verbose
```

Assign the files to the appropriate owners:

```shell
sudo chown --recursive $(whoami):apache .
ls -al
```

Establish the proper access rights:

```shell
find var/ -type d -exec chmod 775 {} +
find var/ -type f -exec chmod 664 {} +
ls -al
```

Also, it's important to check that the Apache `mod_rewrite` module is enabled and operating properly:

```shell
httpd -M | grep rewrite
```

If the rewrite module is not installed it will be necessary to proceed, for example, with the following commands:

```shell
sudo a2enmod rewrite
```

Now it's time to make the appropriate changes to the web application configuration file:

```shell
sudo nano /etc/httpd/conf.d/items.local.conf
```

Edit:

```xml
<VirtualHost *:80>
        ServerAdmin webmaster@localhost
        ServerName items.local
        ServerAlias www.items.local
        DocumentRoot /var/www/html/items.local/items_webapp/public
        Redirect permanent "/" "https://items.local/"
</VirtualHost>

<VirtualHost *:443>
        ServerAdmin webmaster@localhost
        ServerName items.local
        ServerAlias www.items.local
        DocumentRoot /var/www/html/items.local/items_webapp/public

        <Directory /var/www/html/items.local/items_webapp/public>
                Options Indexes FollowSymLinks MultiViews
                AllowOverride All
                Require all granted
                DirectoryIndex index.php index.html

                # Rewrite must process all requests that are neither files nor directories by rewriting them to point to `index.php`.
                # The Web Debug Toolbar, or `_wdt`, isn't an actual directory under the `public` directory.
                # It's implemented as a virtual route managed by the framework and serves as a Symfony endpoint.
                <IfModule mod_rewrite.c>
                        RewriteEngine On
                        RewriteCond %{REQUEST_FILENAME} !-f
                        RewriteCond %{REQUEST_FILENAME} !-d
                        RewriteRule ^(.*)$ index.php [QSA,L]
                </IfModule>
        </Directory>

        SSLEngine on

        SSLCertificateFile /etc/ssl/certs/items.local.crt
        SSLCertificateKeyFile /etc/ssl/private/items.local.key

        ErrorLog /var/log/httpd/items_local_error_log

        <FilesMatch "\.(cgi|shtml|phtml|php|phar)$">
                SSLOptions +StdEnvVars
        </FilesMatch>
</VirtualHost>
```

Now you can verify the correctness of the configurations and restart the web server:

```shell
sudo apachectl configtest
sudo systemctl restart httpd
systemctl status httpd --no-pager
```

optionally you can also type:

```shell
sudo systemctl restart php-fpm
systemctl status php-fpm --no-pager
```

---

## `8` MariaDB database setup

Command to connect to the development database:

```shell
mariadb --help
mariadb --host=192.168.XXX.XXX --user=$(whoami) --password
```

**Remembering to replace the placeholder `192.168.XXX.XXX` with your IP address.**

Now, from the SQL command line I can type:

```sql
SELECT VERSION() AS server_version;
SHOW DATABASES LIKE;
CREATE DATABASE IF NOT EXISTS `items_db` DEFAULT CHARACTER SET utf8mb4 DEFAULT COLLATE utf8mb4_general_ci;
-- With `utf8mb4_general_ci` for applications that require accurate sorting.
```

And check:

```sql
-- Verify the existence of the database.
SHOW DATABASES LIKE 'items_db';
-- An additional way to verify the existence of the database.
SELECT SCHEMA_NAME FROM information_schema.SCHEMATA WHERE SCHEMA_NAME = 'items_db';
-- Shows the actual creation of the database.
SHOW CREATE DATABASE items_db;
-- Consult the `information_schema`, which stores the exact metadata is the source of truth.
SELECT
  SCHEMA_NAME AS db,
  DEFAULT_CHARACTER_SET_NAME AS charset,
  DEFAULT_COLLATION_NAME AS collation
FROM information_schema.SCHEMATA WHERE SCHEMA_NAME = 'items_db';
QUIT;
```

### database setup

Here's how to reset login credentials for accessing the `MariaDB` database, based on my experience:

```shell
ssh developer_username@192.168.XXX.XXX
sudo mariadb -u root
```

Or, in extreme cases, given the invasiveness entailed by this second method:

```shell
ssh developer_username@192.168.XXX.XXX
systemctl status mariadb
sudo systemctl stop mariadb
sudo mysqld_safe --skip-grant-tables &
sudo mariadb -u root
```

You can now proceed using the SQL shell with the following statements:

```sql
SELECT VERSION() AS server_version; -- To determine the version of the MariaDB database system.
SELECT PASSWORD('plaintext_password'); -- In order to retrieve the password hash.
ALTER USER 'root'@'localhost' IDENTIFIED BY PASSWORD 'password_hash_including_asterisk';
FLUSH PRIVILEGES;
QUIT;
```

Restart sever database:

```shell
sudo systemctl start mariadb
systemctl status mariadb
mariadb -u root -p
```

```sql
SELECT `user`, `host`, `Grant_priv`, `Super_priv` FROM `mysql`.`user` ORDER BY `user` DESC;
SELECT PASSWORD('plaintext_password');
CREATE USER IF NOT EXISTS 'developer_username'@'127.0.0.1' IDENTIFIED BY PASSWORD 'password_hash_including_asterisk';
GRANT ALL ON *.* TO 'developer_username'@'127.0.0.1';
CREATE USER IF NOT EXISTS 'developer_username'@'localhost' IDENTIFIED BY PASSWORD 'password_hash_including_asterisk';
GRANT ALL ON *.* TO 'developer_username'@'localhost' WITH GRANT OPTION;
-- It allows the granting of privileges, effectively giving the same power as the root user.
FLUSH PRIVILEGES;
SELECT user, host, Grant_priv, Super_priv FROM mysql.user WHERE user='developer_username' AND host='127.0.0.1' OR user='developer_username' AND host='localhost';
QUIT;
```

Should it become necessary to change the password at a later date:

```sql
SELECT PASSWORD('new_plaintext_password');
ALTER USER IF EXISTS 'developer_username'@'127.0.0.1' IDENTIFIED BY PASSWORD 'new_password_hash_including_asterisk';
ALTER USER IF EXISTS 'developer_username'@'localhost' IDENTIFIED BY PASSWORD 'new_password_hash_including_asterisk';
```

### configuration of the database

Configuration of the database required for the application created with the Symfony framework in the appropriate .env files:

```env
DATABASE_URL="mysql://developer_username:db_password@127.0.0.1:3306/items_db?serverVersion=mariadb-10.5.29&charset=utf8mb4"
```

---

## `9` route checking

To ensure that paths are formatted correctly or meet specific criteria:

```shell
php bin/console debug:router
```

## `10` CRUD implementation

Now I will proceed to create an entity and the CRUD that concerns it:

### create entity

```shell
php bin/console help make:entity
php bin/console make:entity Item --verbose
```

Now all the fields that will form the record track of the new entity must be inserted.

Change the table name by adding the following annotation to the entity class `Item`:

```php
#[ORM\Table(name: 'items')]
```

```shell
php bin/console help make:migration
php bin/console make:migration --formatted --verbose
php bin/console doctrine:migrations:migrate -vv --dry-run
php bin/console doctrine:migrations:migrate -vv
```

### after migration

For example, from client you can connect to `MariaDB` with the following command:

```shell
mariadb --host=192.168.XXX.XXX --user=$(whoami) --password
```

to then verify the changes made by the migration:

```sql
SHOW DATABASES;
SHOW TABLES FROM items_db;
DESCRIBE items_db.items;
SELECT id, name, description FROM items_db.items;
quit
```

### make CRUD

```shell
php bin/console help make:crud
php bin/console make:crud Item --verbose
```

### tool to automatically fix PHP code style

```shell
composer require --dev friendsofphp/php-cs-fixer
```

So I can try to correct the style of the PHP code:

```shell
ls -l ./vendor/bin/php-cs-fixer
./vendor/bin/php-cs-fixer check ./src
./vendor/bin/php-cs-fixer fix ./src
```
