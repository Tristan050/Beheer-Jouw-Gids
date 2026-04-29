<?php

class Logger
{
    private static ?Logger $instance = null;
    private string $logDir;
    private string $logFile;

    private function __construct()
    {
        $this->logDir = __DIR__ . '/../logs';
        $this->logFile = $this->logDir . '/app.log';

        if (!is_dir($this->logDir)) {
            mkdir($this->logDir, 0755, true);
        }
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function error(string $message, ?array $context = null): void
    {
        $this->log('ERROR', $message, $context);
    }

    public function warning(string $message, ?array $context = null): void
    {
        $this->log('WARNING', $message, $context);
    }

    public function info(string $message, ?array $context = null): void
    {
        $this->log('INFO', $message, $context);
    }
    /**
     * Logs a debug message if debugging is enabled.
     *
     * @param string $message The debug message to log
     * @param array|null $context Optional: context data to include in the log
     */

    public function debug(string $message, ?array $context = null): void
    {
        if (jg_db_debug_enabled()) {
            $this->log('DEBUG', $message, $context);
        }
    }
    /**
     * method to log to a file.
      *
      * @param string $level The log type (e.g., ERROR, WARNING, INFO, DEBUG)
      * @param string $message The log message
      * @param array|null $context Optional: context data to include in the log
     */
    private function log(string $level, string $message, ?array $context = null): void
    {
        $timestamp = date('Y-m-d H:i:s');
        $contextStr = $context !== null ? ' | ' . json_encode($context) : '';
        $logLine = "[$timestamp] [$level] $message$contextStr\n";

        file_put_contents($this->logFile, $logLine, FILE_APPEND | LOCK_EX);
    }
}
