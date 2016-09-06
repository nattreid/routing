<?php

namespace NAttreid\Routing\DI;

use NAttreid\Routing\RouterFactory;
use Nette\DI\Statement;

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
			'lang' => [
				'default' => NULL,
				'allowed' => NULL
			]
		]
	];

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$config = $this->validateConfig($this->defaults, $this->getConfig());

		$factory = $builder->addDefinition($this->prefix('routerFactory'))
			->setClass(RouterFactory::class);

		$lang = $config['configuration']['lang'];
		if ($lang['default'] !== NULL && $lang['allowed'] !== NULL) {
			$factory->addSetup('setLang', [$lang['default'], $lang['allowed']]);
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
		$classType = new \Nette\Reflection\ClassType($this->getClass($class));
		return $classType->getShortName();
	}

}
