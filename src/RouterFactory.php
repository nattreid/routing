<?php

declare(strict_types=1);

namespace NAttreid\Routing;

use Nette\Application\IRouter;
use Nette\Application\Routers\RouteList;
use Nette\SmartObject;

/**
 * Router factory.
 *
 * @property-read string $variable
 *
 * @author Attreid <attreid@gmail.com>
 */
class RouterFactory
{
	use SmartObject;

	CONST
		PRIORITY_HIGH = 0,
		PRIORITY_SYSTEM = 10,
		PRIORITY_APP = 20,
		PRIORITY_USER = 30;

	/** @var Router[][] */
	private $routers = [];

	/** @var string|null */
	private $locale;

	/** @var string */
	private $variable;

	public function __construct(string $variable)
	{
		$this->variable = $variable;
	}

	/**
	 * @return string
	 */
	public function getVariable(): string
	{
		return $this->variable;
	}

	/**
	 * Prida router
	 * @param Router $router
	 * @param int $priority
	 */
	public function addRouter(Router $router, int $priority = null)
	{
		$priority = $priority ?? PHP_INT_MAX;

		if (!isset($this->routers[$priority])) {
			$this->routers[$priority] = [];
		}

		$this->routers[$priority][] = $router;
	}

	/**
	 * Nastavi jazyk
	 * @param string $default
	 * @param array $allowed
	 */
	public function setLocale(string $default, array $allowed)
	{
		$this->locale = "[<{$this->variable}=$default " . implode('|', $allowed) . '>/]';
	}

	/**
	 * @return IRouter
	 */
	public function createRouter(): IRouter
	{
		$routeList = new RouteList();

		ksort($this->routers);

		foreach ($this->routers as $routers) {
			foreach ($routers as $router) {
				/* @var $router Router */
				$router->setRouteList($routeList);
				if ($this->locale !== null) {
					$router->setLocale($this->locale);
				}
				$router->createRoutes();
			}
		}
		return $routeList;
	}

}
