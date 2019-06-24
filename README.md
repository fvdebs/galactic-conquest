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

Project setup
```
vendor/bin/inferno app:setup
```

Create a new handler
```
vendor/bin/inferno app:create:handler ModuleDir HandlerName
```

Clear cache data
```
vendor/bin/inferno app:cache-clear
```

Run static code analyzer
```
composer analyze
```

## Other commands

Executes a tick.
```
vendor/bin/inferno app:tick:run
```

Executes a tick. Ignores universe tick interval.
```
vendor/bin/inferno app:tick:run --force
```

## Config
```
/config/config.php
/config/app.php
```
