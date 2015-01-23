<?php

/**
 * AppserverIo\RemoteMethodInvocation\RemoteContextConnectionTest
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
 * Test for the context connection instance.
 *
 * @category  Library
 * @package   RemoteMethodInvocation
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2014 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/rmi
 * @link      http://www.appserver.io
 */
class RemoteContextConnectionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * The application name for testing purposes.
     *
     * @var string
     */
    const APP_NAME = 'testApp';

    /**
     * An IP address for testing purposes.
     *
     * @var string
     */
    const ADDRESS = '127.0.0.1';

    /**
     * A port number for testing purposes.
     *
     * @var integer
     */
    const PORT = 1234;

    /**
     * The instance we want to test.
     *
     * @var \AppserverIo\RemoteMethodInvocation\RemoteContextConnection
     */
    protected $contextConnection;

    /**
     * Initialize the context connection instance we want to test.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->contextConnection = new RemoteContextConnection();
    }

    /**
     * Tests the constructor of the context connection.
     *
     * @return void
     */
    public function testSetterAndGetterForAppName()
    {
        $this->contextConnection->injectAppName(RemoteContextConnectionTest::APP_NAME);
        $this->assertEquals(RemoteContextConnectionTest::APP_NAME, $this->contextConnection->getAppName());
    }

    /**
     * Tests the setter/getter for the IP address.
     *
     * @return void
     */
    public function testSetterAndGetterForAddress()
    {
        $this->contextConnection->injectAddress(RemoteContextConnectionTest::ADDRESS);
        $this->assertSame(RemoteContextConnectionTest::ADDRESS, $this->contextConnection->getAddress());
    }

    /**
     * Tests the setter/getter for the port.
     *
     * @return void
     */
    public function testSetterAndGetterForPort()
    {
        $this->contextConnection->injectPort(RemoteContextConnectionTest::PORT);
        $this->assertSame(RemoteContextConnectionTest::PORT, $this->contextConnection->getPort());
    }
}
