<?php
require_once __DIR__.'/vendor/autoload.php';

use FBWAScrapper\FBWAScrapper;

$fb = new FBWAScrapper("email", "password", "socks jika ada");
$fb->Login();
print_r($fb->GetPhones());

?>