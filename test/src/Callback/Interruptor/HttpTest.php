<?php
/**
 * Created by PhpStorm.
 * User: victorsecuring
 * Date: 30.12.16
 * Time: 5:17 PM
 */

namespace zaboy\test\Interruptor\Callback;


use zaboy\Callback\Interruptor\Http;
use zaboy\Callback\Interruptor\InterruptorAbstract;
use zaboy\Callback\Interruptor\Process;
use zaboy\test\Callback\CallbackTestDataProvider;


class HttpTest extends CallbackTestDataProvider
{

    protected $url;

    public function setUp()
    {
        $container = include 'config/container.php';
        $this->url = $container->get('config')['httpInterruptor']['url'];
    }

    /**
     * @param $callable
     * @param $val
     * @param $expected
     * @dataProvider provider_mainType()
     */
    public function test_httpInterruptorCallback($callable, $val, $expected)
    {
        $httpInterraptor = new Http($callable, $this->url);
        $result = $httpInterraptor($val);
        $this->assertTrue(isset($result['data']));
        $this->assertTrue(isset($result[InterruptorAbstract::MACHINE_NAME_KEY]));
        $this->assertTrue(isset($result[InterruptorAbstract::INTERRUPTOR_TYPE_KEY]));
    }

    /**
     * @param $callable
     * @param $val
     * @param $expected
     * @dataProvider provider_mainType()
     */
    public function test_httpInterruptorInterruptor($callable, $val, $expected)
    {
        $callable = new Process($callable);
        $httpInterraptor = new Http($callable, $this->url);
        $result = $httpInterraptor($val);
        $this->assertTrue(isset($result['data']));
        $this->assertTrue(isset($result[InterruptorAbstract::MACHINE_NAME_KEY]));
        $this->assertEquals(Http::class, $result[InterruptorAbstract::INTERRUPTOR_TYPE_KEY]);
    }
}
