<?php

namespace zaboy\test\async\Promise\Pending;

use zaboy\async\Promise\Promise;
use zaboy\async\Promise\PromiseInterface;
use zaboy\async\Promise\Exception\TimeIsOutException;
use zaboy\Di\InsideConstruct;
use zaboy\test\async\Promise\DataProvider;
use zaboy\async\Promise\Exception as PromiseException;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-09-24 at 00:05:36.
 */
class PromiseTest extends DataProvider
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

    //====================== getState(); =======================================

    public function test_getState()
    {
        $promise = new Promise;
        $this->assertEquals(PromiseInterface::PENDING, $promise->getState());
    }

    //====================== wait(); ===========================================
    public function test_wait_false()
    {
        $promise = new Promise;
        $this->assertContainsOnlyInstancesOf(TimeIsOutException::class, [$promise->wait(false)]);
    }

    public function test_wait_true()
    {
        $promise = new Promise;
        $this->setExpectedException(TimeIsOutException::class);
        $promise->wait();
    }

    //====================== resolve(); ========================================
    /**
     * @dataProvider provider_Types()
     */
    public function test_resolve_anyTypes($in)
    {
        $promise = new Promise;
        $promise->resolve($in);
        $this->assertEquals($in, $promise->wait(false));
    }

    //====================== reject(); =========================================
    public function test_reject_by_string()
    {
        $promise = new Promise;
        $promise->reject('foo');
        $this->assertEquals(PromiseInterface::REJECTED, $promise->getState());
        $this->assertStringStartsWith(
                'foo', $promise->wait(false)->getMessage()
        );
        $this->setExpectedException(PromiseException::class);
        $promise->wait();
    }

    public function test_reject_by_Exception()
    {
        $promise = new Promise;
        $promise->reject(new \RuntimeException('foo'));
        $this->assertEquals(PromiseInterface::REJECTED, $promise->getState());
        $this->setExpectedException(\RuntimeException::class, 'foo');
        $promise->wait();
    }

    public function test_reject_by_not_converted_to_string_value()
    {
        $promise = new Promise;
        $promise->reject(['foo']);
        $this->assertStringStartsWith(
                'Reason cannot be converted to string.', $promise->wait(false)->getMessage()
        );
        $this->setExpectedException(\UnexpectedValueException::class);
        $promise->wait();
    }

    //====================== then(); ===========================================
}
