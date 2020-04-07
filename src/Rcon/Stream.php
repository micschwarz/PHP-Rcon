<?php


namespace Rcon;


use Rcon\Exception\ConnectionException;
use Rcon\Exception\ConnectionNotOpenException;

class Stream
{
    private $socket;

    private string $host;
    private int $port;
    private int $timeout;

    public function __construct(
        string $host,
        int $port,
        int $timeout)
    {
        $this->host = $host;
        $this->port = $port;
        $this->timeout = $timeout;
    }

    /**
     * Opens Stream to Rcon Server.
     *
     * @throws ConnectionException Stream could not be opened.
     */
    function open(): void
    {
        $this->socket = fsockopen($this->host, $this->port, $errno, $errstr, $this->timeout);

        if (!$this->socket) {
            throw new ConnectionException('Could not establish connection');
        }

        stream_set_timeout($this->socket, $this->timeout, 0);
    }

    /**
     * Closes Stream to Rcon Server.
     */
    function close(): void
    {
        if ($this->socket) {
            fclose($this->socket);
        }
    }

    /**
     * Writes Packet to the Stream.
     *
     * @param Packet $packet Request
     * @throws ConnectionNotOpenException Stream needs to be opened
     */
    function write(Packet $packet): void
    {
        if (!$this->isOpen())
            throw new ConnectionNotOpenException();

        fwrite($this->socket, $packet->getContent(), $packet->getSize());
    }

    /**
     * Reads Packet from Stream.
     *
     * @return Packet Answer
     * @throws ConnectionNotOpenException Stream needs to be opened
     */
    function read(): Packet
    {
        if (!$this->isOpen())
            throw new ConnectionNotOpenException();

        $sizeData = fread($this->socket, 4);
        $sizePack = unpack('V1size', $sizeData);
        $size = $sizePack['size'];

        $data = unpack('V1id/V1type/a*body', fread($this->socket, $size));

        return new Packet(
            $data['id'],
            $data['type'],
            $data['body'],
        );
    }

    function isOpen(): bool {
        return isset($this->socket);
    }

    /**
     * Writes Packet to Stream and reads answer.
     *
     * @param Packet $packet Request
     * @return Packet Answer
     * @throws ConnectionNotOpenException Stream needs to be opened
     */
    function writeRead(Packet $packet): Packet {
        $this->write($packet);
        return $this->read();
    }
}