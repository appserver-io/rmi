<?php

/**
 * AppserverIo\RemoteMethodInvocation\RemoteMethodCallTest
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
 * Test implementation for the RemoteMethodCall class.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/rmi
 * @link      http://www.appserver.io
 */
class RemoteMethodCallTest extends \PHPUnit_Framework_TestCase
{

    /**
     * A method name for testing purposes.
     *
     * @var string METHOD_NAME
     */
    const METHOD_NAME = 'testGetClassName';

    /**
     * The remote method call instance we want to test.
     *
     * @var \AppserverIo\RemoteMethodInvocation\RemoteMethodCall $remoteMethodCall
     */
    protected $remoteMethodCall;

    /**
     * Initialize the remote method call instance to test.
     *
     * @return void
     */
    public function setUp()
    {
        $this->remoteMethodCall = new RemoteMethodCall(__CLASS__, RemoteMethodCallTest::METHOD_NAME);
    }

    /**
     * Test is a class name passed to the constructor will be returned.
     *
     * @return void
     */
    public function testGetClassName()
    {
        $this->assertSame(__CLASS__, $this->remoteMethodCall->getClassName());
    }

    /**
     * Test is a method name passed to the constructor will be returned.
     *
     * @return void
     */
    public function testGetMethodName()
    {
        $this->assertSame(RemoteMethodCallTest::METHOD_NAME, $this->remoteMethodCall->getMethodName());
    }
}
