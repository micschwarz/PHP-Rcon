<?php


namespace Rcon;


class Packet
{
    const SERVERDATA_AUTH = 3;
    const SERVERDATA_AUTH_RESPONSE = 2;
    const SERVERDATA_EXECCOMMAND = 2;
    const SERVERDATA_RESPONSE_VALUE = 0;

    const PACKET_AUTHORIZE = 5;
    const PACKET_COMMAND = 6;

    private $id;
    private $type;
    private $body;

    function __construct($id, $type, $body)
    {
        $this->id = $id;
        $this->type = $type;
        $this->body = $body;
    }

    /**
     * @return string
     */
    function getContent(): string
    {
        $packet = pack('VV', $this->id, $this->type);
        $packet .= $this->body . "\x00";
        $packet .= "\x00";

        $size = strlen($packet);

        return pack('V', $size) . $packet;
    }

    function getSize(): int
    {
        return strlen($this->getContent());
    }

    /**
     * @return mixed
     */
    function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    function getBody()
    {
        return $this->body;
    }
}