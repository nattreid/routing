# Rozšíření Router pro Nette Framework

## Nastaveni
V **config.neon** zaregistruje extension
```neon
extensions:
    router: NAttreid\Routers\DI\Extension
```

a nastavte. Router přebírá parametry $url a $sercure. $url => adresa, $secure => https nebo http (TRUE => https)
```neon
services:
    routeConfigure: RouteConfigure

router:
    routers:
        - FrontRouter('url', FALSE)
    configuration: RouteConfigure
```

Nastaveni configuratoru
```php
class RouteConfigure implements \NAttreid\Routers\IConfigure {
    public function getDefaultLanguage() {
        return 'cs';
    }

    public function getAllowedLanguages() {
        return ['cs', 'en'];
    }
}
```

## FrontRouter
```php
class FrontRouter extends \NAttreid\Routers\Router {

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

Vytvořte třídu děděním z **NAttreid\Routers\Route**
```php
class PageRoute extends \NAttreid\Routers\Route {

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