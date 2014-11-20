<?php

/**
 * AppserverIo\Psr\PersistenceContainerProtocol\Session
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
 * The interface for the session.
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
interface Session
{

    /**
     * Returns the ID of the session to use.
     *
     * @return string The session ID
     */
    public function getSessionId();

    /**
     * Invokes the remote method over the connection.
     *
     * @param \AppserverIo\Psr\PersistenceContainerProtocol\RemoteMethod $remoteMethod The remote method call to invoke
     *
     * @return mixed the method return value
     */
    public function send(RemoteMethod $remoteMethod);

    /**
     * Creates a remote inital context instance.
     *
     * @return \AppserverIo\Psr\PersistenceContainerProtocol\RemoteObject The proxy for the inital context
     */
    public function createInitialContext();
}
