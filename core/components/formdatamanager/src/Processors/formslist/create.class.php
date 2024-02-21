<?php

use FormDataManager\Model\Forms;
use MODX\Revolution\Processors\Model\CreateProcessor;

class FormCreateProcessor extends CreateProcessor
{
    public $classKey = Forms::class;
    public $languageTopics = array('formdatamanager:default');
    public $objectType = 'formdatamanager.formdatamanager';

    public function process()
    {
        $FormDataManager = $this->modx->formdatamanager;
        $generator = $FormDataManager->getGenerator();
        $object = $this->getProperty('form');
        foreach ($object['fields'] as &$field) {
            $field = str_replace(' ', '_', $field);
        }

        $this->object = $generator->process($object);

        if (!$this->object) {
            return $this->failure($this->modx->lexicon($this->objectType . '_err_create'));
        }

        if (!$this->object->validate()) {
            $validator = $this->object->getValidator();
            if ($validator->hasMessages()) {
                foreach ($validator->getMessages() as $message) {
                    $this->addFieldError($message['field'], $this->modx->lexicon($message['message']));
                }
            }
        }

        if ($this->saveObject() === false) {
            $this->modx->error->checkValidation($this->object);

            return $this->failure($this->modx->lexicon($this->objectType . '_err_save'));
        }

        $this->logManagerAction();
        $this->afterSave();
        return $this->cleanup();
    }

    public function afterSave()
    {
        $handlersId = explode(',', $this->modx->getOption("formdatamanager.default_handlers"));
        $this->object->setHandlers($handlersId);
    }
}

return 'FormCreateProcessor';