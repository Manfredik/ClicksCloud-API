<?php
/**
 * Example Api for ClicksCloud.net
 * User:    manfredi aka Malyavkind Denis
 * Email:   denis@spacepush.ru
 * Date:    20.04.2018
 */

class Api
{
    public static
        $URL    = "http://clients.clickscloud.net/api",
        $ID     = "Enter Your key",
        $KEY    = "Enter Your id";

    /**
     * Получить список кампаний
     * @param bool $active
     * @return mixed
     * @throws Exception
     */
    public static function getCampaignList($active = true){
        return static::request('campaign/index', ['active' => $active])['campaigns'];
    }

    /**
     * Получить статистику кампании
     * @param int $campaign_id
     * @param String $from (d.m.Y)
     * @param String $to (d.m.Y)
     * @return array
     * @throws Exception
     */
    public static function getCampaignStatistic($campaign_id, $from = null, $to = null) {
        return static::request('campaign/statistic', ['$campaign_id' => (int)$campaign_id, 'from' => $from, 'to' => $to ]);
    }

    /**
     * @param $title
     * @param int $run
     * @param float $price
     * @param string $country
     * @param string $device
     * @param int $clpd
     * @param int $cl
     * @return array
     * @throws Exception
     */
    public static function createCampaign($title, $run = 1, $price = 5.0, $country = "RU, KZ, UZ, BY, UA", $device = "", $clpd = 0, $cl = 0){
        return static::request('campaign/statistic',
            ['title' => $title, 'run' => (int) $run,'price' => (float) $price,
                'target_country' => (string) $country, 'target_device' => $device,
                'click_limit_per_day' => (int) $clpd, 'click_limit' => $cl])['campaign'];
    }

    /**
     * Обновить параметры кампании
     * @param $campaign_id
     * @param array $params [
     *     'title'                  => String,
     *     'run'                    => Int,
     *     'price'                  => Float
     *     'target_country'         => String
     *     'target_device'          => String
     *     'click_limit_per_day'    => Int
     *     'click_limit'            => Int
     * ]
     * @return mixed
     * @throws Exception
     */
    public static function updateCampaign($campaign_id, array $params = ['run' => 1, 'price' => 4.99]){
        return static::request('campaign/statistic',
            array_merge(['campaign_id' => $campaign_id], $params))['campaign'];
    }

    /**
     * Получить список тизеров кампании
     * @param Integer $campaign_id
     * @param Int $run (-1 - все, 0 - остановленные, 1 - активные)
     * @param String (d.m.Y) $from
     * @param String (d.m.Y) $to
     * @return array
     * @throws Exception
     */
    public static function getTeaserList($campaign_id, $run = -1,$from = null, $to = null) {
        return static::request('campaign/list', ['campaign_id' => (int)$campaign_id, 'from' => $from, 'to' => $to, 'run' => $run ]);
    }

    /**
     * @param Int $campaign_id
     * @param Int $teaser_id
     * @param Int $run (0 - отключить, 1 включить)
     * @return boolean
     * @throws Exception
     */
    public static function teaserToggle($campaign_id, $teaser_id, $run ) {
        return static::request('campaign/toggle-teaser', ['campaign_id' => (int)$campaign_id, 'teaser_id' => (int) $teaser_id, 'run' => (int) $run ]);
    }
    /**
     * @param Int $campaign_id
     * @param Int $teaser_id
     * @param Float $price
     * @return boolean
     * @throws Exception
     */
    public static function teaserTogglePrice($campaign_id, $teaser_id, $price) {
        return static::request('campaign/toggle-price', ['campaign_id' => (int)$campaign_id, 'teaser_id' => (int) $teaser_id, 'price' => (float) $price ]);
    }

    /**
     * Добавить новый тизер в кампанию
     * @param $campaign_id
     * @param $title String
     * @param $link  String Url
     * @param $price Float
     * @param $image String Base64_encode (without prefix "data:image/TYPE;base64,")
     * @return mixed
     * @throws Exception
     */
    public static function createTeaser($campaign_id, $title, $link, $price, $image)
    {
        return static::request('campaign/create-teaser',
            ['campaign_id' => (int) $campaign_id, 'title' => $title, 'link' => $link, 'price' => (float) $price, 'image' => $image ])['teaser'];
    }
    /**
     * @param Integer $campaign_id
     * @param Integer $teaser_id
     * @param array $params = [title => String, $link => String, price => Float, image => String (without prefix "data:image/TYPE;base64,")]
     * @return Boolean
     * @throws Exception
     */
    public static function updateTeaser($campaign_id, $teaser_id, $params = [])
    {
        return static::request('campaign/update-teaser',
            array_merge(['campaign_id' => (int) $campaign_id, 'teaser' => (int) $teaser_id ]), $params)['teaser'];
    }

    /**
     * @param $campaign_id
     * @param $teaser_id
     * @param DateFormat(d.m.Y) $from
     * @param DateFormat(d.m.Y) $to
     * @return array
     * @throws Exception
     */
    public static function getTeaserStatistic($campaign_id, $teaser_id, $from = null, $to = null) {
        return static::request('campaign/teaser-statistic',
            ['campaign_id' => (int) $campaign_id, 'teaser_id' => (int) $teaser_id, 'from' => $from, 'to' => $to ])['data'];
    }

    public static function getBalance()
    {
        return static::request('balance/index');
    }


    public static function request($method, $params = []){
        $ch = curl_init();
        $params = array_merge(array_filter($params), ['id' => static::$ID, 'sig' => static::getSig(array_filter($params))]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_URL, implode('/', [static::$URL, $method]));
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt_array($ch, static::getCurlParams());

        $response = json_decode(curl_exec($ch), true);
        curl_close($ch);
        if ($response['status'] == 200) {
            return $response['response'];
        } else {
            throw new Exception($response['response']['error']);
        }
    }

    protected static function getCurlParams() {
        return [
            CURLOPT_POST  => true,
            CURLOPT_RETURNTRANSFER  => true,
        ];
    }

    public static function getSig($params = []) {
        // Сортируем массив передаваемых параметров в алфовитном порядке
        ksort($params);
        // В начало строки добавляем ваш ID
        $string = static::$ID;
        // Пробегаем по списку параметров для конкатенации строки
        foreach ($params as $k => $v) {
            // Если в списке параметров встретится параметр id или sig пропустим их
            if (in_array($k, ['sig', 'id'])) {
                continue;
            }
            // Конкатенируем строку
            $string .=  $k.'='.$v;
        }
        // Добавляем к строке секретный ключ
        $string .= static::$KEY;
        // возвращаем md5 hash от этой строки
        return md5($string);
    }

}