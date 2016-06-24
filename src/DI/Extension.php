<?php

namespace NAttreid\Routers\DI;

/**
 * Rozsireni
 *
 * @author Attreid <attreid@gmail.com>
 */
class Extension extends \Nette\DI\CompilerExtension {

    private $defaults = [
        'routers' => [],
        'configuration' => NULL
    ];

    public function loadConfiguration() {
        $builder = $this->getContainerBuilder();
        $config = $this->validateConfig($this->defaults, $this->getConfig());

        $configuration = $config['configuration'];
        if ($configuration !== NULL) {
            if (!$configuration instanceof \NAttreid\Routers\IConfigure) {
                throw new \Nette\InvalidArgumentException("Route Configuration musi implementovat '\NAttreid\Routers\IConfigure'");
            }
        }

        $builder->addDefinition($this->prefix('routerFactory'))
                ->setClass('NAttreid\Routers\RouteFactory', [$config['routers'], $configuration]);
    }

    public function beforeCompile() {
        $builder = $this->getContainerBuilder();
        $builder->getDefinition('router')
                ->setFactory('@NAttreid\Routers\RouteFactory::createRouter');
    }

}
