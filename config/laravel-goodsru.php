<?php

return [
    // phpcs:disable

    // Первичные настройки cURL запроса
    'curl' => [
        CURLOPT_POST => true,
        CURLOPT_TIMEOUT_MS => 2000,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_PROTOCOLS => CURLPROTO_HTTPS,
        CURLOPT_HTTPHEADER => [
            'Accept: application/json',
            'Content-Type: application/json'
        ],
        CURLOPT_USERPWD => env('GOODSRU_LOGIN') . ':' . env('GOODSRU_PASSWORD')
    ],

    'routes' => [
        'get' => '/order/get',
        'confirm' => '/order/confirm',
        'reject' => '/order/reject',
        'packing' => '/order/packing',
        'search' => '/order/search',
        'shipping' => '/order/shipping',
        'print' => '/sticker/print'
    ],

    // Количество id заказов по-умолчанию для запроса /order/search
    'count' => 100,

    // Типы скидок
    'discount' => [
        'BPG' => 'Гарантия Лучшей Цены (ГЛЦ)',
        'LOY' => 'Программа Лояльности (ПЛ)'
    ],

    // Причины отмены
    'reasons' => [
        'OUT_OF_STOCK' => 'Нет товара в наличии',
        'INCORRECT_PRICE' => 'Некорректная цена товара',
        'INCORRECT_PRODUCT' => 'Некорректная привязка товара к Офферу',
        'INCORRECT_SPEC' => 'Некорректные характеристики Товара на Goods',
        'TWICE_ORDER' => 'Два Заказа на один и тот же товар, дубль заказа',
        'NOT_TIME_FOR_SHIPPING' => 'Недостаточно времени на отгрузку',
        'FRAUD_ORDER' => 'Фродовый заказ'
    ],

    // Коды причин отмены для статуса MERCHANT_CANCELED
    'reasonsCancel' => [
        'CONFIRMATION_REJECT' => 'Продавец отклонил заказ',
        'CONFIRMATION_EXPIRED' => 'Заказ отменён, так как Продавец не подтвердил заказ вовремя',
        'LATE_REJECT' => 'Продавец отменил заказ после подтверждения',
        'PACKING_EXPIRED' => 'Заказ отменён, так как Продавец не подтвердил комплектацию заказа вовремя',
        'SHIPPING_EXPIRED' => 'Заказ отменён, так как Продавец не подтвердил отгрузку заказа вовремя (и не подтвердил ее в течение 7 дней после окончания тайм-лимита на отгрузку)'
    ],

    // Доступные статсы для запроса /order/search
    'statuses' => [
        'NEW' => 'Ожидает подтверждения',
        'CONFIRMED' => 'Ожидает комплектации',
        'PACKED' => 'Ожидает отгрузки',
        'SHIPPED' => 'Отгружен продавцом',
        'DELIVERED' => 'Доставлен покупателю',
        'MERCHANT_CANCELED' => 'Отменен Продавцом',
        'CUSTOMER_CANCELED' => 'Отменен покупателем'
    ]
    // phpcs:enable
];
