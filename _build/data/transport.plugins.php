<?php


$plugins =[];

$plugin = $modx->newObject('modPlugin');
$plugin->fromArray(array(
    'id' => 0,
    'name' => 'CRFPlugin-request',
    'category' => 0,
    'description' => "",
    'plugincode' => getContent($sources['elements'] . 'plugins/CRFPlugin-request.php'),
    'static' => false,
    'source' => 1,
    'static_file' => 'core/components/' . PKG_NAME_LOWER . '/elements/plugins/CRFPlugin-request.php',
), '', true, true);

$event = $modx->newObject('modPluginEvent');
$event->fromArray(array(
    'event' => 'OnPageNotFound',
    'priority' => 0,
    'propertyset' => 0,
), '', true, true);

$plugin->addMany($event);
$plugins[] = $plugin;

return $plugins;