<?php

namespace NAttreid\Routers;

/**
 * Parametry routy
 * 
 * @author Attreid <attreid@gmail.com>
 */
class Parameters {

    private $params = [];

    public function __construct($params) {
        $this->params = $params;
    }

    public function __get($name) {
        $result = NULL;
        if (isset($this->params[$name])) {
            $result = $this->params[$name];
            unset($this->params[$name]);
        }
        return $result;
    }

    public function __set($name, $value) {
        $this->params[$name] = $value;
    }

    public function get($name = NULL) {
        if ($name !== NULL) {
            if (isset($this->params[$name])) {
                return $this->params[$name];
            } else {
                return NULL;
            }
        }
        return $this->params;
    }

}
