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
 * @author    Tim Wagner <tw@appserver.io>
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/rmi
 * @link      http://www.appserver.io
 */

namespace AppserverIo\RemoteMethodInvocation;

use AppserverIo\Collections\HashMap;
use AppserverIo\Collections\ArrayList;

/**
 * The interface for the remote connection.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/rmi
 * @link      http://www.appserver.io
 */
class ContextSession extends HashMap implements SessionInterface
{

    /**
     * The connection instances.
     *
     * @var \AppserverIo\Collections\ArrayList
     */
    protected $connections = null;

    /**
     * The session ID used for the connection.
     *
     * @var string
     */
    protected $sessionId = null;

    /**
     * Initializes the session with the connection.
     *
     * @param \AppserverIo\RemoteMethodInvocation\ConnectionInterface $connection The connection for the session
     */
    public function __construct(ConnectionInterface $connection)
    {

        // initialize the ArrayList for the collections
        $this->connections = new ArrayList();

        // add the passed connection
        $this->addConnection($connection);

        // check if already a session id exists in the session
        if (($this->sessionId = session_id()) == null) {
            // if not, create a unique ID
            $this->sessionId = uniqid();
        }
    }

    /**
     * Add's the passed connection to the session's connection collection.
     *
     * @param \AppserverIo\RemoteMethodInvocation\ConnectionInterface $connection The connection instance to add
     *
     * @return void
     */
    public function addConnection(ConnectionInterface $connection)
    {
        $this->connections->add($connection);
    }

    /**
     * Returns the collection with the session's connections.
     *
     * @return \AppserverIo\Collections\ArrayList The collection with the session's connections
     */
    public function getConnections()
    {
        return $this->connections;
    }

    /**
     * Returns the connection instance.
     *
     * @param integer $key The key of the connection to return
     *
     * @return \AppserverIo\RemoteMethodInvocation\ConnectionInterface The connection instance
     */
    public function getConnection($key = 0)
    {
        return $this->connections->get($key);
    }

    /**
     * Re-Attaches the beans bound to this session to the container.
     *
     * @return void
     */
    public function __destruct()
    {

        // query whether we've beans that has to be re-attached to the container or not
        if ($this->size() > 0) {
            // iterate over all connections to query if the bean has to be re-attached
            foreach ($this->getConnections() as $connection) {
                // query whether we've local context connection or not
                if ($application = $connection->getApplication()) {
                    // load the bean manager instance from the application
                    $beanManager = $application->search('BeanContextInterface');

                    // load the session-ID
                    $sessionId = $this->getSessionId();

                    // attach all beans of this session
                    foreach ($this->items as $instance) {
                        $beanManager->attach($instance, $sessionId);
                    }
                }
            }
        }
    }

    /**
     * Returns the ID of the session to use.
     *
     * @return string The session ID
     * @see \AppserverIo\RemoteMethodInvocation\SessionInterface::getSessionId()
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
     * @param \AppserverIo\RemoteMethodInvocation\RemoteMethodInterface $remoteMethod The remote method call to invoke
     *
     * @return mixed the method return value
     * @see AppserverIo\RemoteMethodInvocation\SessionInterface::send()
     * @todo Refactor to replace check for 'setSession' method, e. g. check for an interface
     */
    public function send(RemoteMethodInterface $remoteMethod)
    {

        // create an array to store connection response temporarily
        $responses = array();

        // iterate over all connections and invoke the remote method call
        foreach ($this->getConnections() as $key => $connection) {
            // invoke the remote method on the connection
            $responses[$key] = $connection->send($remoteMethod);

            // check if a proxy has been returned
            if (method_exists($response, 'setSession')) {
                $responses[$key]->setSession($this);
            }
        }

        // return the response of the first connection
        return reset($responses);
    }

    /**
     * Creates a remote initial context instance.
     *
     * @return \AppserverIo\RemoteMethodInvocation\RemoteObjectInterface The proxy for the initial context
     * @see \AppserverIo\RemoteMethodInvocation\SessionInterface::createInitialContext()
     */
    public function createInitialContext()
    {
        $initialContext = new InitialContextProxy();
        $initialContext->__setSession($this);
        return $initialContext;
    }
}
