<?php

namespace NAttreid\Routing;

use Nette\Application\IRouter;
use Nette\Application\Routers\RouteList;

/**
 * Router modelu
 *
 * @author Attreid <attreid@gmail.com>
 */
abstract class Router
{

	/** @var IRouter */
	private $router;

	/** @var string */
	private $url;

	/** @var string */
	private $locale;

	public function __construct($url = NULL)
	{
		$this->url = $url;
	}

	/**
	 * Nastavi hlavni router
	 * @param IRouter $router
	 */
	public function setRouteList(IRouter $router)
	{
		$this->router = $router;
	}

	/**
	 * Nastavi locale
	 * @param string $locale
	 */
	public function setLocale($locale)
	{
		$this->locale = $locale;
	}

	/**
	 * Vrati url
	 * @return string
	 */
	protected function getUrl()
	{
		return $this->url . $this->locale;
	}

	/**
	 * Vrati routu
	 * @param string $module
	 * @return IRouter
	 */
	protected function getRouter($module = NULL)
	{
		if ($module !== NULL) {
			return $this->router[] = new RouteList($module);
		} else {
			return $this->router;
		}
	}

	abstract public function createRoutes();
}
