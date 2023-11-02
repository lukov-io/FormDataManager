<?php

namespace FormDataManager;

use FormDataManager\Generator\ModelGenerator;
use FormDataManager\Model\Forms;
use MODX\Revolution\modX;
use xPDO\Om\xPDOSimpleObject;

class FormDataManager
{
    public modX $modx;
    public array $options;
    private ModelGenerator $generator;

    /**
     * @throws \ReflectionException
     */
    public function __construct(modX &$modx, array $config = [])
    {
        $this->modx =& $modx;

        $corePath = $this->modx->getOption('formdatamanager.core_path',$config,$this->modx->getOption('core_path').'components/formdatamanager/');
        $assetsPath = $this->modx->getOption('formdatamanager.assets_path',$config,$this->modx->getOption('core_path').'components/formdatamanager/');
        $assetsUrl = $this->modx->getOption('formdatamanager.assets_url',$config,$this->modx->getOption('assets_url').'components/formdatamanager/');

        $this->options = array_merge([
            "classMap" => [],
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

        $classMap = [
            str_replace('FormDataManager\Model\\', '', Forms::class) => [
                "extends" => xPDOSimpleObject::class
            ]
        ];

        foreach ($modx->getCollection(Forms::class) as $form) {
            $className = $form->get("formName");
            $this->options["classMap"][$className] = explode(',', $form->get("fields"));
            $classMap[$className] = ["extends" => xPDOSimpleObject::class];
        }

        $this->generator = new ModelGenerator($modx, $classMap);

        $this->modx->lexicon->load('formdatamanager:default');
    }

    public function process() {


        echo "<pre>";

        echo "</pre>";
    }

    public function getGenerator(): ModelGenerator
    {
        return $this->generator;
    }
}