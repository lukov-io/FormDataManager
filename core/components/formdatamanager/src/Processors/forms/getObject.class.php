<?php

use FormDataManager\Model\Forms;
use FormDataManager\Model\Handlers;
use MODX\Revolution\Processors\Model\GetProcessor;

class getHandlersProcessor extends GetProcessor {
    public $classKey = Forms::class;

    public function cleanup()
    {
        return $this->success('', $this->object);
    }

    /**
     * Used for adding custom data in derivative types
     *
     * @return void
     */
    public function beforeOutput()
    {
        $handlers = [];
        $currentHandlersId = [];

        foreach ($this->object->getMany("FormsHandlers") as $link) {
            $currentHandlersId[] = $link->get('handler');
        }

        foreach ($this->modx->getCollection(Handlers::class) as $handler) {
            $i = $handler->toArray();
            $i['isActive'] = in_array($i['id'], $currentHandlersId);
            $handlers[] = $i;
        }

        $this->object = $handlers;

    }
}
return 'getHandlersProcessor';