<?php

/**
 * AppserverIo\RemoteMethodInvocation\BeanContextWithInvokeInterface
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
 * @copyright 2016 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/rmi
 * @link      http://www.appserver.io
 */

namespace AppserverIo\RemoteMethodInvocation;

use AppserverIo\Collections\CollectionInterface;
use AppserverIo\Psr\EnterpriseBeans\BeanContextInterface;

/**
 * Wrapper to test bean context because of missing invoke method in default interface.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2016 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/rmi
 * @link      http://www.appserver.io
 */
interface BeanContextWithInvokeInterface extends BeanContextInterface
{

    /**
     * Invoke the passed remote method on the described session bean and return the result.
     *
     * @param \AppserverIo\RemoteMethodInvocation\RemoteMethodInterface $remoteMethod The remote method description
     * @param \AppserverIo\Collections\CollectionInterface              $sessions     The collection with the sessions
     *
     * @return mixed The result of the remote method invocation
     */
    public function invoke(RemoteMethodInterface $remoteMethod, CollectionInterface $sessions);
}
