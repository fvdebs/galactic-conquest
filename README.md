# [WIP] Galactic-Conquest

## Install

### 1. Composer
1.  Execute following commands in main directory.
```
cd /var/www/dev.galactic-conquest.com
composer update
 ```

### 2. Database
1. Create database "gc" with "utf8_unicode_ci".
2. Execute following commands in main directory.
```
vendor/bin/inferno orm:schema-tool:create
vendor/bin/inferno app:doctrine:fixtures
 ```