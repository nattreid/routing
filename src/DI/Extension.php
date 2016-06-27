<?php

namespace NAttreid\Routers\DI;

use Nette\DI\Statement;

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
     * @param mixed $class
     * @return string
     */
    private function getClass($class) {
        if ($class instanceof Statement) {
            return $class->getEntity();
        } elseif (is_object($class)) {
            return get_class($class);
        } else {
            return $class;
        }
    }

    /**
     * @param mixed $class
     * @return string
     */
    private function getShortName($class) {
        $classType = new \Nette\Reflection\ClassType($this->getClass($class));
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
            $builder->addDefinition($this->prefix('router' . $this->getShortName($router)))
                    ->setClass($this->getClass($router), $router instanceof Statement ? $router->arguments : []);
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
