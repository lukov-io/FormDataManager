<?php
const MODX_API_MODE = true;

require_once dirname(__FILE__,3).'/index.php';

$modx->initialize('mgr');

$modx->setLogLevel(modX::LOG_LEVEL_INFO);
$modx->setLogTarget(XPDO_CLI_MODE ? 'ECHO' : 'HTML');

$sources = array(
    'model' => $modx->getOption('formdatamanager.core_path').'src/',
    'schema_file' => $modx->getOption('formdatamanager.core_path').'/schema/FormDataManager.mysql.schema.xml'
);

$manager= $modx->getManager();
$generator= $manager->getGenerator();
if (!is_dir($sources['model'])) { $modx->log(xPDO::LOG_LEVEL_ERROR,'Model directory not found!'); die(); }
if (!file_exists($sources['schema_file'])) { $modx->log(xPDO::LOG_LEVEL_ERROR,'Schema file not found!'); die(); }
$generator->parseSchema($sources['schema_file'],$sources['model'], ['namespacePrefix' => 'FormDataManager']);


$manager->createObjectContainer('FormDataManager\Model\Forms');
$manager->createObjectContainer('FormDataManager\Model\Handlers');
$manager->createObjectContainer('FormDataManager\Model\FormsHandlers');


$modx->log(modX::LOG_LEVEL_INFO, 'Done!');