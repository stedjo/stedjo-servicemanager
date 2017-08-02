<?php
/**
 * If you need an environment-specific system or application configuration,
 * there is an example in the documentation
 * @see https://docs.zendframework.com/tutorials/advanced-config/#environment-specific-system-configuration
 * @see https://docs.zendframework.com/tutorials/advanced-config/#environment-specific-application-configuration
 */
return [
	// Retrieve list of modules used in this application.
	'modules' => require __DIR__ . '/modules.config.php',

	// These are various options for the listeners attached to the ModuleManager
	'module_listener_options' => [
		// This should be an array of paths in which modules reside.
		// If a string key is provided, the listener will consider that a module
		// namespace, the value of that key the specific path to that module's
		// Module class.
		'module_paths' => [
			'./vendor',
		],

		// An array of paths from which to glob configuration files after
		// modules are loaded. These effectively override configuration
		// provided by modules themselves. Paths may use GLOB_BRACE notation.
		'config_glob_paths' => [
			realpath(__DIR__) . '/autoload/{{,*.}global,{,*.}local}.php',
		],
	],
];
