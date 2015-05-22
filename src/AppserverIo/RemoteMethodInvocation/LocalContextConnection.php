<?php

/**
 * AppserverIo\PersistenceContainerClient\Context\LocalContextConnection
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

use AppserverIo\Collections\CollectionInterface;
use AppserverIo\Psr\Application\ApplicationInterface;
use AppserverIo\Collections\CollectionUtils;

/**
 * Connection implementation to invoke a local method call.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/rmi
 * @link      http://www.appserver.io
 */
class LocalContextConnection implements ConnectionInterface
{

    /**
     * The storage for the sessions.
     *
     * @var \AppserverIo\Collections\CollectionInterface
     */
    protected $sessions = null;

    /**
     * The application instance.
     *
     * @var \AppserverIo\Psr\Application\ApplicationInterface
     */
    protected $application;

    /**
     * The bean manager instance to load the local bean instance with.
     *
     * @var \AppserverIo\Psr\EnterpriseBeans\BeanContextInterface
     */
    protected $beanManager;

    /**
     * The local bean instance we're the proxy for.
     *
     * @var object
     */
    protected $instance;

    /**
     * Injects the application instance for the local connection.
     *
     * @param \AppserverIo\Psr\Application\ApplicationInterface $application The application instance
     *
     * @return void
     */
    public function injectApplication(ApplicationInterface $application)
    {
        $this->application = $application;
    }

    /**
     * Returns the application instance.
     *
     * @return \AppserverIo\Psr\Application\ApplicationInterface The application instance
     */
    public function getApplication()
    {
        return $this->application;
    }

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
     * Sends the remote method call to the container instance.
     *
     * @param \AppserverIo\RemoteMethodInvocation\RemoteMethodInterface $remoteMethod The remote method instance
     *
     * @return mixed The response from the container
     * @see AppserverIo\RemoteMethodInvocation\ConnectionInterface::send()
     */
    public function send(RemoteMethodInterface $remoteMethod)
    {

        // prepare method name and parameters and invoke method
        $className = $remoteMethod->getClassName();
        $methodName = $remoteMethod->getMethodName();
        $parameters = $remoteMethod->getParameters();
        $sessionId = $remoteMethod->getSessionId();

        // try to load the session with the ID passed in the remote method
        $session = CollectionUtils::find($this->getSessions(), new FilterSessionPredicate($sessionId));

        // query whether the session is available or not
        if ($session == null) {
            throw new \Exception(sprintf('Can\'t find session with ID %s', $sessionId));
        }

        // query whether we already have an instance in the session container
        if ($instance = $session->exists($className) === false) {
            // load the application context and the bean manager
            $application = $this->getApplication();

            // load the bean instance
            $instance = $application->search($className, array($sessionId, array($application)));

            // load local bean instance from the application
            $session->add($className, $instance);

        } else {
            // load the instance from the session container
            $instance = $session->get($className);
        }

        // invoke the remote method call on the local instance
        return call_user_func_array(array($instance, $methodName), $parameters);
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
}
