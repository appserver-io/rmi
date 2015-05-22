<?php

/**
 * AppserverIo\RemoteMethodInvocation\RemoteContextConnection
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/rmi
 * @link      http://www.appserver.io
 */

namespace AppserverIo\RemoteMethodInvocation;

use Guzzle\Http\Client;
use Guzzle\Http\Exception\CurlException;
use AppserverIo\Collections\CollectionInterface;

/**
 * Connection implementation to invoke a remote method call over a socket.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/rmi
 * @link      http://www.appserver.io
 */
class RemoteContextConnection implements ConnectionInterface
{

    /**
     * The default transport to use.
     *
     * @var string
     */
    const DEFAULT_SCHEME = 'http';

    /**
     * The default client sockets IP address.
     *
     * @var string
     */
    const DEFAULT_HOST = '127.0.0.1';

    /**
     * The default client sockets port.
     *
     * @var integer
     */
    const DEFAULT_PORT = 8585;

    /**
     * The default transport to use.
     *
     * @var string
     */
    protected $transport = RemoteContextConnection::DEFAULT_SCHEME;

    /**
     * The client socket's IP address.
     *
     * @var string
     */
    protected $address = RemoteContextConnection::DEFAULT_HOST;

    /**
     * The client socket's port.
     *
     * @var integer
     */
    protected $port = RemoteContextConnection::DEFAULT_PORT;

    /**
     * The name of the webapp using this client connection.
     *
     * @var string
     */
    protected $appName;

    /**
     * The storage for the sessions.
     *
     * @var \AppserverIo\Collections\CollectionInterface
     */
    protected $sessions = null;

    /**
     * Parser to process the remote method call.
     *
     * @var \AppserverIo\RemoteMethodInvocation\RemoteMethodCallParser
     */
    protected $parser;

    /**
     * The HTTP client we use for connection to the persistence container.
     *
     * @var \Guzzle\Http\Client
     */
    protected $client;

    /**
     * Injects the collection for the sessions.
     *
     * @param \AppserverIo\Collections\CollectionInterface $sessions The collection for the sessions
     *
     * @return void
     */
    public function injectSessions(CollectionInterface $sessions)
    {
        $this->sessions = $sessions;
    }

    /**
     * Returns the collection with the sessions.
     *
     * @return \AppserverIo\Collections\CollectionInterface The collection with the sessions
     */
    public function getSessions()
    {
        return $this->sessions;
    }

    /**
     * Injects the remote method call parser.
     *
     * @param \AppserverIo\RemoteMethodInvocation\RemoteMethodCallParser $parser The remote method call parser
     *
     * @return void
     */
    public function injectParser(RemoteMethodCallParser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * Returns the parser to process the remote method call.
     *
     * @return \AppserverIo\RemoteMethodInvocation\RemoteMethodCallParser The parser instance
     */
    public function getParser()
    {
        return $this->parser;
    }

    /**
     * Sets the clients webapp name
     *
     * @param string $appName Name of the webapp using this client connection
     *
     * @return void
     */
    public function injectAppName($appName)
    {
        $this->appName = $appName;
    }

    /**
     * Returns the name of the webapp this connection is for
     *
     * @return string The webapp name
     */
    public function getAppName()
    {
        return $this->appName;
    }

    /**
     * Sets the servers IP address for the client to connect to.
     *
     * @param string $address The servers IP address to connect to
     *
     * @return void
     */
    public function injectAddress($address)
    {
        $this->address = $address;
    }

    /**
     * Returns the client sockets IP address.
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     *  Sets  the servers port for the client to connect to.
     *
     * @param integer $port The servers port to connect to
     *
     * @return void
     */
    public function injectPort($port)
    {
        $this->port = $port;
    }

    /**
     * Returns the client port.
     *
     * @return integer The client port
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     *  Sets the transport to use.
     *
     * @param integer $transport The transport to use
     *
     * @return void
     */
    public function injectTransport($transport)
    {
        $this->transport = $transport;
    }

    /**
     * Returns the transport to use.
     *
     * @return integer The transport to use.
     */
    public function getTransport()
    {
        return $this->transport;
    }

    /**
     * Creates the connection to the container.
     *
     * @return void
     */
    public function connect()
    {
        $this->client = new Client($this->getBaseUrl());
    }

    /**
     * Shutdown the connection to the container.
     *
     * @return void
     */
    public function disconnect()
    {
        $this->client = null;
    }

    /**
     * Returns the socket the connection is based on.
     *
     * @return \Guzzle\Http\Client The socket instance
     */
    public function getSocket()
    {
        return $this->client;
    }

    /**
     * Sends the remote method call to the container instance.
     *
     * @param \AppserverIo\RemoteMethodInvocation\RemoteMethodInterface $remoteMethod The remote method instance
     *
     * @return mixed The response from the container
     * @see AppserverIo\RemoteMethodInvocation\ConnectionInterface::send()
     *
     * @throws \Exception
     */
    public function send(RemoteMethodInterface $remoteMethod)
    {

        // connect to the server if necessary
        $this->connect();

        // set address + port + appName
        $remoteMethod->setAddress($this->getAddress());
        $remoteMethod->setPort($this->getPort());
        $remoteMethod->setAppName($this->getAppName());

        // serialize the remote method and write it to the socket
        $packed = RemoteMethodProtocol::pack($remoteMethod);

        // invoke the RMC with a number of retries
        $maxRetries = 0;
        $retry = true;
        while ($retry) {
            try {
                // send a POST request
                $request = $this->getSocket()->post($this->getPath(), array('timeout' => 5));
                $request->setBody($packed);
                $response = $request->send();

                $retry = false;

            } catch (CurlException $ce) {
                $maxRetries++;
                if ($maxRetries >= 5) {
                    $retry = false;
                    throw $ce;
                }
            }
        }

        // read the remote method call result
        $result = RemoteMethodProtocol::unpack($response->getBody());

        // if an exception returns, throw it again
        if ($result instanceof \Exception) {
            throw $result;
        }

        // close the connection and return the data
        return $result;
    }

    /**
     * Prepares path for the connection to the persistence container.
     *
     * @return string The path to define the persistence container module
     */
    protected function getPath()
    {
        return '/' . $this->getAppName() . '/index.pc';
    }

    /**
     * Prepares the base URL we used for the connection to the persistence container.
     *
     * @return string The default base URL
     */
    protected function getBaseUrl()
    {
        // initialize the requested URL with the default connection values
        return $this->getTransport() . '://' . $this->getAddress() . ':' . $this->getPort();
    }

    /**
     * Initializes a new session instance.
     *
     * @return \AppserverIo\RemoteMethodInvocation\SessionInterface The session instance
     * @see \AppserverIo\RemoteMethodInvocation\ConnectionInterface::createContextSession()
     */
    public function createContextSession()
    {
        $this->sessions->add($session = new ContextSession($this));
        return $session;
    }

    /**
     * Returns the application instance.
     *
     * @return \AppserverIo\Psr\Application\ApplicationInterface|null The application instance
     */
    public function getApplication()
    {
        return;
    }
}
