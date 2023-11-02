<?php

use FormDataManager\Model\Forms;
use MODX\Revolution\Processors\Model\RemoveProcessor;
class FormRemoveProcessor extends RemoveProcessor
{
    public $classKey = Forms::class;
    public $languageTopics = array('formdatamanager:default');
    public $objectType = 'formdatamanager.formdatamanager';

    public function afterRemove()
    {
        $className = $this->object->get('formName');
        $modelPath = $this->modx->formdatamanager->options['modelPath'];

        $manager = $this->modx->getManager();
        $manager->removeObjectContainer('FormDataManager\Model\\' . $className);

        unlink($modelPath . 'mysql/' . $className . '.php');
        unlink($modelPath . $className . '.php');

        $meta = file_get_contents($modelPath . 'metadata.mysql.php');
        $meta = preg_replace("/            \d+ => 'FormDataManager\\\\\\\\Model\\\\\\\\$className',\n/", '', $meta );

        file_put_contents($modelPath . 'metadata.mysql.php', $meta);
        return parent::afterRemove();
    }
}

return 'FormRemoveProcessor';