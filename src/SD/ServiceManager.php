<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 02/08/2017
 * Time: 11:33
 */

namespace SD;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\ServiceManager\Exception;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager as ZendServiceManager;

class ServiceManager implements ServiceLocatorInterface
{
	private static $instance = null;
	private $serviceManager;

	/**
	 * Fetch (and create if needed) an instance of this class.
	 *
	 * @param array $config
	 * @return ServiceManager
	 */
	public static function getInstance(array $config)
	{
		if (is_null(self::$instance)) {
			self::$instance = new self($config);
		}

		return self::$instance;
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
	 * Finds an entry of the container by its identifier and returns it.
	 *
	 * @param string $id Identifier of the entry to look for.
	 *
	 * @throws NotFoundExceptionInterface  No entry was found for **this** identifier.
	 * @throws ContainerExceptionInterface Error while retrieving the entry.
	 *
	 * @return mixed Entry.
	 */
	public function get($id)
	{
		return $this->serviceManager->get($id);
	}

	/**
	 * Returns true if the container can return an entry for the given identifier.
	 * Returns false otherwise.
	 *
	 * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
	 * It does however mean that `get($id)` will not throw a `NotFoundExceptionInterface`.
	 *
	 * @param string $id Identifier of the entry to look for.
	 *
	 * @return bool
	 */
	public function has($id)
	{
		return $this->serviceManager->has($id);
	}

	/**
	 * Build a service by its name, using optional options (such services are NEVER cached).
	 *
	 * @param  string $name
	 * @param  null|array $options
	 * @return mixed
	 * @throws Exception\ServiceNotFoundException If no factory/abstract
	 *     factory could be found to create the instance.
	 * @throws Exception\ServiceNotCreatedException If factory/delegator fails
	 *     to create the instance.
	 * @throws ContainerExceptionInterface if any other error occurs
	 */
	public function build($name, array $options = null)
	{
		return $this->serviceManager->build($name, $options);
	}
}
