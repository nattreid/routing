<?php

namespace NAttreid\Routing;

use Nette\Application\Routers\RouteList;

/**
 * Router factory.
 * 
 * @author Attreid <attreid@gmail.com>
 */
class RouterFactory {

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
    public function addRouter(Router $router, $priority = NULL) {
        if ($priority !== NULL) {
            $arr = [$router];
            array_splice($this->indexedRouters, $priority, 0, $arr);
        } else {
            $this->routers[] = $router;
        }
    }

    /**
     * Nastavi jazyk
     * @param string $default
     * @param array $allowed
     */
    public function setLang($default, array $allowed) {
        $this->locale = '[<locale=' . $default . ' ' . implode('|', $allowed) . '>/]';
    }

    /**
     * @return \Nette\Application\IRouter
     */
    public function createRouter() {
        $routeList = new RouteList();

        ksort($this->indexedRouters);
        $routers = array_merge(array_values($this->indexedRouters), $this->routers);

        foreach ($routers as $router) {
            $router->setRouteList($routeList);
            $router->setLocale($this->locale);
            $router->createRoutes();
        }
        return $routeList;
    }

}
