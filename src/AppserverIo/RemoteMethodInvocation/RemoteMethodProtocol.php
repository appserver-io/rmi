<?php

/**
 * AppserverIo\RemoteMethodInvocation\RemoteMethodProtocol
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
 * This is a parser for a native persistence container remote method call.
 *
 * A Remote method call must have the following format:
 *
 * <METHOD> <CONTENT-LENGTH> <PROTOCOL>/<VERSION>\r\n
 * <CONTENT>\r\n
 *
 * for example:
 *
 * INVOKE 12 RMC/1.0\r\n
 * czoxOiIxIjs=\r\n
 *
 * @category  Library
 * @package   Rmi
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2014 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/rmi
 * @link      http://www.appserver.io
 */
class RemoteMethodProtocol
{

    /**
     * This is the line ending we use.
     *
     * @var string
     */
    const EOL = "\r\n";

    /**
     * Protocol identifier.
     *
     * @var string
     */
    const PROTOCOL = 'RMC';

    /**
     * Protocol version.
     *
     * @var string
     */
    const VERSION = '1.0';

    /**
     * Protocol method that signals a method remote method call.
     *
     * @var string
     */
    const REMOTE_METHOD_INVOKE = 'INVOKE';

    /**
     * Protocl method that signals the result of a remote method call.
     *
     * @var string
     */
    const REMOTE_METHOD_RESULT = 'RESULT';

    /**
     * Prepares the header line for a remote method invokation request.
     *
     * @param string $string The packed remote method instance
     *
     * @return string The remote method invokation header for the passed remote method instance
     */
    public static function prepareHeaderInvoke($string)
    {
        return RemoteMethodProtocol::prepareHeader(RemoteMethodProtocol::REMOTE_METHOD_INVOKE, $string);
    }

    /**
     * Prepares the header line for a remote method result request.
     *
     * @param string $string The packed remote method instance
     *
     * @return string The remote method result header for the passed remote method instance
     */
    public static function prepareHeaderResult($string)
    {
        return RemoteMethodProtocol::prepareHeader(RemoteMethodProtocol::REMOTE_METHOD_RESULT, $string);
    }

    /**
     * Prepares the header line for the passed remote method.
     *
     * @param string $method The remote method to prepare the heaed for
     * @param string $string The packed remote method instance
     *
     * @return string The remote method header for the passed method
     */
    protected static function prepareHeader($method, $string)
    {
        // prepare the header elements
        $protocol = RemoteMethodProtocol::PROTOCOL;
        $version = RemoteMethodProtocol::VERSION;
        $contentLength = strlen($string);

        // concatenate the header string
        return "$method $contentLength $protocol/$version" . RemoteMethodProtocol::EOL;
    }

    /**
     * Packs the passed instance.
     *
     * @param object $instance The instance to be packed
     *
     * @return string The packed instance
     */
    public static function pack($instance)
    {
        return base64_encode(serialize($instance));
    }

    /**
     * Unpacks the passed instance.
     *
     * @param string $string The packed object instance.
     *
     * @return object The unpacke object instance
     */
    public static function unpack($string)
    {
        return unserialize(base64_decode($string));
    }
}
