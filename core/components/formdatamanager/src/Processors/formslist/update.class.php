<?php

use FormDataManager\Model\Forms;
use MODX\Revolution\Processors\Model\UpdateProcessor;

class DoodleUpdateProcessor extends UpdateProcessor {
    public $classKey = Forms::class;
    public $languageTopics = array('formdatamanager:default');
    public $objectType = 'formdatamanager.formdatamanager';

    public function beforeSet()
    {
        $newMeta = $this->getProperty("form");

        $classname = 'FormDataManager\Model\\' . $newMeta["class"];


        $newMeta = $this->getProperty("form");
        $currentMeta = $this->modx->getFieldMeta('FormDataManager\Model\\' . $newMeta["class"]);
        $newMeta = $this->getProperty("form");
        $newMeta = $newMeta['fieldMeta'];

        var_dump($currentMeta);
        var_dump($newMeta);
        return parent::beforeSet();
    }
}
return 'DoodleUpdateProcessor';
