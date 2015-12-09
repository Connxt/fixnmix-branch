<?php
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class File_Logger {
	private $logger;

	function __construct($logger_name) {
		$this->logger = new Logger($logger_name);
		$this->logger->pushHandler(new StreamHandler(__DIR__ . '\data.log', Logger::WARNING));
	}

	function add_warning($message) {
		$this->logger->addWarning($message);
	}
}
