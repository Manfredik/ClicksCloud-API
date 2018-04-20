<?php
include('Api.php');
clickscloud\Api::$KEY   = 'Enter Your key';
clickscloud\Api::$ID    = 'Enter Your id';

/**
 * Получить список кампаний
 */
$list = clickscloud\Api::getCampaignList(true);

/*
 * Добавить тизер в кампанию
 * */
$image = base64_encode(file_get_contents('http://www.prikol.ru/wp-content/gallery/october-2017/kartinki-04102017-001.jpg'));
$newTeaser = clickscloud\Api::createTeaser($campaign_id, 'Новый способо избавиться от ...', 'http://yandex.ru', 5, file_get_contents(), $image);

/*
 * Сменить цену
 * */
$data = clickscloud\Api::getTeaserTogglePrice($campaign_id, $newTeaser['id'], 3.2);

/*
 * Остановить тизер
 * */
$data = clickscloud\Api::teaserToggle($campaign_id, $newTeaser['id'], 0);



?>

