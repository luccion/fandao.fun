<?php
/**
 * Modern Configuration Manager for fandao.fun
 * 
 * This class provides a centralized way to manage application configuration
 * in the new modern structure while maintaining backward compatibility.
 */

class ModernConfig
{
    private static $instance = null;
    private $config = [];
    
    private function __construct()
    {
        $this->loadConfiguration();
    }
    
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function loadConfiguration()
    {
        // Load base configuration
        $configFile = ROOT . 'config.inc.php';
        if (file_exists($configFile)) {
            include $configFile;
            if (isset($_CFG)) {
                $this->config = $_CFG;
            }
        }
        
        // Load environment variables
        $this->loadEnvironment();
        
        // Set default values for new structure
        $this->setDefaults();
    }
    
    private function loadEnvironment()
    {
        // Load .env file if exists
        $envFile = ROOT . '.env';
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos($line, '=') !== false && $line[0] !== '#') {
                    list($key, $value) = explode('=', $line, 2);
                    $key = trim($key);
                    $value = trim($value, " \t\n\r\0\x0B\"'");
                    
                    // Override config with environment values
                    $this->config[$key] = $value;
                    
                    // Also set as environment variable if not already set
                    if (!getenv($key)) {
                        putenv("$key=$value");
                    }
                }
            }
        }
    }
    
    private function setDefaults()
    {
        $defaults = [
            'app_name' => 'fandao.fun',
            'app_version' => '1.0.0',
            'app_debug' => false,
            'backend_path' => ROOT . 'backend/',
            'frontend_path' => ROOT . 'frontend/',
            'storage_path' => ROOT . 'backend/storage/',
            'public_path' => ROOT . 'backend/public/',
            'api_base_url' => '/api',
            'upload_max_size' => '10M',
            'session_lifetime' => 86400, // 24 hours
        ];
        
        foreach ($defaults as $key => $value) {
            if (!isset($this->config[$key])) {
                $this->config[$key] = $value;
            }
        }
    }
    
    public function get($key, $default = null)
    {
        return $this->config[$key] ?? $default;
    }
    
    public function set($key, $value)
    {
        $this->config[$key] = $value;
    }
    
    public function has($key)
    {
        return isset($this->config[$key]);
    }
    
    public function all()
    {
        return $this->config;
    }
    
    // Helper methods for common paths
    public function getStoragePath($subpath = '')
    {
        return $this->get('storage_path') . $subpath;
    }
    
    public function getPublicPath($subpath = '')
    {
        return $this->get('public_path') . $subpath;
    }
    
    public function getBackendPath($subpath = '')
    {
        return $this->get('backend_path') . $subpath;
    }
    
    public function getFrontendPath($subpath = '')
    {
        return $this->get('frontend_path') . $subpath;
    }
    
    // Check if we're in development mode
    public function isDebug()
    {
        return (bool) $this->get('app_debug', false);
    }
    
    // Get database configuration
    public function getDatabaseConfig()
    {
        return [
            'host' => $this->get('DB_HOST', 'localhost'),
            'port' => $this->get('DB_PORT', 3306),
            'database' => $this->get('DB_DATABASE'),
            'username' => $this->get('DB_USERNAME'),
            'password' => $this->get('DB_PASSWORD'),
            'charset' => $this->get('DB_CHARSET', 'utf8mb4'),
        ];
    }
}