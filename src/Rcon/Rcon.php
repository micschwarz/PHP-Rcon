<?php


namespace Rcon;


use Rcon\Exception\ConnectionException;
use Rcon\Exception\ConnectionNotOpenException;

class Rcon
{
    const DEFAULT_CONNECTION_TIMEOUT = 3;

    private Stream $stream;
    private string $password;
    private bool $authorized = false;

    public function __construct(
        string $host,
        int $port,
        string $password,
        int $timeout = self::DEFAULT_CONNECTION_TIMEOUT
    )
    {
        $this->stream = new Stream($host, $port, $timeout);
        $this->password = $password;
    }

    /**
     * @return bool
     * @throws ConnectionException
     */
    public function connect(): bool
    {
        $this->stream->open();
        return $this->authorize();
    }

    /**
     * Executes command.
     *
     * @param string $command Command to execute
     * @return bool|mixed
     * @throws ConnectionNotOpenException Connection must be open.
     */
    public function execCommand(string $command)
    {
        if (!$this->isConnected())
            throw new ConnectionNotOpenException();


        $response = $this->stream->writeRead(
            new Packet(Packet::PACKET_COMMAND, Packet::SERVERDATA_EXECCOMMAND, $command),
            );

        if ($response->getId() === Packet::PACKET_COMMAND && $response->getType() === Packet::SERVERDATA_RESPONSE_VALUE)
            return $response->getBody();


        return false;
    }

    /**
     * Authorizes the user for the connection.
     *
     * @return bool were credentials correct
     * @throws ConnectionNotOpenException
     */
    private function authorize(): bool
    {
        $response = $this->stream->writeRead(
            new Packet(Packet::PACKET_AUTHORIZE, Packet::SERVERDATA_AUTH, $this->password),
            );

        $this->authorized = $response->getType() === Packet::SERVERDATA_AUTH_RESPONSE
            && $response->getId() === Packet::PACKET_AUTHORIZE;

        if (!$this->authorized)
            $this->stream->close();


        return $this->authorized;
    }

    public function isConnected(): bool
    {
        return $this->authorized;
    }

    /**
     * Disconnect from Rcon Server.
     */
    public function disconnect(): void
    {
        $this->stream->close();
    }
}