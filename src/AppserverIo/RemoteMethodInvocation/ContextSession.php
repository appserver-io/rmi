<?php

/**
 * AppserverIo\RemoteMethodInvocation\RemoteMethod
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @category  Library
 * @package   RemoteMethodInvocation
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2014 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/rmi
 * @link      http://www.appserver.io
 */

namespace AppserverIo\RemoteMethodInvocation;

/**
 * The interface for the remote connection.
 *
 * @category  Library
 * @package   RemoteMethodInvocation
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2014 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/rmi
 * @link      http://www.appserver.io
 */
class ContextSession implements Session
{

    /**
     * The connection instance.
     *
     * @var \AppserverIo\PersistenceContainerClient\Connection
     */
    protected $connection = null;

    /**
     * The session ID used for the connection.
     *
     * @var string
     */
    protected $sessionId = null;

    /**
     * Initializes the session with the connection.
     *
     * @param \AppserverIo\PersistenceContainerClient\Connection $connection The connection for the session
     */
    public function __construct(Connection $connection)
    {
        // initialize the connection
        $this->connection = $connection;
        // check if already a session id exists in the session
        if (($this->sessionId = session_id()) == null) {
            // if not, create a unique ID
            $this->sessionId = uniqid();
        }
    }

    /**
     * Returns the ID of the session to use.
     *
     * @return string The session ID
     * @see \AppserverIo\RemoteMethodInvocation\Session::getSessionId()
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * The session ID to use.
     *
     * @param string $sessionId The session ID to use
     *
     * @return void
     */
    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;
    }

    /**
     * Invokes the remote method over the connection.
     *
     * @param \AppserverIo\RemoteMethodInvocation\RemoteMethod $remoteMethod The remote method call to invoke
     *
     * @return mixed the method return value
     * @see AppserverIo\RemoteMethodInvocation\Session::send()
     * @todo Refactor to replace check for 'setSession' method, e. g. check for an interface
     */
    public function send(RemoteMethod $remoteMethod)
    {

        // invoke the remote method on the connection
        $response = $this->connection->send($remoteMethod);

        // check if a proxy has been returned
        if (method_exists($response, 'setSession')) {
            $response->setSession($this);
        }

        // return the response
        return $response;
    }

    /**
     * Creates a remote inital context instance.
     *
     * @return \AppserverIo\RemoteMethodInvocation\RemoteObject The proxy for the inital context
     * @see \AppserverIo\RemoteMethodInvocation\Session::createInitialContext()
     */
    public function createInitialContext()
    {
        $initialContext = new InitialContextProxy();
        $initialContext->setSession($this);
        return $initialContext;
    }
}
