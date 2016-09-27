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
        - FrontRouter('//url/')
    configuration: 
        locale:
            default: cs
            allowed: [cs, en]
```

## FrontRouter
```php
class FrontRouter extends \NAttreid\Routing\Router {

    public function __construct($url, PagesRepository $pageModel) {
        parent::__construct($url);
    }

    public function createRoutes() {
        $routes = $this->getRouter('Front');

        $routes[] = new Route($this->getUrl(), 'Homepage:default');
        $routes[] = new Route($this->getUrl() . 'index.php', 'Page:default', Route::ONE_WAY);
        $routes[] = new Route($this->getUrl() . '<presenter>[/<action>]', 'Page:default');
    }

}
```