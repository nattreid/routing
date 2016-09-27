<?php

namespace NAttreid\Routing;

use NAttreid\Utils\Arrays;
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

	/** @var Router[] */
	private $routers = [];

	/** @var Router[] */
	private $indexedRouters = [];

	/** @var string */
	private $locale;

	/** @var string */
	private $variable;

	public function __construct($variable)
	{
		$this->variable = $variable;
	}

	/**
	 * @return string
	 */
	public function getVariable()
	{
		return $this->variable;
	}

	/**
	 * Prida router
	 * @param Router $router
	 * @param int $priority
	 */
	public function addRouter(Router $router, $priority = NULL)
	{
		if ($priority !== NULL) {
			Arrays::slice($this->indexedRouters, $priority, $router);
		} else {
			$this->routers[] = $router;
		}
	}

	/**
	 * Nastavi jazyk
	 * @param string $default
	 * @param array $allowed
	 */
	public function setLocale($default, array $allowed)
	{
		$this->locale = "[<{$this->variable}=$default " . implode('|', $allowed) . '>/]';
	}

	/**
	 * @return IRouter
	 */
	public function createRouter()
	{
		$routeList = new RouteList();

		ksort($this->indexedRouters);
		$routers = array_merge(array_values($this->indexedRouters), $this->routers);

		foreach ($routers as $router) {
			/* @var $router Router */
			$router->setRouteList($routeList);
			$router->setLocale($this->locale);
			$router->createRoutes();
		}
		return $routeList;
	}

}
