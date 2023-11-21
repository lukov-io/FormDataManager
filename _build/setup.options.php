<?php
$values = array(
    'token' => '',
    'chatId' => '',
);
switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
    case xPDOTransport::ACTION_UPGRADE:
        $setting = $modx->getObject('modSystemSetting',array('key' => PKG_NAME_LOWER . '.token'));
        if ($setting != null) { $values['token'] = $setting->get('value'); }
        unset($setting);

        $setting = $modx->getObject('modSystemSetting',array('key' => PKG_NAME_LOWER . '.chatId'));
        if ($setting != null) { $values['emailsFrom'] = $setting->get('value'); }
        unset($setting);

        break;
    case xPDOTransport::ACTION_UNINSTALL: break;
}

$output = '<label for="quip-emailsTo">Token:</label>
<input type="text" name="token" id="quip-emailsTo" width="300" value="'.$values['token'].'" />
<br /><br />

<label for="quip-emailsFrom">Chat Id:</label>
<input type="text" name="chatId" id="quip-emailsFrom" width="300" value="'.$values['chatId'].'" />
<br /><br />';

return $output;