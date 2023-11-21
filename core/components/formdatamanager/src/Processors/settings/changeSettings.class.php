<?php

use MODX\Revolution\modSystemSetting;
use MODX\Revolution\Processors\Model\GetListProcessor;

class FormDataManagerChangeSettingsProcessor extends GetListProcessor
{
    public $classKey = modSystemSetting::class;
    public $languageTopics = array('formdatamanager:default');
    public $defaultSortField = 'key';
    public $defaultSortDirection = 'ASC';
    public $permission = 'settings';

    public function initialize()
    {
        $initialized = parent::initialize();
        $this->setDefaultProperties([
            'namespace' => "formdatamanager",
            'area' => false,
            'dateFormat' => $this->modx->getOption('manager_date_format') . ', ' . $this->modx->getOption('manager_time_format'),
        ]);

        return $initialized;
    }

    public function beforeQuery(): bool
    {
        $settings = $this->getProperty("form");
        if (empty($settings)) {
            return "bad request";
        }

        $this->timeStart = new DateTime();

        foreach ($settings as $key => $value) {
            $val = $value;
            if (is_array($val)) {
                $val = implode(',', $val);
            }

            $q = $this->modx->runProcessor("System/Settings/Update", ["key" => $key, "value" => $val, "namespace" => "formdatamanager"]);
            if ($q->isError()) {
                return false;
            }
        }

        return true;
    }

    public function prepareQueryBeforeCount(xPDOQuery $c): xPDOQuery
    {
        $c->where([
            "namespace" => "formdatamanager",
            "editedon:>=" => $this->timeStart->format("Y-m-d H:i:s")
        ]);
        return $c;
    }
}

return 'FormDataManagerChangeSettingsProcessor';