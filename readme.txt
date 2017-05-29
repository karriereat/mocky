# Mocky - A simple api mock server

## Installation

```
composer require karriere/mocky
```

## Starting the example
The example config can be started with the built-in php webserver:

```
php -S localhost:8888 -t public public/index.php
```

## Using the mock api
Before running your test you need to setup the test scope by calling `http://localhost:8888/setup/user-simple`

Then you can call the example user endpoint `http://localhost:8888/user`