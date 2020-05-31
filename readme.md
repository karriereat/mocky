<p align="center">
<a href="https://www.karriere.at/" target="_blank">
    <img width="400" src="http://www.kcdn.at/static/logomocky.png">
</a>
</p>

# Mocky - A simple API mock server
![Test](https://github.com/karriereat/mocky/workflows/test/badge.svg)
![Lint](https://github.com/karriereat/mocky/workflows/lint/badge.svg)

Mocky is a simple API mocking solution written in PHP based on the [Slim Framework](https://www.slimframework.com/).

## Installation
You can either create a new mock api by using the `mocky-template` as a starting point
```
composer create-project karriere/mocky-template /destination/path
```

or add mocky as a dependency to your project.
```
composer require karriere/mocky
```

## Configuration
With mocky you can define your test cases via JSON. The configuration consists of two file types:

* test files
* mock files

A test file contains all your test endpoints (grouped by the request method) and references one or multiple mock files. The mock file defines the mock data to return. This seperation allows you to reuse mock data over multiple tests.

### Writing a test file
A test file is stored in the `tests` folder and has the following basic structure

```json
{
  "REQUEST_METHOD": {
    "ENDPOINT": {
        "content-type": "application/json",
        "mock": "mock-file.json"
    }
  }
}
```

The test file is a map of request methods (GET, POST, PUT, DELETE, ...). Each request method entry contains a map of endpoints as key and the endpoint definition as value.

Each request definition must define a `content-type` and a `mock` field. The mock field is a reference to the mock data file to deliver.

*Example:*
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

#### Structuring your tests
To be able to structure your test json files by feature a test can be stored in a subfolder.

For example to store multiple tests for a user feature you can create a test under `tests/user/a-test.json`. This test has the following setup route `/setup/{scope}/user/a-test`.

### Writing a mock file
A mock file contains an array of mock responses. Each mock response must have a `status` and a `response` field. The `status` field defines the HTTP status code and the `response` field the API response in json format.

Use `example/mocks/users/list-users.json` as a reference.

The mock file uses an array of mock response because you often need different data on subsequent requests.

Lets consider a simple example. You want to test a user feature. Your frontend makes an GET api call to `/users` and receives 2 user entries. Then the test sends a POST to `/users` to create a new user and then executes the GET `/users` again. In the second case 3 user entries should be returned.

## Using mocky

To deliver your mock data you need to setup your test case. To do so you call the mocky setup endpoint `/setup/{scope}/{test-name}`.

The scope variable is needed to allow parallel access to mocky from different applications. The default scope is called `default`.

**Example:**
`http://localhost:8888/setup/default/user-simple`

This endpoint call will setup the `user-simple` test case defined in `tests/user-simple.json`.

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
