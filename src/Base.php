<?php

namespace Overheid;

/**
 * Overheid.io API abstract base class for API client class(es) and Resultset class.
 *
 * @author bastiaanh
 * @package overheid-kvk
 */
abstract class Base
{
    /**
     * @var string
     */
    protected $apiUrl = 'https://overheid.io';

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var string
     */
    protected $lastRequestUrl;

    /**
     * @var mixed
     */
    protected $lastRequestResponse;

    /**
     * Return response without internal data.
     *
     * @param mixed $response
     * @return mixed
     */
    protected function clean($response)
    {
        if (is_array($response)) {
            $result = array();
            foreach ($response as $_key => $_value) {
                if (substr($_key, 0, 1) != '_') {
                    $result[$_key] = $this->clean($_value);
                }
            }
            return $result;
        }
        return $response;
    }

    /**
     * Perform the actual API request and returns JSON decoded response.
     *
     * @param string $uri
     * @return mixed
     * @throws Exception
     */
    protected function request($uri)
    {
        if (empty($this->apiKey)) {
            throw new Exception('Api key not set');
        }

        // use last response if the url is the same
        $url = $this->apiUrl . $uri;
        if ($url == $this->lastRequestUrl) {
            return $this->lastRequestResponse;
        }

        // use cURL to perform a HTTP GET request to the webservice
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'ovio-api-key: ' . $this->apiKey
        ));
        $result = curl_exec($ch);

        $errno = curl_errno($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($errno) {
            throw new Exception('Api request error ' . $errno . ': ' . $error);
        }

        $response = json_decode($result, true, 128, JSON_BIGINT_AS_STRING);
        if (is_null($response)) {
            throw new Exception('Api response JSON decode error');
        }

        // save url and response into object and return response
        $this->lastRequestUrl = $url;
        $this->lastRequestResponse = $response;

        return $this->lastRequestResponse;
    }

    /**
     * Perform API request and return Resultset object (which might triggers additional API requests).
     *
     * @param string $uri
     * @param string $entity
     * @return Resultset
     */
    protected function requestSet($uri, $entity)
    {
        $response = $this->request($uri);

        // use the initial response to instantiate the Resultset object
        return new Resultset($this, $response, $entity);
    }
}
