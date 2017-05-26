<?php

/**
 * AppserverIo\RemoteMethodInvocation\LocalProxyTest
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

/**
 * Test implementation for the LocalProxy class.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/rmi
 * @link      http://www.appserver.io
 */
class LocalProxyTest extends \PHPUnit_Framework_TestCase
{

    /**
     * The local proxy instance we want to test.
     *
     * @var \AppserverIo\RemoteMethodInvocation\LocalProxy
     */
    protected $localProxy;

    /**
     * A dummy session ID.
     *
     * @var string
     */
    protected $sessionId = '2HioZExmdZxZvT1q1kwjhkpSPrmWjXV3';

    /**
     * Initialize the remote method call instance to test.
     *
     * @return void
     */
    public function setUp()
    {

        // initialize a mock bean manager instance
        $mockBeanManager = $this->getMockBuilder(BeanContextInterface::class)
                                ->setMethods(get_class_methods(BeanContextInterface::class))
                                ->getMock();

        // initialize a mock session bean descriptor instance
        $mockSessionBeanDescriptor = $this->getMockBuilder(SessionBeanDescriptorInterface::class)
                                          ->setMethods(get_class_methods(SessionBeanDescriptorInterface::class))
                                          ->getMock();

        // initialize the local proxy instance
        $this->localProxy = new LocalProxy(
            $mockBeanManager,
            $mockSessionBeanDescriptor,
            $this,
            $this->sessionId
        );
    }

    /**
     * Test is a class name passed to the constructor will be returned.
     *
     * @return void
     */
    public function testGetClassName()
    {
        $this->assertSame(__CLASS__, $this->localProxy->__getClassName());
    }

    /**
     * Test the magic __call() method invocation.
     *
     * @return void
     */
    public function testCall()
    {
        $this->assertEquals(get_class($this), $this->localProxy->doSomething());
    }

    /**
     * Dummy method that has to be invoked by the __call() method.
     *
     * @return string The actual class name
     */
    public function doSomething()
    {
        return get_class($this);
    }

    /**
     * Invokes the not implemented __setSession() method and catches the exception.
     *
     * @return void
     * @expectedException \Exception
     */
    public function testSetSession()
    {
        $this->localProxy->__setSession($this->getMock(SessionInterface::class));
    }

    /**
     * Invokes the not implemented __getSession() method and catches the exception.
     *
     * @return void
     * @expectedException \Exception
     */
    public function testGetSession()
    {
        $this->localProxy->__getSession();
    }
}
