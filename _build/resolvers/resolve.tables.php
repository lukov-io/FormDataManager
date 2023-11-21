<?php

use MODX\Revolution\modX;
use xPDO\Transport\xPDOTransport;
use xPDO\xPDO;

/**
 * @var \xPDO\Transport\xPDOTransport $transport
 * @var array $object
 * @var array $options
 */

if (!function_exists('updateTableColumns')) {
    /**
     * @param $modx
     * @param string $table
     */
    function updateTableColumns($modx, $table)
    {
        $tableName = $modx->getTableName($table);
        $tableName = str_replace('`', '', $tableName);
        $dbname = $modx->getOption('dbname');

        $c = $modx->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = :dbName AND table_name = :tableName");
        $c->bindParam(':dbName', $dbname);
        $c->bindParam(':tableName', $tableName);
        $c->execute();

        $unusedColumns = $c->fetchAll(PDO::FETCH_COLUMN, 0);
        $unusedColumns = array_flip($unusedColumns);

        $meta = $modx->getFieldMeta($table);
        $columns = array_keys($meta);

        $m = $modx->getManager();

        foreach ($columns as $column) {
            if (isset($unusedColumns[$column])) {
                $m->alterField($table, $column);
                $modx->log(modX::LOG_LEVEL_INFO, ' -- altered column: ' . $column);
                unset($unusedColumns[$column]);
            } else {
                $m->addField($table, $column);
                $modx->log(modX::LOG_LEVEL_INFO, ' -- added column: ' . $column);
            }
        }

        foreach ($unusedColumns as $column => $v) {
            $m->removeField($table, $column);
            $modx->log(modX::LOG_LEVEL_INFO, ' -- removed column: ' . $column);
        }
    }
}

if (!function_exists('updateTableIndexes')) {
    /**
     * @param $modx
     * @param string $table
     */
    function updateTableIndexes($modx, $table)
    {
        $tableName = $modx->getTableName($table);
        $tableName = str_replace('`', '', $tableName);
        $dbname = $modx->getOption('dbname');

        $c = $modx->prepare("SELECT DISTINCT INDEX_NAME FROM INFORMATION_SCHEMA.STATISTICS WHERE table_schema = :dbName AND table_name = :tableName AND INDEX_NAME != 'PRIMARY'");
        $c->bindParam(':dbName', $dbname);
        $c->bindParam(':tableName', $tableName);
        $c->execute();

        $oldIndexes = $c->fetchAll(PDO::FETCH_COLUMN, 0);

        $m = $modx->getManager();

        foreach ($oldIndexes as $oldIndex) {
            $m->removeIndex($table, $oldIndex);
            $modx->log(modX::LOG_LEVEL_INFO, ' -- removed index: ' . $oldIndex);
        }

        $meta = $modx->getIndexMeta($table);
        $indexes = array_keys($meta);

        foreach ($indexes as $index) {
            if ($index == 'PRIMARY') continue;
            $m->addIndex($table, $index);
            $modx->log(modX::LOG_LEVEL_INFO, ' -- added index: ' . $index);
        }
    }
}

if (!function_exists('alterTable')) {
    /**
     * @param $modx
     * @param string $table
     */
    function alterTable($modx, $table)
    {
        $modx->log(modX::LOG_LEVEL_INFO, ' - Updating columns');
        updateTableColumns($modx, $table);

        $modx->log(modX::LOG_LEVEL_INFO, ' - Updating indexes');
        updateTableIndexes($modx, $table);
    }
}

if ($object->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
            $modx =& $object->xpdo;
            $modelPath = $modx->getOption('formdatamanager.core_path',null,$modx->getOption('core_path').'components/formdatamanager/').'src/';
            $modx->addPackage('FormDataManager',$modelPath, null, 'FormDataManager\\');

            $manager = $modx->getManager();

            $manager->createObjectContainer('FormDataManager\Model\Forms');
            $manager->createObjectContainer('FormDataManager\Model\FormsHandlers');
            $manager->createObjectContainer('FormDataManager\Model\Handlers');

            $newHandlerTg = $this->modx->newObject('FormDataManager\Model\Handlers', ['name' => "Telegram", 'className' => "FormDataManager\Handler\Handlers\Telegram"]);
            $newHandlerTg->save();
            $newHandlerEmail = $this->modx->newObject('FormDataManager\Model\Handlers', ['name' => "Email", 'className' => "FormDataManager\Handler\Handlers\Email"]);

            break;
        case xPDOTransport::ACTION_UPGRADE:
            $modx =& $transport->xpdo;

            $tables = [
                "FormDataManager\Model\Forms",
                'FormDataManager\Model\FormsHandlers',
                'FormDataManager\Model\Handlers'
            ];

            foreach ($tables as $table) {
                $modx->log(xPDO::LOG_LEVEL_INFO, 'Altering table: ' . $table);
                alterTable($modx, $table);
            }

            break;
    }
}
return true;