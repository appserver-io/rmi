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

use AppserverIo\Collections\Collection;
use AppserverIo\Psr\Application\ApplicationInterface;

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
     * @var \AppserverIo\Collections\Collection
     */
    protected $sessions = null;

    /**
     * The application instance.
     *
     * @var \AppserverIo\Psr\Application\ApplicationInterface
     */
    protected $application;

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
     * @param \AppserverIo\Collections\Collection $sessions The collection for the sessions
     *
     * @return void
     */
    public function injectSessions(Collection $sessions)
    {
        $this->sessions = $sessions;
    }

    /**
     * Returns the collection with the sessions.
     *
     * @return \AppserverIo\Collections\Collection The collection with the sessions
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

        // load the application context and the bean manager
        $application = $this->getApplication();

        // prepare method name and parameters and invoke method
        $className = $remoteMethod->getClassName();
        $methodName = $remoteMethod->getMethodName();
        $parameters = $remoteMethod->getParameters();
        $sessionId = $remoteMethod->getSessionId();

        // load the bean manager and the bean instance
        $beanManager = $application->search('BeanContext');
        $instance = $application->search($className, array($sessionId, array($application)));

        // invoke the remote method call on the local instance
        $response = call_user_func_array(array($instance, $methodName), $parameters);

        // reattach the bean instance in the container and unlock it
        $beanManager->attach($instance, $sessionId);

        // return the response to the client
        return $response;
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
