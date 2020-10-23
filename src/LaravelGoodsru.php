<?php

namespace Alexmg86\LaravelGoodsru;

use Alexmg86\LaravelGoodsru\Traits\LaravelGoodsruTrait;

class LaravelGoodsru
{
    use LaravelGoodsruTrait;

    // Переменные для доступов
    protected $api_url;
    protected $token;
    protected $login;
    protected $password;
    protected $merchantId;
    protected $merchantName;
    protected $curl;

    // Переменные для запросов
    protected $count;
    protected $orderCode;
    protected $shipmentId;
    protected $shippingDate;

    public function __construct(string $shipmentId = null, string $orderCode = null)
    {
        $this->initSettings();
        $this->initCurl();
        $this->initOrder($shipmentId, $orderCode);
    }

    /**
     * Инициализация настроек
     */
    private function initSettings()
    {
        $this->api_url      = env('GOODSRU_URL', '');
        $this->token        = env('GOODSRU_TOKEN', '');
        $this->login        = env('GOODSRU_LOGIN', '');
        $this->password     = env('GOODSRU_PASSWORD', '');
        $this->merchantId   = env('GOODSRU_MERCHANT_ID', '');
        $this->merchantName = env('GOODSRU_MERCHANT_NAME', '');
        $this->count        = config('laravel-goodsru.count');
    }

    /**
     * Инициализация cURL запроса
     */
    private function initCurl()
    {
        $this->curl = curl_init();
        curl_setopt_array($this->curl, config('laravel-goodsru.curl'));
    }

    /**
     * Получение подробной информации об отправлениях
     * @param  array|int  $values
     * @return array
     */
    public function get($shipments = null)
    {
        $this->getRoute('get');

        $shipmentsIds = [];

        if ($shipments) {
            $shipmentsIds = is_array($shipments) ? $shipments : [$shipments];
        } elseif ($this->shipmentId) {
            $shipmentsIds = [$this->shipmentId];
        }

        $values = [
            'shipments' => $shipmentsIds
        ];

        return $this->sendRequest($values);
    }

    /**
     * Подтверждение лотов в Отправлении со стороны Продавца
     * @param  array  $items
     * @return array
     */
    public function confirm(array $items)
    {
        $this->getRoute('confirm');

        $values = [
            'shipments' => [
                [
                    'shipmentId' => $this->shipmentId,
                    'items'      => $this->makeItems($items)
                ]
            ]
        ];

        return $this->sendRequest($values);
    }

    /**
     * Отмена Лотов в Отправлении со стороны Продавца
     * @param  array  $items
     * @param  string $reason
     * @return array
     */
    public function reject(array $items, $reason = 'OUT_OF_STOCK')
    {
        $this->getRoute('reject');

        $values = [
            'shipments' => [
                [
                    'shipmentId' => $this->shipmentId,
                    'items'      => $this->makeItems($items)
                ]
            ],
            'reason' => [
                'type' => $reason
            ]
        ];

        return $this->sendRequest($values);
    }

    /**
     * Подтверждение комплектации Продавцом
     * @param  array  $items
     * @return array
     */
    public function packing(array $items)
    {
        $this->getRoute('packing');

        $values = [
            'shipments' => [
                [
                    'shipmentId' => $this->shipmentId,
                    'orderCode'  => $this->orderCode,
                    'items'      => $this->makeBoxes($items)
                ]
            ]
        ];

        return $this->sendRequest($values);
    }

    /**
     * Подтверждение комплектации Продавцом
     * @param  array  $items
     * @return array
     */
    public function print(array $items)
    {
        $this->getRoute('print');

        $values = [
            'shipmentId' => $this->shipmentId,
            'boxCodes'   => $this->makeBoxesCodes($items)
        ];

        return $this->sendRequest($values);
    }

    /**
     * Поиск по отправлениям
     * @param  int        $period
     * @param  array|null $statuses
     * @return array
     */
    public function search(int $period, array $statuses = null)
    {
        $this->getRoute('search');

        $values = [
            'dateFrom' => \Carbon\Carbon::now()->subDays($period)->startOfDay()->format('Y-m-d\TH:i:s'),
            'dateTo'   => \Carbon\Carbon::now()->format('Y-m-d\TH:i:s'),
            'count'    => $this->getCount()
        ];

        if ($this->shippingDate) {
            $values['shippingDate'] = $this->shippingDate;
        }

        if ($this->orderCode) {
            $values['orderCode'] = $this->orderCode;
        }

        if ($statuses) {
            $values['statuses'] = $statuses;
        }

        return $this->sendRequest($values);
    }

    /**
     * Подтверждение отгрузки Продавцом
     * @param  array  $items
     * @return array
     */
    public function shipping(array $items)
    {
        $this->getRoute('shipping');

        $values = [
            'shipments' => [
                [
                    'shipmentId' => $this->shipmentId,
                    'boxes'      => $this->makeBoxesItems($items),
                    'shipping'   => [
                        'shippingDate' => $this->shippingDate
                    ]
                ]
            ]
        ];

        return $this->sendRequest($values);
    }

    /**
     * Формирования массива товаров и грузовых мест
     * @param  array  $items
     * @return array
     */
    private function makeBoxes(array $items)
    {
        $data = [];
        foreach ($items as $itemIndex => $boxIndex) {
            $data[] = [
                'itemIndex' => $itemIndex,
                'quantity'  => 1,
                'boxes'     => $this->makeBoxesItems([$boxIndex])
            ];
        }
        return $data;
    }

    /**
     * Формирование кода грузового места
     * @param  string $boxIndex
     * @return string
     */
    private function makeBoxesCode(string $boxIndex)
    {
        return $this->merchantId . '*' . $this->orderCode . '*' . $boxIndex;
    }

    /**
     * Формирование кодов грузовых мест
     * @param  array  $boxes
     * @return array
     */
    private function makeBoxesCodes(array $boxes)
    {
        $data = [];
        foreach ($boxes as $boxIndex) {
            $data[] = $this->makeBoxesCode($boxIndex);
        }
        return $data;
    }

    /**
     * Формирования массива грузовых мест
     * @param  array  $boxes
     * @return array
     */
    private function makeBoxesItems(array $boxes)
    {
        $data = [];
        foreach ($boxes as $boxIndex) {
            $data[] = [
                'boxIndex' => $boxIndex,
                'boxCode' => $this->makeBoxesCodes($boxIndex)
            ];
        }
        return $data;
    }

    /**
     * Формирования массива товаров
     * @param  array  $items
     * @return array
     */
    private function makeItems(array $items)
    {
        $data = [];
        foreach ($items as $itemIndex => $goodId) {
            $data[] = [
                'itemIndex' => $itemIndex,
                'offerId' => (string)$goodId
            ];
        }
        return $data;
    }

    /**
     * Отправка запроса на GoodsRu
     * @param  array  $values
     * @return array
     */
    private function sendRequest(array $values)
    {
        $data = [
            'data' => [
                'token' => $this->token
            ],
            'meta' => []
        ];

        foreach ($values as $key => $value) {
            if ($key != 'token') {
                $data['data'][$key] = $value;
            }
        }

        curl_setopt_array($this->curl, [
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_URL => $this->api_url
        ]);

        try {
            $response = curl_exec($this->curl);
            $response = json_decode($response, true);
        } catch (\Exception $e) {
            return "Curl return error: " . $e->getMessage();
        }

        return $response;
    }
}
