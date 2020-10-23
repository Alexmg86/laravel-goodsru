# Laravel GoodsRu API

![PHP Composer](https://github.com/Alexmg86/laravel-sub-query/workflows/PHP%20Composer/badge.svg)
[![Latest Stable Version](https://poser.pugx.org/alexmg86/laravel-goodsru/v/stable)](https://packagist.org/packages/alexmg86/laravel-goodsru)
[![License](https://poser.pugx.org/alexmg86/laravel-goodsru/license)](https://packagist.org/packages/alexmg86/laravel-goodsru)

## Для чего нужен пакет

Данный пакет предназначен для работы с API сервиса GoodsRu в Laravel.

## Понравился?

Если вам понравился пакет, то можете поставить мне звезду 🙏 😌

## Установка

Установка через composer
```bash
composer require alexmg86/laravel-goodsru
```
Добавить и заполните переменные в .ENV файл
```bash
GOODSRU_URL= //https://site_goodsru.ru/api/market/v1/orderService
GOODSRU_TOKEN=
GOODSRU_LOGIN=
GOODSRU_PASSWORD=
GOODSRU_MERCHANT_ID=
GOODSRU_MERCHANT_NAME=
```
Если вам нужно изменить конфигурацию, то добавьте ее в папку с конфигами
```php
php artisan vendor:publish --provider="Alexmg86\LaravelGoodsru\ServiceProvider"
```

## Использование

### Инициализация
Инициализировать можно несколькими способами:
```php
use Alexmg86\LaravelGoodsru\LaravelGoodsru;

$api = new LaravelGoodsru();
```
можно сразу указать `$shipmentId` и `$orderCode`
```php
$api = new LaravelGoodsru($shipmentId, $orderCode);
```
можно указать `$shipmentId` и `$orderCode` в любой момент работы
```php
$api = new LaravelGoodsru();
$api->initOrder($shipmentId, $orderCode);
```
или установить их по отдельности
```php
$api = new LaravelGoodsru();
$api->setOrder($orderCode);
$api->setShipment($shipmentId);
```

### Запросы

#### get
Получение подробной информации об отправлениях.  
Можно указать как один, так и массив `$shipmentId`.
```php
$api = new LaravelGoodsru();
$data = $api->get($shipmentId);
$data = $api->get([$shipmentId1, $shipmentId2]);
```
#### confirm
Подтверждение лотов в отправлении со стороны продавца.  
Указываем `$shipmentId` и передаем массив `$itemIndex => $goodId`.
```php
$api = new LaravelGoodsru();
$api->setShipment($shipmentId);
$data = $api->confirm([1 => 1111, 2 => 2222]);
```
#### reject
Отмена лотов в отправлении со стороны продавца.  
Указываем `$shipmentId` и передаем массив `$itemIndex => $goodId`.  
Можно указать причину отмены `$reason`. По умолчанию будет передано `OUT_OF_STOCK`.
```php
$api = new LaravelGoodsru();
$api->setShipment($shipmentId);
$data = $api->reject([1 => 1111, 2 => 2222], $reason);
```
Получить список доступных статусов.
```php
$reasons = $api->getReasons();
```
#### packing
Подтверждение комплектации продавцом.  
Указываем `$shipmentId` и `$orderCode` и передаем массив `$itemIndex => $boxIndex`.  
`$boxIndex` участвует в формирование штрихкода (boxCode).
```php
$api = new LaravelGoodsru();
$api->initOrder($shipmentId, $orderCode);
$data = $api->packing([1 => 1, 2 => 1]);
```
#### print
Получение этикетки.  
Указываем `$shipmentId` и передаем массив `$boxIndex`.  
```php
$api = new LaravelGoodsru();
$api->setShipment($shipmentId);
$data = $api->print([1, 2]);
```
#### shipping
Подтверждение отгрузки продавцом.  
Указываем `$shipmentId`, `$shippingDate` и передаем массив `$boxIndex`.  
```php
$api = new LaravelGoodsru();
$api->setShipment($shipmentId);
// формат YYYY-MM-DDThh:mm:ss+hh:mm
$api->setShippingDate($shippingDate);
$data = $api->shipping([1, 2]);
```
#### search
Поиск по отправлениям.  
Передаем `$period` равный количеству дней до текущей даты.  
Необязательный массив `$statuses` для поиска по определенным статусам. По умолчанию поиск будет по всем статусам.  
```php
$api = new LaravelGoodsru();
$data = $api->search($period, $statuses);
```
Список доступных статусов.
```php
$api->getStatuses();
```
Так же можно дополнительно искать по `$orderCode`, `$shippingDate`.
```php
$api->setOrder($orderCode);
// формат YYYY-MM-DDThh:mm:ss+hh:mm
$api->setShippingDate($shippingDate);
```
По-умолчанию будет отдано 100 записей. Максимальное количество можно указать в файле конфигурации `count` либо задать в любой момент.
```php
$api->setCount(200);
```

### Дополнительный методы
Получение конфига.
```php
$api->getConfig();
```
Получение и установка максимального количества результатов в выдаче.
```php
$api->getCount();
$api->setCount(200);
```
Получение кодов типов скидок.
```php
$api->getDiscount();
```
Получение и установка номера заказа.
```php
$api->getOrder();
$api->setOrder($orderCode);
```
Получение id мерчанта.
```php
$api->getMerchantId();
```
Получение название мерчанта.
```php
$api->getMerchantName();
```
Получение кодов причин отмены.
```php
$api->getReasons();
```
Получение кодов причин отмены для статуса MERCHANT_CANCELED.
```php
$api->getCanceles();
```
Получение кодов статусов.
```php
$api->getStatuses();
```
Получение и установка номера заказа GoodsRu.
```php
$api->getShipment();
$api->setShipment($shipmentId);
```
Установка дата отгрузки.
```php
$api->setShippingDate($shippingDate);
```
