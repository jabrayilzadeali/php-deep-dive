<?php

define('BASE_PATH', __DIR__);
require_once BASE_PATH . "/resources/views/layouts/layout.php";

require_once "helpers.php";
$okay = '-----big=okay-----';

$data = file_get_contents('db/db.json');
$json = json_decode($data, true);

// file_put_contents('db/db.json', json_encode($json, JSON_PRETTY_PRINT), LOCK_EX);
layout("resources/views/pages/home.php", ["okay" => $okay]);
