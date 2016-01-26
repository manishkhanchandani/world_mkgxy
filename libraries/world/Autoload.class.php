<?php
final class world_Autoload
{

	/**
	 * Constructor (default - private)
	 */
	private function __construct() { }

	/**
	 * register (static)
	 *
	 */
	public static function register()
	{
		$className = __CLASS__;
		spl_autoload_register(array(__CLASS__, 'autoload'));
	}

	/**
	 * autoload (static)
	 *
	 * @param string $className
	 * @return bool
	 */
	public static function autoload($className)
	{
		$baseName = basename(dirname(__FILE__)) .'_';

		if	(
				(strlen($className) < strlen($baseName))
			||	(substr($className, 0, strlen($baseName)) != $baseName)
			)
		{
			return false;
		}

		$path = str_replace('_', DIRECTORY_SEPARATOR, $className);

		$classFile = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . $path . '.class.php';

		if (!file_exists($classFile) || !is_readable($classFile))
		{
			return false;
		}

		return include_once($classFile);
	}
}

