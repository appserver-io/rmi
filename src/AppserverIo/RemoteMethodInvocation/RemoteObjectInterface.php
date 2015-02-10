<?php

/**
 * \AppserverIo\RemoteMethodInvocation\RemoteObjectInterface
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
 * Interface for all remote objects.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/rmi
 * @link      http://www.appserver.io
 */
interface RemoteObjectInterface
{

    /**
     * Returns the session instance.
     *
     * @return \AppserverIo\RemoteMethodInvocation\SessionInterface The session instance
     */
    public function __getSession();

    /**
     * The name of the original object.
     *
     * @return string The name of the original object
     */
    public function __getClassName();

    /**
     * Sets the session with the connection instance.
     *
     * @param \AppserverIo\RemoteMethodInvocation\SessionInterface $session The session instance to use
     *
     * @return \AppserverIo\RemoteMethodInvocation\RemoteObjectInterface The instance itself
     */
    public function __setSession(SessionInterface $session);
}
