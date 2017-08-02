<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 02/08/2017
 * Time: 11:33
 */

namespace SD;

use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\ServiceManager\ServiceManager as ZendServiceManager;

class ServiceManager
{
	private static $instances = [];
	private $serviceManager;

	/**
	 * Fetch (and create if needed) an instance of this class.
	 *
	 * @param string $configPath
	 * @return ServiceManager
	 */
	public static function getInstance($configPath = __DIR__ . '/config/application.config.php')
	{
		$config = require $configPath;
		if (!array_key_exists($config, self::$instances)) {
			self::$instances[$config] = new self($config);
		}

		return self::$instances[$config];
	}

	/**
	 * Initiate service manager and store the instance for further use
	 * ServiceManager constructor.
	 * @param array $config
	 */
	private function __construct(array $config)
	{
		// Prepare the service manager
		$smConfig = isset($config['service_manager']) ? $config['service_manager'] : [];
		$smConfig = new ServiceManagerConfig($smConfig);

		$serviceManager = new ZendServiceManager();
		$smConfig->configureServiceManager($serviceManager);
		$serviceManager->setService('ApplicationConfig', $config);

		$serviceManager->get('ModuleManager')->loadModules();

		$this->serviceManager = $serviceManager;
	}

	/**
	 * Magic call method which propagates calls to original service manager instance
	 * @param $name
	 * @param $arguments
	 * @return mixed
	 */
	public function __call($name, $arguments)
	{
		return $responseObject = call_user_func_array([$this->serviceManager, $name], $arguments);
	}
}
