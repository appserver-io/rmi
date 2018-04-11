<?php

/**
 * AppserverIo\RemoteMethodInvocation\BeanContextWithAttachInterface
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

use AppserverIo\Psr\Deployment\DescriptorInterface;
use AppserverIo\Psr\EnterpriseBeans\BeanContextInterface;

/**
 * Wrapper to test bean context because of missing attach method in default interface.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2016 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/rmi
 * @link      http://www.appserver.io
 */
interface BeanContextWithAttachInterface extends BeanContextInterface
{
    
    /**
     * Attaches the passed bean, depending on it's type to the container.
     *
     * @param \AppserverIo\Psr\Deployment\DescriptorInterface $objectDescriptor The object descriptor for the passed instance
     * @param object                                          $instance         The bean instance to attach
     *
     * @return void
     * @throws \AppserverIo\Psr\EnterpriseBeans\InvalidBeanTypeException Is thrown if a invalid bean type has been detected
     */
    public function attach(DescriptorInterface $objectDescriptor, $instance);
}
