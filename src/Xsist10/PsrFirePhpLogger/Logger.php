<?php

namespace Xsist10\PsrFirePhpLogger;

use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;
use Psr\Log\InvalidArgumentException;
use \FirePHP as FirePHP;

class Logger extends AbstractLogger
{
	protected $fireBug = null;
    protected $logs    = array();

    
    /**
     * Fetch the message to log
     *
     * The fetchMessage() method is used internally to interpolate the
     * message $pattern passed to the log() method with the data given
     * in the $context array.
     *
     * @param string $pattern The message pattern
     * @param array $context The message context
     *
     * @return string
     * The interpolated log message is returned on success
     */
    private function fetchMessage($pattern, array $context = array())
    {
        $values = array();

        foreach ($context as $key => $value) {
            $values["{{$key}}"] = $this->fetchString($value);
        }

        $pattern = $this->fetchString($pattern);
        $message = strtr($pattern, $values);
        return $message;
    }

    /**
     * Fetch a values string representation
     *
     * The fetchString() method is used internally to convert the given
     * $value into its string representation.
     *
     * @param mixed $value The value to convert
     *
     * @return string
     * The $value's string representation is returned on success
     */
    private function fetchString($value)
    {
        if (is_scalar($value)) {
            $string = (string) $value;
        }
        else if (is_null($value)) {
            $string = "NULL";
        }
        else if (is_object($value)) {
            $string = $this->fetchStringFromObject($value);
        }
        else {
            $string = gettype($value);
        }

        return $string;
    }

    /**
     * Fetch an objects string representation
     *
     * The fetchStringFromObject() method is used internally to convert
     * the given $object into its string representation.
     *
     * @param object $object The object to convert
     *
     * @return string
     * The $object's string representation is returned on success
     */
    private function fetchStringFromObject($object)
    {
        if (is_callable(array($object, "__toString"))) {
            $string = (string) $object;
        }
        else {
            $string = get_class($object);
        }

        return $string;
    }

	/**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @return null
     */
    public function log($level, $message, array $context = array())
    {
    	if ($this->fireBug == null)
    	{
    		$this->fireBug = FirePHP::getInstance(true);
    	}

        $message = $this->fetchMessage($message, $context);


    	switch ($level)
    	{
    		case LogLevel::EMERGENCY:
    		case LogLevel::ALERT:
		    case LogLevel::CRITICAL:
		    case LogLevel::ERROR:
		    	$method = 'error';

                break;

		    case LogLevel::WARNING:
		    	$method = 'warn';
                break;

		    case LogLevel::NOTICE:
		    case LogLevel::INFO:
                $method = 'info';
                break;

		    case LogLevel::DEBUG:
                $method = 'log';
                break;

            default:
                throw new InvalidArgumentException('Invalid Log Level Specified.');
    	}

        $this->logs[] = $level . ' ' . $message;
        if (headers_sent())
        {
            return false;
        }
        return $this->fireBug->$method($message);
    }

    public function getLogs()
    {
        return $this->logs;
    }
}
