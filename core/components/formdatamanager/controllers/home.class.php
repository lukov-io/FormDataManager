<?php

use FormDataManager\FormDataManager;
use FormDataManager\Model\Handlers;

class FormDataManagerHomeManagerController extends modExtraManagerController
{
    
    public FormDataManager $formmanager;

    public function initialize(): void
    {
        $handlers = [];
        $defaultHandlers = $this->modx->getOption('formdatamanager.default_handlers');
        $defaultHandlers = explode(',', $defaultHandlers);

        foreach ($this->modx->getCollection(Handlers::class) as $item) {
            $handler = $item->toArray();
            $handler['isDefault'] = in_array($handler['id'], $defaultHandlers);

            $handlers[] = $handler;
        }

        $this->formmanager = $this->modx->services->get('FormDataManager');
        //$this->addCss($this->formmanager->options['cssUrl'] . 'mgr.css');
        $this->addJavascript($this->formmanager->options['jsUrl'] . 'formdatamanager.js');
        $this->addHtml('<script type="text/javascript">
            Ext.onReady(function() {
                FormDataManager.config = ' . $this->modx->toJSON($this->formmanager->options) . ';
                FormDataManager.config.classMap = ' . $this->modx->toJSON($this->formmanager->getFieldsName()) . ';
                FormDataManager.config.handlers = ' . $this->modx->toJSON($handlers) . ';
                FormDataManager.config.chatId = ' . $this->modx->toJSON(explode(",", $this->modx->getOption('formdatamanager.chat_id'))) . ';
                FormDataManager.config.apiToken = "' . $this->modx->getOption('formdatamanager.token_tg') . '";
            });
            </script>');
        parent::initialize();
    }
    public function getLanguageTopics()
    {
        return array('formdatamanager:default');
    }

    public function checkPermissions()
    {
        return true;
    }

    public function process(array $scriptProperties = array())
    {
    }

    public function getPageTitle()
    {
        return $this->modx->lexicon('Form manager');
    }

    public function loadCustomCssJs()
    {
        $this->addJavascript($this->formmanager->options['jsUrl'].'widgets/formdatamanager.grid.js');
        $this->addJavascript($this->formmanager->options['jsUrl'].'widgets/general.settings.panel.js');
        $this->addJavascript($this->formmanager->options['jsUrl'].'widgets/tg.settings.panel.js');
        $this->addJavascript($this->formmanager->options['jsUrl'].'widgets/formdatamanager.window.create.js');
        $this->addJavascript($this->formmanager->options['jsUrl'].'widgets/formdatamanager.window.update.js');
        $this->addJavascript($this->formmanager->options['jsUrl'].'widgets/forms.grid.js');
        $this->addJavascript($this->formmanager->options['jsUrl'] . 'widgets/home.panel.js');
        $this->addLastJavascript($this->formmanager->options['jsUrl'] . 'sections/index.js');
    }

    public function getTemplateFile()
    {
        return $this->formmanager->options['templatesPath'] . 'home.tpl';
    }
}