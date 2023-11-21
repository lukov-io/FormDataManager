<?php

use FormDataManager\Model\Forms;
use MODX\Revolution\Processors\System\Settings\Update;


class FormDataManagerGeneralSettingsProcessor extends Update
{
    public $languageTopics = array('formdatamanager:default');
    private array $newDefaultHandlersId = [];
    private array $oldDefaultHandlersId = [];


    public function initialize()
    {
        $this->setDefaultProperties([
            'namespace' => "formdatamanager",
            'key'       => "formdatamanager.default_handlers",
        ]);
        return parent::initialize();
    }

    public function beforeSet()
    {
        $value = $this->getProperty("value");
        if ($value) {
            $value = array_keys($value);
        } else {
            $value = [];
        }

        $this->newDefaultHandlersId = $value;
        $this->oldDefaultHandlersId = explode(',',$this->object->get('value'));

        $this->setProperty("value", implode(',', $value));
        return parent::beforeSet();
    }

    public function afterSave()
    {
        $forms = $this->modx->getCollection(Forms::class);

        foreach ($forms as $form) {
            $form->setHandlers($this->newDefaultHandlersId, $this->oldDefaultHandlersId);
        }

        $this->updateTranslations($this->getProperties());
        $this->refreshURIs();
        $this->clearCache();

        return parent::afterSave();
    }
}

return 'FormDataManagerGeneralSettingsProcessor';