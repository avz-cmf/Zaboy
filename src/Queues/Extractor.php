<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 04.01.17
 * Time: 11:56
 */

namespace zaboy\Queues;

use Xiag\Rql\Parser\Query;
use zaboy\Callback\Interruptor\Job;
use zaboy\Callback\Interruptor\Process;
use zaboy\Callback\InterruptorInterface;
use zaboy\Callback\Promiser;
use zaboy\Callback\PromiserInterface;

class Extractor
{

    /** @var QueueInterface */
    protected $queue;

    /**
     * Extractor constructor.
     * @param QueueInterface $queue
     */
    public function __construct(QueueInterface $queue)
    {
        $this->queue = $queue;
    }

    /**
     * Extract queue and call callback
     * @return bool
     * @throws QueueException
     */
    public function extract()
    {
        $message = $this->queue->getMessage();
        if (isset($message)){
            $job = Job::unserializeBase64($message->getData());
            try{
                call_user_func($job->getCallback(), $job->getValue());
            } catch (\Exception $e) {
                throw new QueueException("Extract queue error!", 500, $e);
            }
            return true;
        }
        return false;
    }
}
