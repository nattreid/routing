<?php

namespace NAttreid\Routers;

/**
 * Konfigurace routy
 *
 * @author Attreid <attreid@gmail.com>
 */
interface IConfigure {

    /**
     * Vrati defaultni jazyk
     * @return string
     */
    public function getDefaultLanguage();

    /**
     * Vrati povolene jazyky
     * @return array
     */
    public function getAllowedLanguages();
}
