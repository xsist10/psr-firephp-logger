<?php
/**
 * FirePHP PSR-3 Logger System
 *
 * @package xsist10/psr-firephp-logger
 * @license Apache 2.0
 * @author  Thomas Shone <xsist10@gmail.com>
 */

namespace Xsist10\PsrFirePhpLogger;

use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;
use Psr\Log\InvalidArgumentException;
use \FirePHP as FirePHP;

/**
 * FirePHP PSR-3 Logger
 *
 * @package xsist10/psr-firephp-logger
 * @license Apache 2.0
 * @author  Thomas Shone <xsist10@gmail.com>
 */
class Logger extends AbstractLogger
{
	protected $fireBug = null;
    protected $logs    = array();

    
    /**
     * Convert the message and context into a string
     *
     * @param string $pattern The message pattern
     * @param array  $context The message context
     *
     * @return string
     */
    private function formatMessage($raw, array $context = array())
    {
        $values = array();
        foreach ($context as $key => $value)
        {
            $values["{{$key}}"] = $this->convertToString($value);
        }

        $pattern = $this->convertToString($raw);
        $message = strtr($pattern, $values);
        return $message;
    }

    /**
     * Convert a mixed variable into a string
     *
     * @param mixed $value The value to convert
     *
     * @return string
     */
    private function convertToString($value)
    {
        if (is_scalar($value))
        {
            $string = (string)$value;
        }
        else if (is_null($value))
        {
            $string = "NULL";
        }
        else if (is_object($value))
        {
            if (is_callable(array($value, "__toString")))
            {
                $string = (string)$value;
            }
            else
            {
                $string = get_class($value);
            }
        }
        else
        {
            $string = gettype($value);
        }

        return $string;
    }

	/**
     * Logs with an arbitrary level.
     *
     * @param  mixed $level
     * @param  string $message
     * @param  array $context
     *
     * @return null
     */
    public function log($level, $message, array $context = array())
    {
    	if ($this->fireBug == null)
    	{
    		$this->fireBug = FirePHP::getInstance(true);
    	}

        $message = $this->formatMessage($message, $context);


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
