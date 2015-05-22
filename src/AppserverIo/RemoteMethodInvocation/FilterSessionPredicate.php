<?php

/**
 * \AppserverIo\RemoteMethodInvocation\FilterSessionPredicate
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
* @author    Bernhard Wick <bw@appserver.io>
* @copyright 2015 TechDivision GmbH <info@appserver.io>
* @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
* @link      https://github.com/appserver-io/rmi
* @link      http://www.appserver.io
*/

namespace AppserverIo\RemoteMethodInvocation;

use AppserverIo\Collections\PredicateInterface;

/**
 * Predicate to filter a session by it's ID from an ArrayList.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/rmi
 * @link      http://www.appserver.io
 */
class FilterSessionPredicate implements PredicateInterface
{

    /**
     * The session-ID we want to filter for.
     *
     * @var string
     */
    protected $sessionId;

    /**
     * Initializes the predicate with the session-ID we want to filter.
     *
     * @param string $sessionId The session-ID we want to filter
     */
    public function __construct($sessionId)
    {
        $this->sessionId = $sessionId;
    }

    /**
     * This method evaluates the object passed as parameter against
     * the anything specified in the evaluate method.
     *
     * @param object $object The object that should be evaluated
     *
     * @return boolean Returns a boolean depending on the implementation of the method
     */
    public function evaluate($object)
    {
        return $this->compare($object);
    }

    /**
     * Compares the session-ID of the passed session with the
     * internal one and returns TRUE if they match.
     *
     * @param \AppserverIo\RemoteMethodInvocation\SessionInterface $session The session to compare the ID with
     *
     * @return boolean TRUE if the IDs are equal, else FALSE
     */
    protected function compare(SessionInterface $session)
    {
        return $session->getSessionId() === $this->sessionId;
    }
}
