<?php
$menu= $modx->newObject('modMenu');
$menu->fromArray(array(
    'text' => 'FormDataManager',
    'action' => 'home',
    'namespace' => "formdatamanager",
    'parent' => 'components',
    'description' => 'Create and manage forms',
    'icon' => '',
    'menuindex' => 0,
    'params' => '',
    'handler' => '',
),'',true,true);
return $menu;