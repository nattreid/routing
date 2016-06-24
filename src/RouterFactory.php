<?php

namespace NAttreid\Routers;

use Nette\Application\Routers\RouteList;

/**
 * Router factory.
 * 
 * @author Attreid <attreid@gmail.com>
 */
class RouterFactory {

    /** @var Router[] */
    private $routers = [];

    /** @var string */
    private $locale;

    public function addRouter(Router $router, $position = NULL) {
        if ($position !== NULL) {
            $arr = [$router];
            array_splice($this->routers, $position, 0, $arr);
        } else {
            $this->routers[] = $router;
        }
    }

    public function setConfigure(IConfigure $configure) {
        if ($configure !== NULL) {
            $this->locale = '[<locale=' . $configure->getDefaultLanguage() . ' ' . implode('|', $configure->getAllowedLanguages()) . '>/]';
        }
    }

    /**
     * @return \Nette\Application\IRouter
     */
    public function createRouter() {
        $routeList = new RouteList();
        foreach ($this->routers as $router) {
            $router->setRouteList($routeList);
            $router->setLocale($this->locale);
            $router->createRoutes();
        }
        return $routeList;
    }

}
