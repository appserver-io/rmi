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

use AppserverIo\Collections\ArrayList;

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

        // this is dummy interface provide by this library for testing purposes only
        $interface = 'AppserverIo\RemoteMethodInvocation\ApplicationAndNamingDirectoryAwareInterface';

        // create a application mock
        $mockApplication = $this->getMockBuilder($interface)
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
     *
     * @return void
     */
    public function testCreateContextSesion()
    {

        // create a mock collection
        $mockCollection = $this->getMockBuilder($interface = 'AppserverIo\Collections\ArrayList')
            ->setMethods(get_class_methods($interface))
            ->getMock();

        // expects one call on add() method
        $mockCollection->expects($this->once())
                       ->method('add');

        // test setter/getter for the session collection
        $this->contextConnection->injectSessions($mockCollection);

        // create a context session instance
        $this->assertInstanceOf(
            'AppserverIo\RemoteMethodInvocation\SessionInterface',
            $this->contextConnection->createContextSession()
        );
    }

    /**
     * Tests the method invocation on the bean invoked by the send() method
     * and bean instance NOT initialized by a prior call.
     *
     * @return void
     */
    public function testSend()
    {

        // create a mock bean, simulated by an ArrayList
        $mockBean = $this->getMockBuilder($beanInterface = 'AppserverIo\Collections\ArrayList')
                         ->setMethods(get_class_methods($beanInterface))
                         ->getMock();

        // mock the methods
        $mockBean->expects($this->once())
                 ->method('size')
                 ->willReturn(0);

        // create a mock remote method
        $mockRemoteMethod = $this->getMockBuilder($interface = 'AppserverIo\RemoteMethodInvocation\RemoteMethodInterface')
                                 ->setMethods(get_class_methods($interface))
                                 ->getMock();

        // mock the methods
        $mockRemoteMethod->expects($this->once())
                         ->method('getClassName')
                         ->willReturn($beanInterface);
        $mockRemoteMethod->expects($this->once())
                         ->method('getMethodName')
                         ->willReturn('size');
        $mockRemoteMethod->expects($this->once())
                         ->method('getParameters')
                         ->willReturn(array());
        $mockRemoteMethod->expects($this->once())
                         ->method('getSessionId')
                         ->willReturn($sessionId = md5(uniqid()));

        // create a mock session
        $mockSession = $this->getMockBuilder('\AppserverIo\RemoteMethodInvocation\ContextSession')
                            ->setMethods(array('getSessionId', 'exists', 'get'))
                            ->disableOriginalConstructor()
                            ->getMock();

        // mock the methods
        $mockSession->expects($this->once())
                    ->method('getSessionId')
                    ->willReturn($sessionId);
        $mockSession->expects($this->once())
                    ->method('exists')
                    ->willReturn(true);
        $mockSession->expects($this->once())
                    ->method('get')
                    ->willReturn($mockBean);

        // create an ArrayList with the mock sessions
        $sessions = new ArrayList();
        $sessions->add($mockSession);

        // test setter/getter for the session collection
        $this->contextConnection->injectSessions($sessions);

        // invoke the remote method call
        $this->contextConnection->send($mockRemoteMethod);
    }

    /**
     * Tests the method invocation on the bean invoked by the send() method
     * and bean instance ALREADY initialized by a prior call.
     *
     * @return void
     */
    public function testSendWithExistingSession()
    {

        // create a mock bean, simulated by an ArrayList
        $mockBean = $this->getMockBuilder($beanInterface = 'AppserverIo\Collections\ArrayList')
                         ->setMethods(get_class_methods($beanInterface))
                         ->getMock();

        // mock the methods
        $mockBean->expects($this->once())
                 ->method('size')
                 ->willReturn(0);

        // this is dummy interface provide by this library for testing purposes only
        $interface = 'AppserverIo\RemoteMethodInvocation\ApplicationAndNamingDirectoryAwareInterface';

        // create a application mock
        $mockApplication = $this->getMockBuilder($interface)
                                ->setMethods(get_class_methods($interface))
                                ->getMock();

        // mock the search() call
        $mockApplication->expects($this->once())
                        ->method('search')
                        ->willReturnOnConsecutiveCalls($mockBean);

        // test setter/getter for the application
        $this->contextConnection->injectApplication($mockApplication);

        // create a mock remote method
        $mockRemoteMethod = $this->getMockBuilder($interface = 'AppserverIo\RemoteMethodInvocation\RemoteMethodInterface')
                                 ->setMethods(get_class_methods($interface))
                                 ->getMock();

        // mock the methods
        $mockRemoteMethod->expects($this->once())
                         ->method('getClassName')
                         ->willReturn($beanInterface);
        $mockRemoteMethod->expects($this->once())
                         ->method('getMethodName')
                         ->willReturn('size');
        $mockRemoteMethod->expects($this->once())
                         ->method('getParameters')
                         ->willReturn(array());
        $mockRemoteMethod->expects($this->once())
                         ->method('getSessionId')
                         ->willReturn($sessionId = md5(uniqid()));

        // create a mock session
        $mockSession = $this->getMockBuilder('\AppserverIo\RemoteMethodInvocation\ContextSession')
                            ->disableOriginalConstructor()
                            ->setMethods(array('getSessionId', 'exists', 'get', '__destruct'))
                            ->getMock();

        // mock the methods
        $mockSession->expects($this->exactly(2))
                    ->method('getSessionId')
                    ->willReturn($sessionId);
        $mockSession->expects($this->once())
                    ->method('exists')
                    ->willReturn(false);

        // create an ArrayList with the mock sessions
        $sessions = new ArrayList();
        $sessions->add($mockSession);

        // test setter/getter for the session collection
        $this->contextConnection->injectSessions($sessions);

        // invoke the remote method call
        $this->contextConnection->send($mockRemoteMethod);
    }
}
