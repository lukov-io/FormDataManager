<?php

const MODX_API_MODE = true;
if (!@include_once dirname(__FILE__, 5) . '/index.php') {
    require_once dirname(__FILE__, 4) . '/index.php';
};

header_remove();
header('Content-Type: application/json; charset=utf-8');

$FormDataManager = $modx->services->get("FormDataManager");

if (!$FormDataManager) {
    exit(json_encode(["status" => false]));
}

$handler = $FormDataManager->getHandler();
echo $handler->saveRequest();