# [WIP] Galactic-Conquest

## Install

### 1. Windows
1. Install https://github.com/galactic-conquest/devbox.

## Commands
Get a list of possible commands
```
vendor/bin/inferno list
```

 Update project dependencies.
```
composer update
```

Create database schema
```
vendor/bin/inferno orm:schema-tool:create
```

Create test data and fixtures
```
vendor/bin/inferno app:doctrine:fixtures
```

Create a new handler
```
vendor/bin/inferno app:create:handler ModuleDir HandlerName
```

Clear cache data
```
vendor/bin/inferno app:cache-clear
```

## Configfiles
```
/config/config.php
/config/app.php
```


