<?php
const MODX_API_MODE = true;
const PACKAGE_NAME = 'FormDataManager';
const PKG_NAME_LOWER = 'formdatamanager';
const MODELS_PACKAGE = ["Forms", "Handlers", "FormsHandlers"];

require_once dirname(__FILE__,3).'/index.php';

$modx->initialize('mgr');

$modx->setLogLevel(modX::LOG_LEVEL_INFO);
$modx->setLogTarget(XPDO_CLI_MODE ? 'ECHO' : 'HTML');

if (!$modx->getObject('MODX\Revolution\modNamespace', ['name' => PKG_NAME_LOWER,])) {
    $nameSpaseSetting = $modx->newObject('MODX\Revolution\modNamespace');
    $nameSpaseSetting->fromArray(array(
        'name'        => PKG_NAME_LOWER,
        'path'        => '{base_path}' . PKG_NAME_LOWER . '/core/components/' . PKG_NAME_LOWER . '/',
        'assets_path' => '{base_path}' . PKG_NAME_LOWER . '/assets/components/' . PKG_NAME_LOWER . '/',
    ), '', true, true);

    $nameSpaseSetting->save();
}

if (!$corePath = $modx->getOption(PKG_NAME_LOWER . '.core_path')) {
    $q = $modx->runProcessor('System/Settings/Create',[
        'key'       => PKG_NAME_LOWER . '.core_path',
        'value'     => dirname(__DIR__) . '/core/components/' . PKG_NAME_LOWER . '/',
        'xtype'     => 'textfield',
        'namespace' => PKG_NAME_LOWER,
    ]);

    if ($q->isError()) {
        $modx->log(xPDO::LOG_LEVEL_ERROR,'access denied');
        die();
    }

    $corePath = $q->getObject();
    $corePath = $corePath["value"];
}

if (!$modx->getOption(PKG_NAME_LOWER . '.assets_url')) {
    $modx->runProcessor('System/Settings/Create', [
        'key'       => PKG_NAME_LOWER . '.assets_url',
        'value'     => '/' . PKG_NAME_LOWER . '/assets/components/' . PKG_NAME_LOWER . '/',
        'xtype'     => 'textfield',
        'namespace' => PKG_NAME_LOWER,
    ]);
}

$modx->initialize('mgr');

$settings = @include_once 'data/transport.settings.php';

if (!$settings) {
    $modx->log(\xPDO\xPDO::LOG_LEVEL_ERROR, 'cant include settings');
    exit();
}

foreach ($settings as $setting) {
    $setting->save();
}

$modx->setLogLevel(modX::LOG_LEVEL_INFO);
$modx->setLogTarget(XPDO_CLI_MODE ? 'ECHO' : 'HTML');

$sources = array(
    'model' => $corePath . 'src/',
    'schema_file' => $corePath . '/schema/' . PACKAGE_NAME .'.mysql.schema.xml'
);

$manager= $modx->getManager();
$generator= $manager->getGenerator();
if (!is_dir($sources['model'])) { $modx->log(xPDO::LOG_LEVEL_ERROR,'Model directory not found!'); die(); }
if (!file_exists($sources['schema_file'])) { $modx->log(xPDO::LOG_LEVEL_ERROR,'Schema file not found!'); die(); }
$generator->parseSchema($sources['schema_file'],$sources['model'], ['namespacePrefix' => 'FormDataManager']);

$modx->addPackage(PACKAGE_NAME . '\Model', $sources['model'], null, PACKAGE_NAME . '\\');

foreach (MODELS_PACKAGE as $item) {
    $manager->createObjectContainer(PACKAGE_NAME.'\Model\\' . $item);
}

$modx->log(modX::LOG_LEVEL_INFO, 'Done!');
