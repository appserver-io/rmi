<?php

/**
 * AppserverIo\RemoteMethodInvocation\Connection
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
 * @package   Rmi
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2014 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/rmi
 * @link      http://www.appserver.io
 */
interface Connection
{

    /**
     * Sends the remote method call to the container instance.
     *
     * @param \AppserverIo\RemoteMethodInvocation\RemoteMethod $remoteMethod The remote method to invoke
     *
     * @return mixed The response from the container
     */
    public function send(RemoteMethod $remoteMethod);

    /**
     * Initializes a new session instance.
     *
     * @return \AppserverIo\RemoteMethodInvocation\Session The session instance
     */
    public function createContextSession();
}
