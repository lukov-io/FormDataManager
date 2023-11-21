<?php

use FormDataManager\Model\Forms;
use MODX\Revolution\Processors\Model\UpdateProcessor;

class UpdateFormHandlersProcessor extends UpdateProcessor {
    public $classKey = Forms::class;
    private array $handlersId;

    public function cleanup()
    {
        return $this->success('', $this->object);
    }

    public function beforeSet(): bool
    {
        $value = $this->getProperty("value");
        if (is_array($value)) {
            $value = array_keys($value);
        } else {
            $value = [];
        }

        $this->handlersId = $value;
        $this->unsetProperty("value");

        return parent::beforeSet();
    }

    public function afterSave()
    {
        $this->object->setHandlers($this->handlersId);
    }
}
return 'UpdateFormHandlersProcessor';