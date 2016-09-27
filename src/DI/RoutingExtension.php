<?php

namespace NAttreid\Routing\DI;

use NAttreid\Routing\RouterFactory;
use Nette\DI\Statement;
use Nette\Reflection\ClassType;

/**
 * Rozsireni
 *
 * @author Attreid <attreid@gmail.com>
 */
class RoutingExtension extends \Nette\DI\CompilerExtension
{

	private $defaults = [
		'routers' => [],
		'configuration' => [
			'locale' => [
				'variable' => 'locale',
				'default' => NULL,
				'allowed' => NULL
			]
		]
	];

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$config = $this->validateConfig($this->defaults, $this->getConfig());

		$lang = $config['configuration']['locale'];

		$factory = $builder->addDefinition($this->prefix('routerFactory'))
			->setClass(RouterFactory::class)
			->setArguments([$lang['variable']]);

		if ($lang['default'] !== NULL && $lang['allowed'] !== NULL) {
			$factory->addSetup('setLocale', [$lang['default'], $lang['allowed']]);
		}

		foreach ($config['routers'] as $router) {
			$route = $builder->addDefinition($this->prefix('router' . $this->getShortName($router)))
				->setClass($this->getClass($router), $router instanceof Statement ? $router->arguments : []);
			$factory->addSetup('addRouter', [$route]);
		}
	}

	public function beforeCompile()
	{
		$builder = $this->getContainerBuilder();
		$builder->getDefinition('router')
			->setFactory('@' . RouterFactory::class . '::createRouter');
	}

	/**
	 * @param mixed $class
	 * @return string
	 */
	private function getClass($class)
	{
		if ($class instanceof Statement) {
			return $class->getEntity();
		} elseif (is_object($class)) {
			return get_class($class);
		} else {
			return $class;
		}
	}

	/**
	 * @param mixed $class
	 * @return string
	 */
	private function getShortName($class)
	{
		$classType = new ClassType($this->getClass($class));
		return $classType->getShortName();
	}

}
