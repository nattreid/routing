# Rozšíření Router pro Nette Framework

## Nastaveni
V **config.neon** zaregistruje extension
```neon
extensions:
    router: NAttreid\Routing\DI\RoutingExtension
```

a nastavte. Router přebírá parametry $url a $sercure. $url => adresa, $secure => https nebo http (TRUE => https)
```neon
services:
    routeConfigure: RouteConfigure

router:
    routers:
        - FrontRouter('url', FALSE)
    configuration: 
        lang:
            default: @configuration::defaultLang
            allowed: @configuration::allowedLang
```

## FrontRouter
```php
class FrontRouter extends \NAttreid\Routing\Router {

    /** @var PageRoute */
    private $pageRoute;

    public function __construct($url, $secured, PagesRepository $pageModel) {
        parent::__construct($url,$secured);
        $this->pageRoute = new PageRoute($this->getUrl(), $pageModel, $this->getFlag());
    }

    public function createRoutes() {
        $routes = $this->getRouter('Front');

        $routes[] = $this->pageRoute;

        $routes[] = new Route($this->getUrl(), 'Homepage:default', $this->getFlag());
        $routes[] = new Route($this->getUrl() . 'index.php', 'Page:default', Route::ONE_WAY);
        $routes[] = new Route($this->getUrl() . '<presenter>[/<action>]', 'Page:default', $this->getFlag());
    }

}
```

## PageRoute

Vytvořte třídu děděním z **NAttreid\Routing\Route**
```php
class PageRoute extends \NAttreid\Routing\Route {

    /** @var PagesRepository */
    private $pageModel;

    public function __construct(PagesRepository $pageModel) {
        $this->pageModel = $pageModel;
        parent::__construct('[<url>]', 'Page:default');
    }

    public function in($$url) {
        if ($this->pageModel->exists($url)) {
            $this->parameters->url = $url;
            return TRUE;
        }
    }

    public function out() {
        $this->addToSlug($this->parameters->url);
    }

}
```