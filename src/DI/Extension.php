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

    /**
     * @param string $router
     * @return string
     */
    private function getClass($router) {
        $class = is_object($router) ? $router->getEntity() : $router;
        $classType = new \Nette\Reflection\ClassType($class->getEntity());
        return $classType->getShortName();
    }

    public function loadConfiguration() {
        $builder = $this->getContainerBuilder();
        $config = $this->validateConfig($this->defaults, $this->getConfig());

        $configuration = $config['configuration'];
        if ($configuration !== NULL) {
            $builder->addDefinition($this->prefix('configuration'))
                    ->setClass($configuration);
        }

        foreach ($config['routers'] as $router) {
            $builder->addDefinition($this->prefix('router' . $this->getClass($router)))
                    ->setClass($router->getEntity(), $router->arguments);
        }

        $builder->addDefinition($this->prefix('routerFactory'))
                ->setClass('NAttreid\Routers\RouterFactory');
    }

    public function beforeCompile() {
        $builder = $this->getContainerBuilder();
        $builder->getDefinition('router')
                ->setFactory('@NAttreid\Routers\RouterFactory::createRouter');

        if (isset($this->config['configuration'])) {
            $builder->getDefinition($this->prefix('routerFactory'))
                    ->addSetup('setConfigure', ['@router.configuration']);
        }

        foreach ($this->config['routers'] as $router) {
            $builder->getDefinition($this->prefix('routerFactory'))
                    ->addSetup('addRouter', ['@' . $this->prefix('router' . $this->getClass($router))]);
        }
    }

}
