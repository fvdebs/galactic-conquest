# [WIP] Galactic-Conquest

## Install

### For Windows
Follow the instructions here:\
https://github.com/galactic-conquest/devbox.

## App commands
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

## Other commands

Executes a tick.
```
vendor/bin/inferno app:tick:run
```

Executes a tick. Ignores universe tick interval.
```
vendor/bin/inferno app:tick:run --force-tick
```

Executes a tick. Ignores universe ranking interval.
```
vendor/bin/inferno app:tick:run --force-ranking
```

Executes a tick. Ignores tick and ranking interval.
```
vendor/bin/inferno app:tick:run --force-tick --force-ranking
```

## Configfiles
```
/config/config.php
/config/app.php
```
