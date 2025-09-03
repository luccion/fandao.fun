<?php
/*
file conf.class.php
配置文件读写类
*/

defined('ACC') || exit('ACC Denied');
class conf
{
	protected static $ins = null;
	protected $data = array();

	final protected function __construct()
	{
		// Load base config
		include(ROOT . 'config.inc.php');
		$this->data = $_CFG;
		// Load environment overrides if available
		if (file_exists(ROOT . '.env')) {
			// Lazy load Dotenv if installed
			if (file_exists(ROOT . 'vendor/autoload.php')) {
				require_once ROOT . 'vendor/autoload.php';
				$factory = ['Dotenv\\Dotenv', 'createImmutable'];
				if (is_callable($factory)) {
					$dotenv = call_user_func($factory, ROOT);
					if (is_object($dotenv) && method_exists($dotenv, 'safeLoad')) {
						$dotenv->safeLoad();
					}
				}
			}
		}
		// Override configurable items from env when present
		$map = [
			'host' => 'DB_HOST',
			'user' => 'DB_USER',
			'pass' => 'DB_PASS',
			'db' => 'DB_NAME',
			'charset' => 'DB_CHARSET',
			'aifadian_foundation_id' => 'AIFADIAN_FOUNDATION_ID',
			'aifadian_foundation_token' => 'AIFADIAN_FOUNDATION_TOKEN',
			'site_name' => 'SITE_NAME',
			'version' => 'SITE_VERSION',
			'content_length_limit' => 'CONTENT_LENGTH_LIMIT',
			'post_cd' => 'POST_CD',
		];
		foreach ($map as $key => $envKey) {
			if (isset($_ENV[$envKey]) && $_ENV[$envKey] !== '') {
				$this->data[$key] = $_ENV[$envKey];
			}
		}
	}

	final protected function __clone() {}

	public static function getInstance()
	{
		if (self::$ins == null) {
			self::$ins = new self;
		}
		return self::$ins;
	}

	//用魔术方法，读取data内的信息
	public function __get($key)
	{
		if (array_key_exists($key, $this->data)) {
			return $this->data[$key];
		} else {
			return null;
		}
	}

	//用魔术方法，在运行期，动态增加或改变配置选项
	public function __set($key, $value)
	{
		$this->data[$key] = $value;
	}
}
