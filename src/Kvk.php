<?php

namespace Overheid;

/**
 * Overheid.io KvK API client class.
 *
 * @author bastiaanh
 * @package overheid-kvk
 */
class Kvk extends Client
{
    /**
     * Search in the dataset of openKvK.
     * @link https://overheid.io/documentatie/kvk#list
     *
     * @param array $parameters
     * @return Resultset
     * @throws Exception
     */
    public function search($parameters = array())
    {
        return $this->requestSet('/api/kvk?' . http_build_query($parameters), 'rechtspersoon');
    }

    /**
     * Returns all details about the given dossier number.
     * @link https://overheid.io/documentatie/kvk#show
     *
     * @param string $dossierNr
     * @param string $subDossierNr Default is "0000"
     * @return array
     * @throws Exception
     */
    public function get($dossierNr, $subDossierNr = '0000')
    {
        return $this->clean($this->request('/api/kvk/' . $dossierNr . '/' . $subDossierNr));
    }

    /**
     * Returns search term suggestions.
     * https://overheid.io/documentatie/kvk#suggest
     *
     * @param string $query
     * @param int $size Number of search results with a maximum of 25.
     * @param array $fields Fields to be returned.
     * @return array
     * @throws Exception
     */
    public function suggest($query, $size = null, $fields = null)
    {
        $parameters = array();
        if (!is_null($size)) {
            $parameters['size'] = $size;
        }
        if (!is_null($fields)) {
            $parameters['fields'] = $fields;
        }
        return $this->request('/suggest/kvk/' . rawurlencode($query) . '?' . http_build_query($parameters));
    }
}
