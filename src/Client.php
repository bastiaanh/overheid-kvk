<?php

namespace Overheid;

/**
 * Overheid.io API abstract client class.
 *
 * @author bastiaanh
 * @package overheid-kvk
 */
abstract class Client extends Base
{
    /**
     * @param string $apiKey
     * @return $this
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
        return $this;
    }
}
