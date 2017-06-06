<p align="center">
<a href="https://www.karriere.at/" target="_blank">
    <img width="400" src="http://www.kcdn.at/static/logomocky.png">
</a>
</p>


# Mocky - A simple API mock server

Mocky is a simple API mocking solution written in PHP based on the [Slim Framework](https://www.slimframework.com/).

## Creating a new mocky API

```
composer create-project karriere/mocky /destination/path
```

## Configuration
With mocky you can define your test cases via JSON. These JSON files are stored in the `tests` folder. Each test case can contain multiple endpoint definitions.

```json
{
  "GET": {
    "/users": {
      "content-type": "application/vnd.api+json",
      "mock": "users/list-users.json"
    },
    "/users/1": {
      "content-type": "application/vnd.api+json",
      "mock": "users/single-user.json"
    }
  }
}
```

The root elements of the test definintion defines the request methods. Each request method can contain multiple route definitions:

### Request Definition
The key of each request definition is the route (e.g. `/users`). Each request definitions must define a `content-type` and a `mock` field. The mock field is a reference to the mock data file to deliver.

### Mock Data
A mock data file contains an array of mock responses. Each mock response must have a `status` and a `response` field. The `status` field defines the HTTP status code and the `response` field the API response in json format.

Use `example/mocks/users/list-users.json` as a reference.

## Using mocky

To deliver your mock data you need to setup your test case. To do so you call the mocky setup endpoint `/setup/{scope}/{test-name}`.

The scope variable is needed to allow parallel access to mocky from different applications. The default scope is called `default`.

**Example:**
`http://localhost/setup/default/users-simple`

This endpoint call will setup the `users-simple` test case defined in `tests/users-simple.json`.

To be able to use another scope you simply call the `setup` route with your custom scope name and on each real API call you need to include a request header called `Mocky-Scope` with the custom scope name as value.

<a href="http://www.youtube.com/watch?feature=player_embedded&v=UYyWKrbJjAM" target="_blank"><img src="http://img.youtube.com/vi/UYyWKrbJjAM/0.jpg" alt="Mocky Demo" width="240" height="180" border="10" /></a>

## Webserver Setup

### Built-In PHP Webserver (local development)
```
php -S localhost:8888 -t public public/index.php
```

### Nginx
```
server {
    listen 80;
    server_name mocky.example.com;
    index index.php;
    error_log off;
    access_log off;
    root /path/to/public;

    location / {
        try_files $uri /index.php$is_args$args;
    }

    location ~ \.php {
        try_files $uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param SCRIPT_NAME $fastcgi_script_name;
        fastcgi_index index.php;
        fastcgi_pass 127.0.0.1:9000;
    }
}
```

### Apache
Your document root points to the `public` directory and you need to make sure that `mod_rewrite` is available.

## License
Apache License 2.0 Please see [LICENSE](LICENSE) for more information.
