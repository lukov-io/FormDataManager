<?php

namespace FormDataManager\Handler;

use FormDataManager\Model\Forms;
use MODX\Revolution\modX;

class FormHandler {

    private modX $modx;
    private array $handlers = [];

    public function __construct(modX $modx)
    {
        $this->modx = $modx;
    }

    public function process(): string
    {
        if (!$_POST["formName"]) {
            http_response_code(400);
            return $this->failure('bad request');
        }

        $formName = $_POST["formName"];
        $form = $this->modx->getObject(Forms::class, ['formName' => $formName]);

        if (!$form) {
            return $this->failure('form does not exist');
        }

        $formData = [];
        $formFields = explode(',', $form->get("fields"));
        $handlerLinks = $form->getMany('FormsHandlers');


        foreach ($formFields as $field) {
            $formData[$field] = $_POST[$field];
        }

        $newRequest = $this->modx->newObject('FormDataManager\Model\\' . $formName, $formData);
        $newRequest->save();

        foreach ( $handlerLinks as $link )
        {
            $handler = $link->getOne('Handlers');
            $handlerClass = $this->getHandler($handler->get('className'));

            if ($handlerClass) {
                $handlerClass->run($newRequest);
            }
        }

        return $this->success();
    }

    private function getHandler(string $handlerClass) {
        if (isset($this->handlers[$handlerClass]) && $this->handlers[$handlerClass] instanceof $handlerClass) {
            return $this->handlers[$handlerClass];
        }
        if (class_exists($handlerClass)) {
            return new $handlerClass($this->modx);
        }
        return null;
    }

    private function success(): string
    {
        return json_encode(['success' => true]);
    }

    private function failure(string $message): string
    {
        return json_encode([
            'success' => false,
            "message" => $message
        ]);
    }
}