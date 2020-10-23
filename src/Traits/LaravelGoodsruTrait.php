<?php

namespace Alexmg86\LaravelGoodsru\Traits;

trait LaravelGoodsruTrait
{
    /**
     * Установка номера заказа GoodsRu и номера заказа
     * @param  string|null $shipmentId
     * @param  string|null $orderCode
     */
    public function initOrder(string $shipmentId = null, string $orderCode = null)
    {
        if ($shipmentId) {
            $this->setShipment($shipmentId);
        }

        if ($orderCode) {
            $this->setOrder($orderCode);
        }
    }

    /**
     * Получение конфига
     * @return string
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Получение максимального кол-ва результатов в выдаче
     * @return string
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Получение типов скидок
     * @return array
     */
    public function getDiscount()
    {
        return config('laravel-goodsru.discount');
    }

    /**
     * Получение номера заказа
     * @return string
     */
    public function getOrder()
    {
        return $this->orderCode;
    }

    /**
     * Получение id мерчанта
     * @return string
     */
    public function getMerchantId()
    {
        return $this->merchantId;
    }

    /**
     * Получение название мерчанта
     * @return string
     */
    public function getMerchantName()
    {
        return $this->merchantName;
    }

    /**
     * Получение причины отмены
     * @return array
     */
    public function getReasons()
    {
        return config('laravel-goodsru.reasons');
    }

    /**
     * Получение кодов причин отмены для статуса MERCHANT_CANCELED
     * @return array
     */
    public function getCanceles()
    {
        return config('laravel-goodsru.reasonsCancel');
    }

    /**
     * Получение статусов
     * @return array
     */
    public function getStatuses()
    {
        return config('laravel-goodsru.statuses');
    }

    /**
     * Получение роута для запроса из конфига
     * @param  string $name
     */
    public function getRoute(string $name)
    {
        $this->api_url = $this->api_url . config('laravel-goodsru.routes')[$name];
    }

    /**
     * Получение номера заказа GoodsRu
     * @return string
     */
    public function getShipment()
    {
        return $this->shipmentId;
    }

    /**
     * Установка максимальное кол-во результатов в выдаче
     * @param string $count
     */
    public function setCount(string $count)
    {
        $this->count = $count;
    }

    /**
     * Установка номера заказа
     * @param string $orderCode
     */
    public function setOrder(string $orderCode)
    {
        $this->orderCode = $orderCode;
    }

    /**
     * Установка номера заказа GoodsRu
     * @param string $shipmentId
     */
    public function setShipment(string $shipmentId)
    {
        $this->shipmentId = $shipmentId;
    }

    /**
     * Установка дата отгрузки
     * @param string $shippingDate
     */
    public function setShippingDate(string $shippingDate)
    {
        $this->shippingDate = $shippingDate;
    }
}
