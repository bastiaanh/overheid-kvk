<?php

namespace Overheid;

/**
 * Overheid.io API resultset class.
 *
 * @author bastiaanh
 * @package overheid-kvk
 */
class Resultset extends Base implements \Countable, \Iterator
{
    /**
     * @var Base
     */
    protected $base;

    /**
     * @var array
     */
    protected $response;

    /**
     * @var string
     */
    protected $entity;

    /**
     * Page number currently loaded in response.
     *
     * @var int
     */
    protected $page = 0;

    /**
     * Position within current page.
     *
     * @var int
     */
    protected $position = 0;

    /**
     * Resultset constructor. Protected so it can only be called by methods of other Base objects.
     * Currently used only by \Overheid\Base::requestSet
     *
     * @param Base $base
     * @param array $firstPageResponse
     * @param string $entity
     */
    protected function __construct(Base $base, $firstPageResponse, $entity)
    {
        $this->base     = $base;
        $this->response = $firstPageResponse;
        $this->entity   = $entity;
    }

    /**
     * Returns the total number of items. Implementation of the Countable interface.
     *
     * @return int
     */
    public function count()
    {
        return $this->response['totalItemCount'];
    }

    /**
     * Return the current element. Implementation of the Iterator interface.
     *
     * @return mixed
     */
    public function current()
    {
        return $this->base->clean($this->response['_embedded'][$this->entity][$this->position]);
    }

    /**
     * Return the key of the current element. Implementation of the Iterator interface.
     *
     * @return int
     */
    public function key()
    {
        return ($this->page * $this->response['size']) + $this->position;
    }

    /**
     * Move forward to next element. Implementation of the Iterator interface.
     */
    public function next()
    {
        $this->position++;
        if ($this->position >= $this->response['size']) {
            $this->response = $this->base->request($this->response['_links']['next']['href']);
            $this->page++;
            $this->position = 0;
        }
    }

    /**
     * Rewind the Iterator to the first element. Implementation of the Iterator interface.
     */
    public function rewind()
    {
        if ($this->page) {
            $this->response = $this->base->request($this->response['_links']['first']['href']);
            $this->page = 0;
        }
        $this->position = 0;
    }

    /**
     * Checks if current position is valid. Implementation of the Iterator interface.
     *
     * @return bool
     */
    public function valid()
    {
        return ($this->position >= 0 && $this->position < $this->response['size'] && $this->key() < $this->count());
    }
}
