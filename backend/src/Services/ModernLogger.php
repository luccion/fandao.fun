<?php
/**
 * Modern Logger for fandao.fun
 * 
 * A simple logging utility that follows modern practices
 */

class ModernLogger
{
    const LEVEL_DEBUG = 'DEBUG';
    const LEVEL_INFO = 'INFO';
    const LEVEL_WARNING = 'WARNING';
    const LEVEL_ERROR = 'ERROR';
    
    private static $instance = null;
    private $logPath;
    private $enabled;
    
    private function __construct()
    {
        $config = ModernConfig::getInstance();
        $this->logPath = $config->getStoragePath('logs/');
        $this->enabled = $config->isDebug() || $config->get('logging_enabled', false);
        
        // Ensure log directory exists
        if (!is_dir($this->logPath)) {
            mkdir($this->logPath, 0755, true);
        }
    }
    
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function log($level, $message, $context = [])
    {
        if (!$this->enabled) {
            return;
        }
        
        $timestamp = date('Y-m-d H:i:s');
        $contextStr = empty($context) ? '' : ' ' . json_encode($context);
        $logEntry = "[{$timestamp}] {$level}: {$message}{$contextStr}" . PHP_EOL;
        
        // Write to daily log file
        $logFile = $this->logPath . date('Y-m-d') . '.log';
        file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
        
        // Also write errors to separate error log
        if ($level === self::LEVEL_ERROR) {
            $errorFile = $this->logPath . 'error.log';
            file_put_contents($errorFile, $logEntry, FILE_APPEND | LOCK_EX);
        }
    }
    
    public function debug($message, $context = [])
    {
        $this->log(self::LEVEL_DEBUG, $message, $context);
    }
    
    public function info($message, $context = [])
    {
        $this->log(self::LEVEL_INFO, $message, $context);
    }
    
    public function warning($message, $context = [])
    {
        $this->log(self::LEVEL_WARNING, $message, $context);
    }
    
    public function error($message, $context = [])
    {
        $this->log(self::LEVEL_ERROR, $message, $context);
    }
    
    // Log request information
    public function logRequest()
    {
        if (!$this->enabled) {
            return;
        }
        
        $request = [
            'method' => $_SERVER['REQUEST_METHOD'] ?? 'UNKNOWN',
            'uri' => $_SERVER['REQUEST_URI'] ?? 'UNKNOWN',
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'UNKNOWN',
            'timestamp' => time()
        ];
        
        $this->info('Request', $request);
    }
    
    // Log performance metrics
    public function logPerformance($startTime, $operation = 'request')
    {
        if (!$this->enabled) {
            return;
        }
        
        $endTime = microtime(true);
        $duration = round(($endTime - $startTime) * 1000, 2); // milliseconds
        $memory = round(memory_get_peak_usage() / 1024 / 1024, 2); // MB
        
        $this->info("Performance: {$operation}", [
            'duration_ms' => $duration,
            'memory_mb' => $memory
        ]);
    }
}