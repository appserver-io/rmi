<?php

/**
 * AppserverIo\RemoteMethodInvocation\LocalContextConnectionTest
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
 * Test implementation for the LocalContextConnectionTest class.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/rmi
 * @link      http://www.appserver.io
 */
class LocalContextConnectionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * The local context connection instance we want to test.
     *
     * @var \AppserverIo\RemoteMethodInvocation\LocalContextConnectionTest
     */
    protected $contextConnection;

    /**
     * Initialize the remote method call instance to test.
     *
     * @return void
     */
    public function setUp()
    {
        $this->contextConnection = new LocalContextConnection();
    }

    /**
     * Tests the setter/getter for the application instance.
     *
     * @return void
     */
    public function testSetGetApplication()
    {

        // create a application mock
        $mockApplication = $this->getMockBuilder($interface = 'AppserverIo\Psr\Application\ApplicationInterface')
                                ->setMethods(get_class_methods($interface))
                                ->getMock();

        // test setter/getter for the application
        $this->contextConnection->injectApplication($mockApplication);
        $this->assertSame($mockApplication, $this->contextConnection->getApplication());
    }

    /**
     * Tests the setter/getter for the session collection.
     *
     * @return void
     */
    public function testSetGetSessions()
    {

        // create a mock collection
        $mockCollection = $this->getMockBuilder($interface = 'AppserverIo\Collections\CollectionInterface')
                               ->setMethods(get_class_methods($interface))
                               ->getMock();

        // test setter/getter for the session collection
        $this->contextConnection->injectSessions($mockCollection);
        $this->assertSame($mockCollection, $this->contextConnection->getSessions());
    }

    /**
     * Test the context session creation.
     */
    public function testCreateContextSesion()
    {

    }
}
