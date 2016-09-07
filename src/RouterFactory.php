<?php

namespace NAttreid\Routing;

use NAttreid\Utils\Arrays;
use Nette\Application\Routers\RouteList;

/**
 * Router factory.
 *
 * @author Attreid <attreid@gmail.com>
 */
class RouterFactory
{

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
		$this->locale = '[<locale=' . $default . ' ' . implode('|', $allowed) . '>/]';
	}

	/**
	 * @return \Nette\Application\IRouter
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
