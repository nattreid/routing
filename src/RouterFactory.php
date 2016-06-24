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

    public function __construct(array $routers, IConfigure $configure = NULL) {
        $this->routers = $routers;
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
            if ($router instanceof Router) {
                $router->setRouteList($routeList);
                $router->setLocale($this->locale);
                $router->createRoutes();
            } else {
                throw new \Nette\InvalidArgumentException("Router musi dedit z tridy 'NAttreid\Routers\Router'");
            }
        }

        return $routeList;
    }

}
