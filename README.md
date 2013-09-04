PsrFirePhpLogger
=======

The [PSR-3 Compliant Logger Interface](http://www.php-fig.org/psr/3/) for FirePHP.


You will need to install [FireBug](https://getfirebug.com/) and [FirePHP](http://www.firephp.org/) on your browser first.


Usage
-----
You can use the logger directly like this:

````<?php

use Xsist10\PsrFirePhpLogger\Logger;

$logger = new Logger();

$logger->emergency('emergency');
$logger->alert('alert');
$logger->critical('critical');
$logger->error('error');
$logger->warning('warning');
$logger->notice('notice');
$logger->info('info');
$logger->debug('debug');
````

Or pass the logger as a parameter to `LoggerAwareInterface` classes like this:

````<?php

use Xsist10\PsrFirePhpLogger\Logger;

$logger = new Logger()

class MyClass implements LoggerAwareInterface
{
	// ...
}

$my_class = new MyClass();
$my_class->setLogger($logger);

````