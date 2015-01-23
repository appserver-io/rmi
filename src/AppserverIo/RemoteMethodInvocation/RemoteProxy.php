<?php

/**
 * AppserverIo\PersistenceContainerClient\RemoteProxy
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
 * The proxy is used to create a new remote object of the
 * class with the requested name.
 *
 * namespace AppserverIo\Example;
 *
 * use AppserverIo\RemoteMethodInvokation\RemoteConnectionFactory;
 *
 * $connection = RemoteConnectionFactory::createContextConnection();
 * $session = $connection->createContextSession();
 * $initialContext = $session->createInitialContext();
 *
 * $processor = $initialContext->lookup('Some\ProxyClass');
 *
 * @category  Library
 * @package   RemoteMethodInvocation
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2014 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/rmi
 * @link      http://www.appserver.io
 */
class RemoteProxy implements RemoteObject
{

    /**
     * Holds the ContextSession for this proxy.
     *
     * @var \AppserverIo\RemoteMethodInvocation\Session
     */
    protected $session = null;

    /**
     * The class name to proxy.
     *
     * @var string
     */
    protected $className = null;

    /**
     * Initializes the proxy with the class name to proxy.
     *
     * @param mixed $className The name of the class to create the proxy for
     */
    public function __construct($className = 'AppserverIo\Appserver\Core\InitialContext')
    {
        $this->className = $className;
    }

    /**
     * The name of the original object.
     *
     * @return string The name of the original object
     * @see \AppserverIo\RemoteMethodInvocation\RemoteObject::getClassName()
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * Sets the session with the connection instance.
     *
     * @param \AppserverIo\RemoteMethodInvocation\Session $session The session instance to use
     *
     * @return \AppserverIo\RemoteMethodInvocation\RemoteObject The instance itself
     */
    public function setSession(Session $session)
    {
        $this->session = $session;
        return $this;
    }

    /**
     * Returns the session instance.
     *
     * @return \AppserverIo\RemoteMethodInvocation\Session The session instance
     * @see \AppserverIo\RemoteMethodInvocation\RemoteObject::getSession()
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * Invokes the remote execution of the passed remote method.
     *
     * @param string $method The remote method to call
     * @param array  $params The parameters for the method call
     *
     * @return mixed The result of the remote method call
     */
    public function __call($method, $params)
    {
        $methodCall = new RemoteMethodCall($this->getClassName(), $method, $this->getSession()->getSessionId());
        foreach ($params as $key => $value) {
            $methodCall->addParameter($key, $value);
        }
        return $this->__invoke($methodCall, $this->getSession());
    }

    /**
     * Invokes the remote execution of the passed remote method.
     *
     * @param \AppserverIo\RemoteMethodInvocation\RemoteMethod $methodCall The remote method call instance
     * @param \AppserverIo\RemoteMethodInvocation\Session      $session    The session with the connection instance to use
     *
     * @return mixed The result of the remote method call
     */
    public function __invoke(RemoteMethod $methodCall, Session $session)
    {
        return $this->setSession($session)->getSession()->send($methodCall);
    }

    /**
     * Factory method to create a new instance of the requested proxy implementation.
     *
     * @param string $className The name of the class to create the proxy for
     *
     * @return \AppserverIo\RemoteMethodInvocation\RemoteObject The proxy instance
     */
    public static function create($className)
    {
        return new RemoteProxy($className);
    }
}
