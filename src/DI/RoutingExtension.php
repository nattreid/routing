<?php

namespace NAttreid\Routing\DI;

use Nette\DI\Statement;

/**
 * Rozsireni
 *
 * @author Attreid <attreid@gmail.com>
 */
class RoutingExtension extends \Nette\DI\CompilerExtension {

    private $defaults = [
        'routers' => [],
        'configuration' => [
            'lang' => [
                'default' => NULL,
                'allowed' => NULL
            ]
        ]
    ];

    public function loadConfiguration() {
        $builder = $this->getContainerBuilder();
        $config = $this->validateConfig($this->defaults, $this->getConfig());

        $lang = $config['configuration']['lang'];

        foreach ($config['routers'] as $router) {
            $builder->addDefinition($this->prefix('router' . $this->getShortName($router)))
                    ->setClass($this->getClass($router), $router instanceof Statement ? $router->arguments : []);
        }

        $factory = $builder->addDefinition($this->prefix('routerFactory'))
                ->setClass('NAttreid\Routing\RouterFactory');

        if ($lang['default'] !== NULL && is_array($lang['allowed'])) {
            $factory->addSetup('setLang', [$lang['default'], $lang['allowed']]);
        }
    }

    public function beforeCompile() {
        $builder = $this->getContainerBuilder();
        $builder->getDefinition('router')
                ->setFactory('@NAttreid\Routing\RouterFactory::createRouter');

        foreach ($this->config['routers'] as $router) {
            $builder->getDefinition($this->prefix('routerFactory'))
                    ->addSetup('addRouter', ['@' . $this->prefix('router' . $this->getShortName($router))]);
        }
    }

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

}
