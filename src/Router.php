<?php

namespace NAttreid\Routing;

use Nette\Application\IRouter,
    Nette\Application\Routers\RouteList,
    Nette\Application\Routers\Route;

/**
 * Router modelu
 *
 * @author Attreid <attreid@gmail.com>
 */
abstract class Router {

    CONST
            PRIORITY_HIGH = 0,
            PRIORITY_SYSTEM = 10,
            PRIORITY_APP = 20,
            PRIORITY_USER = 30;

    /** @var IRouter */
    private $router;

    /** @var string */
    private $url;

    /** @var string */
    private $locale;

    /** @var string */
    private $flag;

    public function __construct($url = NULL, $secured = FALSE) {
        $this->url = $url;
        $this->flag = $secured ? Route::SECURED : Route::OPTIONAL;
    }

    /**
     * Nastavi hlavni router
     * @param IRouter $router
     */
    public function setRouteList(IRouter $router) {
        $this->router = $router;
    }

    /**
     * Nastavi locale
     * @param string $locale
     */
    public function setLocale($locale) {
        $this->locale = $locale;
    }

    /**
     * Vrati url
     * @return string
     */
    protected function getUrl() {
        return $this->url . $this->locale;
    }

    /**
     * Vrati flat
     * @return int
     */
    protected function getFlag() {
        return $this->flag;
    }

    /**
     * Vrati routu
     * @param string $module
     * @return IRouter
     */
    protected function getRouter($module = NULL) {
        if ($module !== NULL) {
            return $this->router[] = new RouteList($module);
        } else {
            return $this->router;
        }
    }

    abstract public function createRoutes();
}
