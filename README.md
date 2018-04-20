# ClicksCloud-API
PHP библиотека для работы с тизерной сетью ClicksCloud.net 

Для работы через API вам необходимо получить ключ API для вашего пользователя у вашего персонального менеджера

## Получение баланса пользователя
```php
/**
* @param Int $active = 1
**/
$balance = clickscloud/Api::getBalance();
```

## Получение списка кампаний
```php
/**
* @param Int $active = 1
**/
$list = clickscloud/Api::getCampaignList();
```
В результате выполрнения вы получите массви кампаний
```
[
  [
    "id":       Integer,    // ID Кампании
    "title":    String,     // Название кампании
    "run":      Boolean,    // Запущена ли кампания в текущий момент
    "type":     Integer,    // Тип кампании (0 - кампания с тизерами)
    "utm":      Object,     // Объект описывает макросы кампании
    "views":    Integer,    // Сумарное кол-во показов тизеров кампании
    "clicks":   Integer     // Суммарное кол-во кликов
  ], [...]
]
```

## Добавить новую кампанию
```php
/**
 * @param string $title             Название кампании
 * @param int $run                  Запущена ли кампания
 * @param float $price              Цена по умолчанию
 * @param string $target_country    Список iso кодов стран черезх запятую (RU, KZ)
 * @param string $target_device     Список таргетинга по устройствам (desktop, mobile, tablet)
 * @param string $without_landing   Список лэндингов разделенный через запяту 'hash,hash,hash' )
 * @param string $without_source    Список id источников разделенный через запяту '12,15,1' )
 * @param int $click_limit_per_day  Дневной лимит кликов     
 * @param int $click_limit          Лимит кликов общий
 */
$campaign = clickscloud/Api::createCampaign('Campaign Name', 1, 3.2, 'RU');
```
В результате выполрнения вы получите объкт созданной кампании
```
[
    "id":       Integer,    // ID Кампании
    "title":    String,     // Название кампании
    "run":      Boolean,    // Запущена ли кампания в текущий момент
    "type":     Integer,    // Тип кампании (0 - кампания с тизерами)
    "utm":      Object,     // Объект описывает макросы кампании
    "views":    Integer,    // Сумарное кол-во показов тизеров кампании за весь переид
    "clicks":   Integer     // Суммарное кол-во кликов за весь перид работы кампании
]
```
## Обновить существующую кампанию
```php
/**
 * @param $campaign_id
 * @param array $params [
 *     'title'                  => String,
 *     'run'                    => Int,
 *     'price'                  => Float
 *     'target_country'         => String
 *     'target_device'          => String
 *     'without_landing'        => String   Список лэндингов разделенный через запяту 'hash,hash,hash' )
 *     'without_source'         => String
 *     'click_limit_per_day'    => Int
 *     'click_limit'            => Int
 * ]
 */
$campaign = clickscloud/Api::updateCampaign($campaign_id, ['title' => 'new Campaign Name', 'run' => 0]);
```
В результате выполрнения вы получите обновленный объект кампании
```
[
    "id":       Integer,    // ID Кампании
    "title":    String,     // Название кампании
    "run":      Boolean,    // Запущена ли кампания в текущий момент
    "type":     Integer,    // Тип кампании (0 - кампания с тизерами)
    "utm":      Object,     // Объект описывает макросы кампании
    "views":    Integer,    // Сумарное кол-во показов тизеров кампании за весь переид
    "clicks":   Integer     // Суммарное кол-во кликов за весь перид работы кампании
]
```
## Статистика кампании
```php
/**
* @param int    $campaign_id  Id вашей кампании
* @param String $from         Дата начала статистики в формате d.m.Y
* @param String $to           Дата окончания статистики в формате d.m.Y
**/
$statistic = clickscloud/Api::getCampaignStatistic($campaign_id, '19.04.2018', '20.04.2018');
```
В результате выполрнения вы получите массви статистики по дням
```
[
  [
    "date":         Integer,  // Unixtime даты дня
    "clicks":       Integer,  // Кол-во кликов за день
    "views":        Integer,  // Кол-во показов за день
    "bill":         Float     // Кол-во потраченных денег за день
  ],
  [...]
]
```

## Получиь список тизеров кампании
```php
/**
* @param Integer $campaign_id
* @param Int $run (-1 - все, 0 - остановленные, 1 - активные)
* @param String (d.m.Y) $from (по умолчанию сегодня)
* @param String (d.m.Y) $to (по умолчанию сегодня)
**/
$statistic = clickscloud/Api::getTeaserList($campaign_id, 1);
```
В результате выполрнения вы получите
```
[
    "campaign": Object,        // Объект текущей кампании
    "params": Object,          // Параметры текущего запроса
    "moderate_codes": Object,  // Коды отказов при модерации
    "category": Object,        // Категории тизеров
    "data": [                  // Массив Объектов Тизеров
        [
            "id":           Integer,    // ID Тизера
            "title":        String,     // Заголовок тизера
            "cover":        String,     // URL до превью картинки
            "link":         String,     // Ссылка на переход
            "white":        Boolean,    // true - ссылка чистая, false - домен нахоится в фильтре adBlock
            "category":     Integer,    // Категория тиезра
            "moderate_code":Integer,    // ID кода отказа модерации
            "moderate":     Boolean,    // true - тизер промодерирован, false - тизер стоит на модерации
            "comment":      String,     // Комментарий модератора
            "price":        Float,      // Цена перехода по тизеру
            "run":          Boolean,    // true - тизер запущен
            "active":       Boolean,    // true - тизер активен (не удален)
            "clicks":       Integer,    // Кол-во преходов по тизеру за выбранный промежуток from - to (по умолчанию сегодня)
            "views":        Integer,    // Кол-во показов тизера за выбранный промежуток from - to (по умолчанию сегодня)
            "bill":         Float,      // Кол-во потраченных денег в рубля за выбранный промежуток from - to (по умолчанию сегодня)
            "campaign_id":  Integer     // ID кампании, к которой относится тизер
        ], [...]
    ]
]
```
## Добавить новый тизер в кампанию
```php
/**
* @param Integer $campaign_id
* @param $title String
* @param $link  String Url
* @param $price Float
* @param $image String Base64_encode (without prefix "data:image/TYPE;base64,")
**/
$image = base64_encode(file_get_contents('http://www.prikol.ru/wp-content/gallery/october-2017/kartinki-04102017-001.jpg'));
$teaser = clickscloud/Api::createTeaser($campaign_id, 'Новый тестовыцй тизер, можете отклонить', 'http://ya.ru', 3.2, $image);
```
В результате выполрнения вы получите объект нового тизера
```
[
  "id":           Integer,    // ID Тизера
  "title":        String,     // Заголовок тизера
  "cover":        String,     // URL до превью картинки
  "link":         String,     // Ссылка на переход
  "white":        Boolean,    // true - ссылка чистая, false - домен нахоится в фильтре adBlock
  "category":     Integer,    // Категория тиезра
  "moderate_code":Integer,    // ID кода отказа модерации
  "moderate":     Boolean,    // true - тизер промодерирован, false - тизер стоит на модерации
  "price":        Float,      // Цена перехода по тизеру
  "run":          Boolean,    // true - тизер запущен
  "active":       Boolean,    // true - тизер активен (не удален)
  "type":         Integer,    // ID типа тизеров из списка типов
  "clicks":       Integer,    // Кол-во преходов по тизеру за выбранный промежуток from - to
  "views":        Integer,    // Кол-во показов тизера за выбранный промежуток from - to
  "bill":         Float,      // Кол-во потраченных денег в рубля за выбранный промежуток from - to
  "campaign_id":  Integer     // ID кампании, к которой относится тизер
]
```

## Отредактировать тизер
```php
/**
* @param Integer $campaign_id
* @param Integer $teaser_id
* @param array $params = [title => String, $link => String, price => Float, image => String (without prefix "data:image/TYPE;base64,")]
**/
$image = base64_encode(file_get_contents('http://www.prikol.ru/wp-content/gallery/october-2017/kartinki-04102017-001.jpg'));
$teaser = clickscloud/Api::updateTeaser($campaign_id, $teaser['id'], ['title' => 'Новый заголовок']);
```
В результате выполрнения вы получите объект нового тизера

## Статистика тизера
```php
/**
* @param Integer $campaign_id
* @param Integer $teaser_id
* @param String (d.m.Y) $from (по умолчанию сегодня)
* @param String (d.m.Y) $to (по умолчанию сегодня)
**/
$statistic = clickscloud/Api::updateTeaser($campaign_id, $teaser['id']);
```
В результате выполрнения вы получите массви статистики по дням
```
[
  [
    "date":         Integer,  // Unixtime даты дня
    "clicks":       Integer,  // Кол-во кликов за день
    "views":        Integer,  // Кол-во показов за день
    "bill":         Float     // Кол-во потраченных денег за день
  ],
  [...]
]
```

## Отключить\Включить тизер
```php
/**
* @param Integer $campaign_id
* @param Integer $teaser_id
* @param Int $run (0 - отключить, 1 включить)
**/
$statistic = clickscloud/Api::teaserToggle($campaign_id, $teaser['id'], 1);
```
В результетае выполнения вы получите объект
```
[
  'success': Bolean,    // Результат выполнения операции
  'run': Bolean,        // Новое состояние тизера
  'id': Integer         // Id тизера
]
```

## Сменить цену CPC тизера
```php
/**
* @param Integer $campaign_id
* @param Integer $teaser_id
* @param Float   $price
**/
$statistic = clickscloud/Api::teaserTogglePrice($campaign_id, $teaser['id'], 10);
```
В результетае выполнения вы получите объект
```
[
  'success': Bolean,    // Результат выполнения операции
  'price': Float,       // Новая цена
  'id': Integer         // Id тизера
]
```
