<?php

use Rcon\Exception\ConnectionException;
use Rcon\Exception\ConnectionNotOpenException as ConnectionNotOpenExceptionAlias;
use Rcon\Rcon;

include '../vendor/autoload.php';

echo '<pre>';

$rcon = new Rcon('localhost', '28016', 'testing'); // Create Rcon Connector
$authorized = false;

/* Open Connection */
try {
    $authorized = $rcon->connect(); // Open Connection
} catch (ConnectionException $e) {
    print('Exception: ' . $e->getMessage() . '<br/>');
}

print('Authorized: ' . ($authorized ? 'Yes' : 'No') . '<br/>');

if ($authorized)
    try {
        $playerList = $rcon->execCommand('list');// List Player
        print_r($playerList);
    } catch (ConnectionNotOpenExceptionAlias $e) {
        print('Exception: ' . $e->getMessage() . '<br/>');
    }