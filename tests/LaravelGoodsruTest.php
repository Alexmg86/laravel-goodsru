<?php

namespace Alexmg86\LaravelGoodsru\Tests;

use Alexmg86\LaravelGoodsru\Facades\LaravelGoodsru;
use Alexmg86\LaravelGoodsru\LaravelGoodsru as LaravelGoodsruApi;
use Alexmg86\LaravelGoodsru\ServiceProvider;
use Orchestra\Testbench\TestCase;

class LaravelGoodsruTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [
            'laravel-goodsru' => LaravelGoodsru::class,
        ];
    }

    public function testOrder()
    {
        $api = new LaravelGoodsruApi();
        $api->setOrder(111);

        $this->assertEquals(111, $api->getOrder());
    }

    public function testShipment()
    {
        $api = new LaravelGoodsruApi();
        $api->setShipment(111);

        $this->assertEquals(111, $api->getShipment());
    }

    public function testInitOrder()
    {
        $api = new LaravelGoodsruApi();
        $api->initOrder(111, 222);

        $this->assertEquals(111, $api->getShipment());
        $this->assertEquals(222, $api->getOrder());
    }

    public function testShipmentAndOrder()
    {
        $api = new LaravelGoodsruApi(111, 222);

        $this->assertEquals(111, $api->getShipment());
        $this->assertEquals(222, $api->getOrder());
    }
}
