<?php
include('Api.php');
Api::$KEY   = 'Enter Your key';
Api::$ID    = 'Enter Your id';

/**
 * Получить список кампаний
 */
$list = Api::getCampaignList(true);

/*
 * Добавить тизер в кампанию
 * */
$image = base64_encode(file_get_contents('http://www.prikol.ru/wp-content/gallery/october-2017/kartinki-04102017-001.jpg'));
$newTeaser = Api::createTeaser($campaign_id, 'Новый способо избавиться от ...', 'http://yandex.ru', 5, file_get_contents(), $image);

/*
 * Сменить цену
 * */
$data = Api::getTeaserTogglePrice($campaign_id, $newTeaser['id'], 3.2);

/*
 * Остановить тизер
 * */
$data = Api::teaserToggle($campaign_id, $newTeaser['id'], 0);



?>

