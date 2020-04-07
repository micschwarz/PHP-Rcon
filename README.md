# PHP Rcon

![PHP 7.4](https://img.shields.io/badge/PHP-7.4-blue "PHP 7.4")

A small and easy to use rcon connector

## Example

A complete example can be found in `/example`. It requires composer and docker-compose
You can test it with the docker-minecraft-server by running `docker-compose up`.
To start the included example run `composer run-script start-example` and navigate to `localhost:8000`

## Usage

### Connect
**1.Step:** Create Rcon Connector
```php
$rcon = new Rcon('localhost', '28016', 'testing'); // Connect to localhost:28016 with Password "testing"
```

**2.Step:** Connect and Authorize
Throws ConnectionException on Connection Error (Rcon Server not available, etc.).
Returns false if supplied password is wrong.
```php
$authorized = $rcon->connect();
```

### Execute Command
Throws ConnectionNotOpenException if connection has not been opened yet.
```php
$playerList = $rcon->execCommand('list'); // List all players
```

