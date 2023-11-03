<?php

use MODX\Revolution\Processors\ModelProcessor;

class getFormMetaProcessor extends ModelProcessor {
    const NAMESPACE = "FormDataManager\\Model\\";
    public $classKey = '';

    public function initialize(): bool
    {
        $this->classKey = self::NAMESPACE . $this->getProperty('class');


        return parent::initialize();
    }

    public function process()
    {
        $meta = $this->modx->getFieldMeta($this->classKey);

        return $this->success('', $meta);
    }
}
return 'getFormMetaProcessor';