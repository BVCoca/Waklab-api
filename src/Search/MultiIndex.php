<?php

/*
 * Created by Exploit.cz <insekticid AT exploit.cz>
 */

namespace App\Search;

use Elastica\Exception\InvalidException;
use Elastica\Index;
use Elastica\ResultSet\BuilderInterface;
use Elastica\Search;

class MultiIndex extends Index
{
    /**
     * Array of indices.
     *
     * @var array
     */
    protected $_indices = [];

    /**
     * Adds a index to the list.
     *
     * @param \Elastica\Index|string $index Index object or string
     *
     * @return $this
     *
     * @throws \Elastica\Exception\InvalidException
     */
    public function addIndex($index)
    {
        if ($index instanceof Index) {
            $index = $index->getName();
        }

        if (!is_scalar($index)) {
            throw new InvalidException('Invalid param type');
        }

        $this->_indices[] = (string) $index;

        return $this;
    }

    /**
     * Add array of indices at once.
     *
     * @return $this
     */
    public function addIndices(array $indices = [])
    {
        foreach ($indices as $index) {
            $this->addIndex($index);
        }

        return $this;
    }

    /**
     * Set array of indices.
     *
     * @return $this
     */
    public function setIndices(array $indices = [])
    {
        $this->_indices = $indices;
    }

    /**
     * Return array of indices.
     *
     * @return array List of index names
     */
    public function getIndices()
    {
        return $this->_indices;
    }

    /**
     * @param string|array|\Elastica\Query $query
     * @param int|array                    $options
     */
    public function createSearch($query = '', $options = null, BuilderInterface $builder = null): Search
    {
        $search = new Search($this->getClient(), $builder);
        $search->addIndices($this->getIndices());
        $search->setOptionsAndQuery($options, $query);

        return $search;
    }
}
