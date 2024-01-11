<?php

namespace FormDataManager;

use FormDataManager\Interfaces\HandlerInterface;
use FormDataManager\Model\Forms;
use FormDataManager\Model\Handlers;
use FormDataManager\Services\FormHandler;
use FormDataManager\Services\ModelGenerator;
use MODX\Revolution\modX;
use xPDO\xPDO;

class FormDataManager
{
    public modX $modx;
    public array $options;
    public array $classMap;
    private ?ModelGenerator $generator = null;
    private ?FormHandler $formHandler = null;

    public function __construct(modX &$modx, array $config = [])
    {
        $this->modx =& $modx;

        $corePath = $this->modx->getOption('formdatamanager.core_path',$config,$this->modx->getOption('core_path').'components/formdatamanager/');
        $assetsPath = $this->modx->getOption('formdatamanager.assets_path',$config,$this->modx->getOption('core_path').'components/formdatamanager/');
        $assetsUrl = $this->modx->getOption('formdatamanager.assets_url',$config,$this->modx->getOption('assets_url').'components/formdatamanager/');

        $this->options = array_merge([
            'corePath' => $corePath,
            'connectorUrl' => $assetsUrl.'connector.php',
            'modelPath' => $corePath . 'src/Model/',
            'chunksPath' => $corePath . 'elements/chunks/',
            'snippetsPath' => $corePath . 'elements/snippets/',
            'templatesPath' => $corePath . 'templates/',
            'processorsPath' => $corePath . "src/Processors/",
            'assetsPath' => $assetsPath,
            'assetsUrl' => $assetsUrl,
            'jsUrl' => $assetsUrl . 'mgr/js/',
            'cssUrl' => $assetsUrl . 'mgr/css/',
            'webAssetsUrl' => $assetsUrl . 'web/',
        ], $config);

        $this->modx->lexicon->load('formdatamanager:default');
    }

    public function getFieldsName(): array
    {
        $result = [];

        foreach ($this->modx->getCollection(Forms::class) as $form) {
            $className = $form->get("formName");
            $result[$className] = explode(',', $form->get("fields"));
        }

        return $result;
    }

    public function getGenerator(): ModelGenerator
    {
        if (!$this->generator) {
            $this->generator = new ModelGenerator($this->modx);
        }
        return $this->generator;
    }

    public function test() {
        $sh = curl_init('https://icslegal.com/regulatory-bodies-icslegal.php');
        curl_setopt($sh, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($sh);
        $document = new \DOMDocument();
        $document->loadHTML($response);
        $finder = new \DomXPath($document);
        $classname="body-content ckEditor-content new-font";
        $nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");

        $tmp_dom = new \DOMDocument();

        foreach ($nodes as $node)
        {
            $tmp_dom->appendChild($tmp_dom->importNode($node,true));
        }

        $innerHTML = trim($tmp_dom->saveHTML());
        echo $innerHTML;
        echo "<pre>";
        var_dump($nodes);
        echo "</pre>";
    }

    public function registerHandler(string $name, string $className): bool
    {
        if ($this->modx->getObject(Handlers::class, ['className'=>$className])) {
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, "Can't registered handler: Handler already exist");
            return false;
        }

        if (!class_exists($className)) {
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, "Can't registered handler: Class not found");
            return false;
        }

        $class = new $className($this->modx);

        if (!($class instanceof HandlerInterface)) {
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, "Can't registered handler: Class not implement HandlerInterface");
            return false;
        }

        unset($class);

        $newHandler = $this->modx->newObject(Handlers::class, ['name' => $name, 'className' => $className]);

        if (!$newHandler) {
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, "Can't registered handler: Error loading handler model");
        }

        return $newHandler->save();
    }

    public function removeHandler(string $className) {
        $object = $this->modx->getObject(Handlers::class, ['className'=>$className]);
        if ($object) {
            $object->remove();
        }
    }

    public function getHandler(): FormHandler
    {
        if (!$this->formHandler) {
            $this->formHandler = new FormHandler($this->modx);
        }
        return $this->formHandler;
    }
}