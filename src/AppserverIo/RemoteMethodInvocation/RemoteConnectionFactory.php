<?php

/**
 * AppserverIo\RemoteMethodInvocation\RemoteConnectionFactory
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

use AppserverIo\Collections\ArrayList;

/**
 * Connection factory to create a new remote context connection.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/rmi
 * @link      http://www.appserver.io
 */
class RemoteConnectionFactory
{

    /**
     * Private constructor to use class only in static context.
     */
    private function __construct()
    {
    }

    /**
     * Simple factory to create a new context connection
     * of the requested type.
     *
     * @return \AppserverIo\RemoteMethodInvocation\ConnectionInterface The requested context connection
     */
    public static function createContextConnection()
    {

        // initialize the remote method call parser and the session storage
        $sessions = new ArrayList();
        $parser = new RemoteMethodCallParser();

        // initialize the remote context connection
        $contextConnection = new RemoteContextConnection();
        $contextConnection->injectParser($parser);
        $contextConnection->injectSessions($sessions);

        // return the initialized connection
        return $contextConnection;
    }
}
