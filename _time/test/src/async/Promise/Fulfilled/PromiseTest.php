<?php

namespace zaboy\test\async\Promise\Fulfilled;

use zaboy\async\Promise\Promise;
use zaboy\async\Promise\PromiseInterface;
use zaboy\async\Promise\Promise\Pending as PendingPromise;
use zaboy\async\Promise\TimeIsOutException;
use zaboy\async\Promise\RejectedException;
use zaboy\Di\InsideConstruct;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-09-24 at 00:05:36.
 */
class PromiseTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Promise
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $container = include 'config/container.php';
        InsideConstruct::setContainer($container);
    }

    //********************** @dataProvider *************************************
    public function provider_Types()
    {
        return [
            array(false),
            array(-12345),
            array('foo'),
            array([1, 'foo', [], false]),
            array(new \stdClass()),
            array(new \LogicException('bar')),
        ];
    }

    //====================== getState(); =======================================
    public function test_getState()
    {
        $promise = new Promise;
        $promise->resolve('foo');
        $this->assertEquals(PromiseInterface::FULFILLED, $promise->getState());
    }

    //====================== wait(); ===========================================
    public function test_wait_false()
    {
        $promise = new Promise;
        $promise->resolve('foo');
        $this->assertEquals('foo', $promise->wait(false));
    }

    public function test_wait_true()
    {
        $promise = new Promise;
        $promise->resolve('foo');
        $this->assertEquals('foo', $promise->wait(false));
    }

    //====================== resolve(); ========================================
    /**
     * @dataProvider provider_Types()
     */
    public function test_resolve_same_val_twice($in)
    {
        $promise = new Promise;
        $promise->resolve($in);
        $promise->resolve($in);
        $this->assertEquals($in, $promise->wait(false));
    }

    /**
     * @dataProvider provider_Types()
     */
    public function test_resolve_anyTypes($in)
    {
        $promise = new Promise;
        $promise->resolve('bar');
        $this->setExpectedExceptionRegExp(\RuntimeException::class, '|.*The promise is already fulfilled.*|');
        $promise->resolve($in);
    }

    //====================== reject(); =========================================
    //====================== then(); ===========================================
}
