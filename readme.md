# Rozšíření Router pro Nette Framework

Vytvořte třídu děděním z **NAttreid\Routers\Route**
```php
class PageRoute extends NAttreid\Routers\Route {

    /** @var PagesRepository */
    private $pageModel;

    public function __construct(PagesRepository $pageModel) {
        $this->pageModel = $pageModel;
        parent::__construct('[<url>]', 'Page:default');
    }

    public function in($locale, $url) {
        if ($this->pageModel->exists($url)) {
            $this->parameters->url = $url;
            return TRUE;
        }
    }

    public function out($locale) {
        $this->addToSlug($this->parameters->url);
    }

}
```

A přidejte do router v **RouteFactory**
```php
$router[] = $routes = new RouteList('Front');

$routes[] = new PageRoute($pageModel);

$routes[] = new Route('<presenter>[/<action>]', 'Homepage:default');
```