# Rozšíření Router pro Nette Framework

## Nastaveni
V **config.neon** zaregistruje extension
```neon
extensions:
    router: NAttreid\Routing\DI\RoutingExtension
```

a nastavte. Router přebírá parametry $url a $sercure. $url => adresa, $secure => https nebo http (TRUE => https)
```neon
router:
    routers:
        - FrontRouter('url', FALSE)
    configuration: 
        locale:
            default: cs
            allowed: [cs, en]
```

## FrontRouter
```php
class FrontRouter extends \NAttreid\Routing\Router {

    /** @var PageRoute */
    private $pageRoute;

    public function __construct($url, PagesRepository $pageModel) {
        parent::__construct($url);
        $this->pageRoute = new PageRoute($this->getUrl(), $pageModel);
    }

    public function createRoutes() {
        $routes = $this->getRouter('Front');

        $routes[] = $this->pageRoute;

        $routes[] = new Route($this->getUrl(), 'Homepage:default');
        $routes[] = new Route($this->getUrl() . 'index.php', 'Page:default', Route::ONE_WAY);
        $routes[] = new Route($this->getUrl() . '<presenter>[/<action>]', 'Page:default');
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