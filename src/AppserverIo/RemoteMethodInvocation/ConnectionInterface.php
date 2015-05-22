<?php

/**
 * AppserverIo\RemoteMethodInvocation\ConnectionInterface
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

/**
 * The interface for the remote connection.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/rmi
 * @link      http://www.appserver.io
 */
interface ConnectionInterface
{

    /**
     * Returns the application instance.
     *
     * @return \AppserverIo\Psr\Application\ApplicationInterface|null The application instance
     */
    public function getApplication();

    /**
     * Sends the remote method call to the container instance.
     *
     * @param \AppserverIo\RemoteMethodInvocation\RemoteMethodInterface $remoteMethod The remote method to invoke
     *
     * @return mixed The response from the container
     */
    public function send(RemoteMethodInterface $remoteMethod);

    /**
     * Initializes a new session instance.
     *
     * @return \AppserverIo\RemoteMethodInvocation\SessionInterface The session instance
     */
    public function createContextSession();
}
