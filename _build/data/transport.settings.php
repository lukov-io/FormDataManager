<?php
$settings = array();

$tmp = array(
    PKG_NAME_LOWER . '.default_handlers' => array(
        'value' => '1,2',
        'xtype' => 'textfield',
        'name'  => 'Default handlers',
        'area' => ''
    ),PKG_NAME_LOWER . '.chat_id' => array(
        'value' => '',
        'xtype' => 'textfield',
        'name'  => 'Id chats',
        'area' => ''
    ),
    PKG_NAME_LOWER . '.token_tg' => array(
        'value' => '',
        'xtype' => 'textfield',
        'name'  => 'token tg bot',
        'area' => ''
    ),
);


foreach ($tmp as $k => $v) {
    /* @var modSystemSetting $setting */
    $setting = $modx->newObject('modSystemSetting');
    $setting->fromArray(array_merge(
        array(
            'key' => $k,
            'name' => $v['name'],
            'namespace' => PKG_NAME_LOWER
        ), $v
    ),'',true,true);

    $settings[] = $setting;
}

return $settings;
