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
        - {FrontRouter('//url/'), 0} #router s prioritou 
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

        $routes[] = new Route($this->url, 'Homepage:default');
        $routes[] = new Route($this->url . 'index.php', 'Page:default', Route::ONE_WAY);
        $routes[] = new Route($this->url . '<presenter>[/<action>]', 'Page:default');
    }

}
```