<?php

namespace NAttreid\Routing;

use Nette\Application\IRouter;
use Nette\Application\Routers\RouteList;
use Nette\SmartObject;

/**
 * Router modelu
 *
 * @property-read string $url
 *
 * @author Attreid <attreid@gmail.com>
 */
abstract class Router
{
	use SmartObject;

	/** @var IRouter */
	private $router;

	/** @var string */
	private $url;

	/** @var string */
	private $locale;

	public function __construct($url = null)
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
	protected function getRouter($module = null)
	{
		if ($module !== null) {
			return $this->router[] = new RouteList($module);
		} else {
			return $this->router;
		}
	}

	abstract public function createRoutes();
}
