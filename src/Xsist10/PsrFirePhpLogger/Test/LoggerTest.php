<?php

namespace Xsist10\PsrFirePhpLogger\Test;

use Psr\Log\Test\LoggerInterfaceTest;
use Psr\Log\LogLevel;
use Xsist10\PsrFirePhpLogger\Logger;

/**
* Provides a base test class for ensuring compliance with the LoggerInterface
*
* Implementors can extend the class and implement abstract methods to run this as part of their test suite
*/
class LoggerTest extends LoggerInterfaceTest 
{
    private $logger = null;

    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        $this->logger = new Logger();
        return $this->logger;
    }

    /**
     * This must return the log messages in order with a simple formatting: "<LOG LEVEL> <MESSAGE>"
     *
     * Example ->error('Foo') would yield "error Foo"
     *
     * @return string[]
     */
    public function getLogs()
    {
        return $this->logger->getLogs();
    }
}