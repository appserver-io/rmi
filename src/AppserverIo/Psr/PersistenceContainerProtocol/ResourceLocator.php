<?php

/**
 * AppserverIo\Psr\PersistenceContainerProtocol\ResourceLocator
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @category   Appserver
 * @package    Psr
 * @subpackage PersistenceContainerProtocol
 * @author     Tim Wagner <tw@appserver.io>
 * @copyright  2014 TechDivision GmbH <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       https://github.com/appserver-io-psr/persistencecontainerprotocol
 * @link       http://www.appserver.io
 */

namespace AppserverIo\Psr\PersistenceContainerProtocol;

/**
 * Interface for the resource locator instances.
 *
 * @category   Appserver
 * @package    Psr
 * @subpackage PersistenceContainerProtocol
 * @author     Tim Wagner <tw@appserver.io>
 * @copyright  2014 TechDivision GmbH <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       https://github.com/appserver-io-psr/persistencecontainerprotocol
 * @link       http://www.appserver.io
 */
interface ResourceLocator
{

    /**
     * Tries to locate the bean that handles the request and returns the instance
     * if one can be found.
     *
     * @param \AppserverIo\Psr\PersistenceContainerProtocol\BeanContext  $beanManager  The bean manager instance
     * @param \AppserverIo\Psr\PersistenceContainerProtocol\RemoteMethod $remoteMethod The remote method call
     *
     * @return object The requested bean instance
     */
    public function locate(BeanContext $beanManager, RemoteMethod $remoteMethod);

    /**
     * Run's a lookup for the session bean with the passed class name and
     * session ID.
     *
     * If the passed class name is a session bean an instance
     * will be returned.
     *
     * @param \AppserverIo\Psr\PersistenceContainerProtocol\BeanContext $beanManager The bean manager instance
     * @param string                                                    $className   The name of the session bean's class
     * @param string                                                    $sessionId   The session ID
     * @param array                                                     $args        The arguments passed to the session beans constructor
     *
     * @return object The requested session bean
     * @throws \AppserverIo\Psr\PersistenceContainerProtocol\InvalidBeanTypeException Is thrown if passed class name is no session bean or is a entity bean (not implmented yet)
     */
    public function lookup(BeanContext $beanManager, $className, $sessionId = null, array $args = array());
}
