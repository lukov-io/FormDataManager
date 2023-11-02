<?php

if (!@include_once dirname(__FILE__, 5) . '/config.core.php') {
    require_once dirname(__FILE__, 4) . '/config.core.php';
};

require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
require_once MODX_CONNECTORS_PATH . 'index.php';
$corePath = $modx->getOption('formdatamanager.core_path', null, $modx->getOption('core_path') . 'components/formdatamanager/');
$modx->formdatamanager = $modx->services->get('FormDataManager');
$modx->lexicon->load('formdatamanager:default');

$path = $modx->getOption('processorsPath', $modx->formdatamanager->options, $corePath . 'src/Processors/');
$modx->request->handleRequest(array(
    'processors_path' => $path,
    'location' => '',
));