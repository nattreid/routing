<?php

declare(strict_types=1);

namespace NAttreid\Routing;

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

	/** @var RouteList */
	private $router;

	/** @var string */
	private $url = '';

	/** @var string */
	private $locale = '';

	public function __construct(string $url = null)
	{
		$this->url = $url;
	}

	/**
	 * Nastavi hlavni router
	 * @param RouteList $router
	 */
	public function setRouteList(RouteList $router): void
	{
		$this->router = $router;
	}

	/**
	 * Nastavi locale
	 * @param string $locale
	 */
	public function setLocale(string $locale): void
	{
		$this->locale = $locale;
	}

	/**
	 * Vrati url
	 * @return string
	 */
	protected function getUrl(): string
	{
		return $this->url . $this->locale;
	}

	/**
	 * Vrati routu
	 * @param string $module
	 * @return RouteList
	 */
	protected function getRouter(string $module = null): RouteList
	{
		if ($module !== null) {
			return $this->router[] = new RouteList($module);
		} else {
			return $this->router;
		}
	}

	abstract public function createRoutes(): void;
}
