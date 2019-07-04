# [WIP] Galactic-Conquest

## Install

### For Windows
Follow the instructions here:\
https://github.com/galactic-conquest/devbox.

## App
List commands
```
vendor/bin/inferno list
```

Update frontend and backend dependencies
```
npm install
composer update
```

Setup
```
vendor/bin/inferno app:setup
```

Frontend build
```
npm run build
```

## Tick

Executes a tick.
```
vendor/bin/inferno app:tick:run
```

Executes a tick. Ignores universe tick interval.
```
vendor/bin/inferno app:tick:run --force
```

## Configs
```
/config/config.php
/config/app.php
```

## Test

Run static code analyzer
```
composer analyze
```
