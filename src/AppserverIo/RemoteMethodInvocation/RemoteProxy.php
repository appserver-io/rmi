<?php

/**
 * \AppserverIo\RemoteMethodInvocation\RemoteProxy
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

/**
 * The proxy is used to create a new remote object of the
 * class with the requested name.
 *
 * namespace AppserverIo\Example;
 *
 * use AppserverIo\RemoteMethodInvocation\RemoteConnectionFactory;
 *
 * $connection = RemoteConnectionFactory::createContextConnection();
 * $session = $connection->createContextSession();
 * $initialContext = $session->createInitialContext();
 *
 * $processor = $initialContext->lookup('Some\ProxyClass');
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/rmi
 * @link      http://www.appserver.io
 */
class RemoteProxy implements RemoteObjectInterface
{

    /**
     * Holds the ContextSession for this proxy.
     *
     * @var \AppserverIo\RemoteMethodInvocation\SessionInterface
     */
    protected $session = null;

    /**
     * The class name to proxy.
     *
     * @var string $className
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
     * @see \AppserverIo\RemoteMethodInvocation\RemoteObjectInterface::__getClassName()
     */
    public function __getClassName()
    {
        return $this->className;
    }

    /**
     * Sets the session with the connection instance.
     *
     * @param \AppserverIo\RemoteMethodInvocation\SessionInterface $session The session instance to use
     *
     * @return \AppserverIo\RemoteMethodInvocation\RemoteObjectInterface The instance itself
     */
    public function __setSession(SessionInterface $session)
    {
        $this->session = $session;
        return $this;
    }

    /**
     * Returns the session instance.
     *
     * @return \AppserverIo\RemoteMethodInvocation\SessionInterface The session instance
     * @see \AppserverIo\RemoteMethodInvocation\RemoteObjectInterface::__getSession()
     */
    public function __getSession()
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
        $methodCall = new RemoteMethodCall($this->__getClassName(), $method, $this->__getSession()->getSessionId());
        foreach ($params as $key => $value) {
            $methodCall->addParameter($key, $value);
        }
        return $this->__invoke($methodCall, $this->__getSession());
    }

    /**
     * Invokes the remote execution of the passed remote method.
     *
     * @param \AppserverIo\RemoteMethodInvocation\RemoteMethodInterface $methodCall The remote method call instance
     * @param \AppserverIo\RemoteMethodInvocation\SessionInterface      $session    The session with the connection instance to use
     *
     * @return mixed The result of the remote method call
     */
    public function __invoke(RemoteMethodInterface $methodCall, SessionInterface $session)
    {
        return $this->__setSession($session)->__getSession()->send($methodCall);
    }

    /**
     * Factory method to create a new instance of the requested proxy implementation.
     *
     * @param string $className The name of the class to create the proxy for
     *
     * @return \AppserverIo\RemoteMethodInvocation\RemoteObjectInterface The proxy instance
     */
    public static function __create($className)
    {
        return new RemoteProxy($className);
    }
}
