<?php

/**
 * Zaboy lib (http://zaboy.org/lib/)
 *
 * @copyright  Zaboychenko Andrey
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace zaboy\async\Promise\Promise;

use zaboy\async\Promise\Store as PromiseStore;
use zaboy\async\Promise\Promise\Fulfilled as FulfilledPromise;
use zaboy\async\Promise\Promise\Pending as PendingPromise;
use zaboy\async\Promise\Promise\Dependent as DependentPromise;
use zaboy\async\Entity\Entity;
use zaboy\async\Promise\PromiseInterface;

/**
 * RejectedPromise
 *
 */
class Rejected extends PendingPromise
{

    /**
     *
     * @param array $data
     */
    public function __construct($data = [])
    {
        parent::__construct($data);
        $data = $this->getData();

        if (!array_key_exists(PromiseStore::RESULT, $data)) {
            throw new \RuntimeException('REJECT reason  must be retriveed. ID = ' . $this->getId());
        }
        if (!$data[PromiseStore::RESULT] instanceof \Exception) {
            throw new \RuntimeException('RESULT type must be an exception. ID = ' . $this->getId());
        }
        $this[Store::STATE] = PromiseInterface::REJECTED;
        $this[PromiseStore::ON_FULFILLED] = null;
        $this[PromiseStore::ON_REJECTED] = null;
        $this[PromiseStore::PARENT_ID] = null;
    }

}
