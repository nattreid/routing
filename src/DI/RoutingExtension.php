<?php

declare(strict_types=1);

namespace NAttreid\Routing\DI;

use NAttreid\Routing\RouterFactory;
use Nette\DI\CompilerExtension;
use Nette\DI\Statement;
use Nette\Reflection\ClassType;

/**
 * Rozsireni
 *
 * @author Attreid <attreid@gmail.com>
 */
class RoutingExtension extends CompilerExtension
{

	private $defaults = [
		'routers' => [],
		'configuration' => [
			'locale' => [
				'variable' => 'locale',
				'default' => null,
				'allowed' => null
			]
		]
	];

	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();
		$config = $this->validateConfig($this->defaults, $this->getConfig());

		$lang = $config['configuration']['locale'];

		$factory = $builder->addDefinition($this->prefix('routerFactory'))
			->setType(RouterFactory::class)
			->setArguments([$lang['variable']]);

		if ($lang['allowed'] !== null) {
			$factory->addSetup('setLocale', [$lang['allowed'], $lang['default']]);
		}

		foreach ($config['routers'] as $router) {
			$priority = null;
			if (is_array($router)) {
				list($router, $priority) = $router;
			}
			$route = $builder->addDefinition($this->prefix('router' . $this->getShortName($router)))
				->setType($this->getClass($router))
				->setFactory($this->getClass($router), $router instanceof Statement ? $router->arguments : []);
			$factory->addSetup('addRouter', [$route, $priority]);
		}
	}

	public function beforeCompile(): void
	{
		$builder = $this->getContainerBuilder();
		$builder->getDefinition('router')
			->setFactory('@' . RouterFactory::class . '::createRouter');
	}

	/**
	 * @param mixed $class
	 * @return string
	 */
	private function getClass($class): string
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
	private function getShortName($class): string
	{
		$classType = new ClassType($this->getClass($class));
		return $classType->getShortName();
	}

}
