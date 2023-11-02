<?php

use MODX\Revolution\Processors\Model\GetListProcessor;
class FormsGetListProcessor extends GetListProcessor {
    const NAMESPACE = "FormDataManager\\Model\\";
    public $classKey = '';
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'DESC';

    public function initialize(): bool
    {
        $this->classKey = self::NAMESPACE . $this->getProperty('class');

        return parent::initialize();
    }


    /**
     * @throws Exception
     */
    public function prepareQueryBeforeCount(xPDOQuery $c): xPDOQuery
    {
        $query = $this->getProperty('query');
        if (!empty($query)) {
            $query = json_decode($query);

            $queryArg = [];

            if (isset($query->from)) {
                $dateStart = new DateTime($query->from);
                $queryArg['date:>='] = $dateStart->format("Y-m-d H:i:s");
            }

            if (isset($query->to)) {
                $dateTo = new DateTime($query->to);
                $queryArg['date:<='] = $dateTo->format("Y-m-d H:i:s");
            }
            $c->where($queryArg);
        }

        return $c;
    }

    private function prepareOutputStatus(array &$array): void
    {
        $count = count($array);

        for ($i = 0; $i < $count; $i++) {
            if ($array[$i]["status"] === 0) {
                $array[$i]["status"] = "Доставлено";
            } elseif ($array[$i]["status"] === 1) {
                $array[$i]["status"] = "Не доставлено: ошибка подключения";
            } else {
                $array[$i]["status"] = "Не доставлено: ошибка доставки";
            }
        }
    }
}
return 'FormsGetListProcessor';