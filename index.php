<?php
require_once __DIR__.'/vendor/autoload.php';

use FBWAScrapper\FBWAScrapper;

$fb = new FBWAScrapper("email", "password", "socks jika ada");
$fb->Login();
$groupList = $fb->GetGroupList();

/*
* Group list must an array
*/
$phones = $fb->GetPhones(json_decode($groupList, true));

?>