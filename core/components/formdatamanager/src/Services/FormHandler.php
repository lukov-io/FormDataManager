<?php

namespace FormDataManager\Services;

use FormDataManager\Model\Forms;
use MODX\Revolution\modX;
use xPDO\Om\xPDOSimpleObject;

class FormHandler {

    const NAMESPACE = 'FormDataManager\Model\\';
    private modX $modx;
    private array $handlers = [];
    private array $currentHandlers = [];
    private array $errors = [];
    private string $formName;

    public function __construct(modX $modx)
    {
        $this->modx = $modx;
    }

    public function saveRequest(): string
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

        $formName = str_replace(' ', '_', $formName);
        $formData = [];
        $formFields = explode(',', $form->get("fields"));

        foreach ($formFields as $field) {
            $formData[$field] = $_POST[$field];
        }

        $newRequest = $this->modx->newObject('FormDataManager\Model\\' . $formName, $formData);
        if ($newRequest->save()) {
            return $this->success();
        }

        return $this->failure('can\'t save form');
    }

    public function process(Forms $form) {
        $formName = $this->formName = $form->get('formName');
        $formName = str_replace(' ', '_', $formName);
        $formRequests = $this->modx->getCollection(self::NAMESPACE . $formName, ["status" => 0]);

        if (empty($formRequests)) {
            return;
        }

        $this->createHandlers($form);

        foreach ($formRequests as $request) {
            $this->startHandlers($request);
        }
        $this->reset();
    }

    public function getHandler(string $handlerClass) {
        if (isset($this->handlers[$handlerClass]) && $this->handlers[$handlerClass] instanceof $handlerClass) {
            return $this->handlers[$handlerClass];
        }

        if (class_exists($handlerClass)) {
            $this->handlers[$handlerClass] = new $handlerClass($this->modx);
            return $this->handlers[$handlerClass];
        }
        return null;
    }

    private function startHandlers(xPDOSimpleObject $request) {
        $isDelivered = false;
        $requestId = $request->get('id');

        foreach ($this->currentHandlers as $handler) {
            $result = $handler->run($request);
            if ($result === true && !$isDelivered) {
                $isDelivered = true;
            }

            if (gettype($result) === 'string') {
                $this->errors[$this->formName][$requestId][] = $result;
            }
        }

        if ($isDelivered) {
            $request->set("status", 1);
            $request->save();
        }
    }

    private function createHandlers(Forms $form) {
        $handlerLinks = $form->getMany('FormsHandlers');

        foreach ( $handlerLinks as $link )
        {
            $handler = $link->getOne('Handlers');
            $handlerClass = $this->getHandler($handler->get('className'));

            if ($handlerClass) {
                $this->currentHandlers[] = $handlerClass;
            }
        }
    }

    private function reset() {
        $this->currentHandlers = [];
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

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }
}