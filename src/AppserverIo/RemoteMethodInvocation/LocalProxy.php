<?php

/**
 * \AppserverIo\RemoteMethodInvocation\LocalProxy
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

use AppserverIo\Psr\EnterpriseBeans\BeanContextInterface;
use AppserverIo\Psr\EnterpriseBeans\Description\SessionBeanDescriptorInterface;
use AppserverIo\Psr\EnterpriseBeans\Description\StatefulSessionBeanDescriptorInterface;

/**
 * Local proxy implementation.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/rmi
 * @link      http://www.appserver.io
 */
class LocalProxy implements RemoteObjectInterface
{

    /**
     * The bean manager instance.
     *
     * @var \AppserverIo\Psr\EnterpriseBeans\BeanContextInterface
     */
    protected $beanManager;

    /**
     * The session bean descriptor.
     *
     * @var \AppserverIo\Psr\EnterpriseBeans\Description\SessionBeanDescriptorInterface
     */
    protected $sessionBeanDescriptor;

    /**
     * The session been that has to be proxied.
     *
     * @var object
     */
    protected $sessionBean;

    /**
     * The actual session ID.
     *
     * @var string
     */
    protected $sessionId;

    /**
     * Initialize the local proxy.
     *
     * @param \AppserverIo\Psr\EnterpriseBeans\BeanContextInterface                       $beanManager           The bean manager instance
     * @param \AppserverIo\Psr\EnterpriseBeans\Description\SessionBeanDescriptorInterface $sessionBeanDescriptor The session bean descriptor
     * @param object                                                                      $sessionBean           The session been that has to be proxied
     * @param string                                                                      $sessionId             The actual session ID
     */
    public function __construct(
        BeanContextInterface $beanManager,
        SessionBeanDescriptorInterface $sessionBeanDescriptor,
        $sessionBean,
        $sessionId
    ) {
        $this->beanManager = $beanManager;
        $this->sessionBeanDescriptor = $sessionBeanDescriptor;
        $this->sessionBean = $sessionBean;
        $this->sessionId = $sessionId;
    }

    /**
     * Sets the session with the connection instance.
     *
     * @param \AppserverIo\RemoteMethodInvocation\SessionInterface $session The session instance to use
     *
     * @return \AppserverIo\RemoteMethodInvocation\RemoteObjectInterface The instance itself
     */
    public function __setSession(SessionInterface $session)
    {
        throw new \Exception(sprintf('Method %s must NOT be invoked on a local proxy', __METHOD__));
    }

    /**
     * Returns the session instance.
     *
     * @return \AppserverIo\RemoteMethodInvocation\SessionInterface The session instance
     */
    public function __getSession()
    {
        throw new \Exception(sprintf('Method %s must NOT be invoked on a local proxy', __METHOD__));
    }

    /**
     * The name of the original object.
     *
     * @return string The name of the original object
     */
    public function __getClassName()
    {
        return get_class($this->sessionBean);
    }

    /**
     * Invokes the local execution of the passed remote method.
     *
     * @param string $method The local method to call
     * @param array  $params The parameters for the method call
     *
     * @return mixed The result of the local method call
     */
    public function __call($method, $params)
    {

        // invoke the method on the session bean
        $response = call_user_func_array(array($this->sessionBean, $method), $params);

        // initialize the flag to mark the instance to be re-attached
        $attach = true;

        // query if we've stateful session bean
        if ($this->sessionBeanDescriptor instanceof StatefulSessionBeanDescriptorInterface) {
            // remove the SFSB instance if a remove method has been called
            if ($this->sessionBeanDescriptor->isRemoveMethod($methodName)) {
                $this->beanManager->removeStatefulSessionBean($sessionId, $this->sessionBeanDescriptor->getClassName());
                $attach = false;
            }
        }

        // re-attach the bean instance if necessary
        if ($attach === true && $this->sessionId) {
            $this->beanManager->attach($this->sessionBean, $this->sessionId);
        }

        // return the remote method call result
        return $response;
    }
}
