<?php

namespace NAttreid\Routing;

use Nette\Utils\Strings,
    Nette\Application\Request,
    Nette\Http\IRequest,
    Nette\Http\Url;

/**
 * Uprava tridy pro routovani
 * 
 * @author Attreid <attreid@gmail.com>
 */
abstract class Route extends \Nette\Application\Routers\Route {

    /** @var Parameters[] */
    protected $parameters;

    /** @var array */
    private $slug;

    /** @var boolean */
    private $outCalled = TRUE;

    /**
     * {@inheritdoc }
     */
    public function match(IRequest $httpRequest) {
        if (parent::match($httpRequest)) {
            $url = Strings::replace($httpRequest->getUrl()->getPathInfo(), '/\/$/');

            $this->parameters = new Parameters($httpRequest->getQuery());

            if ($this->in($url)) {
                return new Request(
                        $this->getDefaults()[self::PRESENTER_KEY], //
                        $httpRequest->getMethod(), //
                        $this->parameters->get(), //
                        $httpRequest->getPost(), //
                        $httpRequest->getFiles(), //
                        [Request::SECURED => $httpRequest->isSecured()] //
                );
            }
        }
        return NULL;
    }

    /**
     * {@inheritdoc }
     */
    public function constructUrl(Request $appRequest, Url $refUrl) {
        if ($appRequest->presenterName === $this->getDefaults()[self::PRESENTER_KEY]) {
            $this->parameters = new Parameters($appRequest->getParameters());
            
            // odstraneni parametru
            $this->parameters->action; 
            $this->parameters->locale;

            $this->slug = [];
            $this->out();

            $constructUrl = parent::constructUrl($appRequest, $refUrl);
            if (!$this->outCalled) {
                return $constructUrl;
            } elseif ($constructUrl !== NULL && !empty($this->slug)) {
                $baseUrl = new \Nette\Http\Url($constructUrl);
                $baseUrl = str_replace($baseUrl->getBasePath(), '', $baseUrl->getBaseUrl());
                $url = new \Nette\Http\Url($baseUrl . '/' . implode('/', $this->slug) . '/');
                $url->setQuery($this->parameters->get());
                return $url->getAbsoluteUrl();
            }
        }
        return NULL;
    }

    /**
     * Prida hodnotu do url
     * @param string $value
     */
    protected function addToSlug($value) {
        if ($value) {
            $this->slug[] = $value;
        }
    }

    /**
     * Nastavi akci
     * @param string $action
     */
    protected function setAction($action) {
        $this->parameters->action = $action;
    }

    /**
     * Nastavi presenter
     * @param string maska
     */
    protected function setPresenter($presenter) {
        $this->presenter = $presenter;
    }

    /**
     * Uprava url pro zobrazeni
     */
    public function out() {
        $this->outCalled = FALSE;
    }

    /**
     * Kontrola url pri requestu
     * @param string $url
     */
    public abstract function in($url);
}
