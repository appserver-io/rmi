<?php

/**
 * AppserverIo\RemoteMethodInvocation\RemoteExceptionWrapper
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
 * A wrapper to make exceptions serializable.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/rmi
 * @link      http://www.appserver.io
 */
class RemoteExceptionWrapper implements \Serializable
{

    /**
     * The original exception type.
     *
     * @var string
     */
    protected $type = '\Exception';

    /**
     * The exception code.
     *
     * @var integer
     */
    protected $code = 0;

    /**
     * The filename where the exception was created.
     *
     * @var string
     */
    protected $file = '';

    /**
     * The line where the exception was created.
     *
     * @var integer
     */
    protected $line = 0;

    /**
     * The original exception stack trace.
     *
     * @var string
     */
    protected $trace = '';

    /**
     * The exception message.
     *
     * @var string
     */
    protected $message = '';

    /**
     * Initializes the wrapper with the data from the passed exception.
     *
     * @param \Exception $e The exception we want to wrap
     */
    public function __construct(\Exception $e)
    {
        // initialize the wrapper with the exception's data
        $this->type = get_class($e);
        $this->code = $e->getCode();
        $this->file = $e->getFile();
        $this->line = $e->getLine();
        $this->trace = $e->getTraceAsString();
        $this->message = $e->__toString();
    }

    /**
     * Factory method to create a new wrapper instance from the
     * passed exception data.
     *
     * @param \Exception $e The exception to wrap
     *
     * @return \AppserverIo\RemoteMethodInvocation\RemoteExceptionWrapper The wrapper instance
     */
    public function factory(\Exception $e)
    {
        return new RemoteExceptionWrapper($e);
    }

    /**
     * This method has to be called to serialize the String.
     *
     * @return string Returns a serialized version of the String
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(get_object_vars($this));
    }

    /**
     * This method unserializes the passed string and initializes the String
     * itself with the data.
     *
     * @param string $data Holds the data of the instance as serialized string
     *
     * @return void
     * @see \Serializable::unserialize($data)
     */
    public function unserialize($data)
    {
        foreach (unserialize($data) as $propertyName => $propertyValue) {
            $this->$propertyName = $propertyValue;
        }
    }

    /**
     * Returns a new instance of the original exception
     * and it's data.
     *
     * @return \Exception An instance of the original exception
     */
    public function toException()
    {
        return new $this->type($this->message, $this->code);
    }
}
