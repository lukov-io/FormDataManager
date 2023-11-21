<?php
namespace FormDataManager\Model;

use xPDO\xPDO;

/**
 * Class Forms
 *
 * @property string $formName
 * @property string $fields
 *
 * @property \FormDataManager\Model\FormsHandlers[] $FormsHandlers
 *
 * @package FormDataManager\Model
 */
class Forms extends \xPDO\Om\xPDOSimpleObject
{
    public function setHandlers(array $newHandlers, array $oldHandlers = []): void
    {
        $currentHandlers = [];

        foreach ($this->getMany('FormsHandlers') as $value) {
            $handlerId = $value->get("handler");

            if ($this->checkRemove($handlerId, $newHandlers, $oldHandlers)) {
                $value->remove();
                continue;
            }
            $currentHandlers[] = $handlerId;
        }

        if (empty($newHandlers)) return;

        foreach ($this->xpdo->getCollection(Handlers::class, ["id:IN" => $newHandlers]) as $handler) {
            $handlerId = $handler->get("id");

            if (in_array($handlerId, $currentHandlers)) {
                continue;
            }

            $formHandler = $this->xpdo->newObject(FormsHandlers::class, ["form" => $this->get("id"), "handler" => $handlerId]);
            $formHandler->save();
        }
    }

    private function checkRemove(int $id, array $new, array $old): bool
    {
        return !in_array($id, $new) && (in_array($id, $old) || empty($old));
    }
}
