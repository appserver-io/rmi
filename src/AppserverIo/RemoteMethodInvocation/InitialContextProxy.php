<?php

/**
 * AppserverIo\PersistenceContainerClient\InitialContext
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
 * Proxy for the container instance itself.
 *
 * @category  Library
 * @package   RemoteMethodInvocation
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2014 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/rmi
 * @link      http://www.appserver.io
 */
class InitialContextProxy extends RemoteProxy
{

    /**
     * Runs a lookup on the container for the class with the
     * passed name.
     *
     * @param string $className The class name to run the lookup for
     *
     * @return \AppserverIo\RemoteMethodInvocation\RemoteObject The instance
     */
    public function lookup($className)
    {
        return RemoteProxy::create($className)->setSession($this->getSession());
    }
}
