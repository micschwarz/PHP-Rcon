<?php


namespace Rcon\Exception;


class ConnectionNotOpenException extends ConnectionException
{
    protected $message = 'Connection is not open yet.';
}