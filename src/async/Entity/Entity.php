<?php

/**
 * Zaboy lib (http://zaboy.org/lib/)
 *
 * @copyright  Zaboychenko Andrey
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace zaboy\async\Entity;

use zaboy\async\Entity\Store as EntityStore;
use zaboy\async\Entity\Base;

/**
 * Entity
 *
 * @category   async
 * @package    zaboy
 */
class Entity extends Base implements \ArrayAccess
{

    /**
     * @var array
     */
    public $data = [];

    /**
     * Entity constructor.
     *
     * @param array $data
     */
    public function __construct($data = [])
    {
        if (!isset($data[EntityStore::ID])) {
            $data[EntityStore::ID] = $this->makeId();
        }
        $this->setData($data);
    }

    /**
     * Returns the ID of Entity
     *
     * @return mixed
     */
    public function getId()
    {
        $data = $this->getData();
        if (isset($data[EntityStore::ID])) {
            return $data[EntityStore::ID];
        } else {
            throw new \LogicException(
            "ID is not set."
            );
        }
    }

    /**
     * Returns the raw data of Entity
     *
     * @return mix
     * @throws \LogicException
     */
    public function getData()
    {
        if (!isset($this->data)) {
            throw new \RuntimeException(
            "Data is not set."
            );
        }
        return $this->data;
    }

    /**
     *
     * @param mix $data
     * @return \zaboy\async\Entity\Entity
     * @throws \LogicException
     */
    protected function setData($data)
    {
        if (!(is_array($data) && isset($data[EntityStore::ID]) && $this->isId($data[EntityStore::ID]))) {
            throw new \RuntimeException(
            "Wrong data. \$data must be an array with 'id' key."
            );
        }
        return $this->data = $data;
    }

//====================== ArrayAccess  =========================================

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    public function offsetGet($offset)
    {
        if (array_key_exists($offset, $this->data)) {
            return $this->data[$offset];
        }
        throw new \RuntimeException(
        'Key "' . $offset . '" is not exist in $thiis->data array'
        );
    }

}
